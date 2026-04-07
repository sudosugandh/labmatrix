<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\Response;

class PublicBillPaymentController extends Controller
{
    /**
     * Show the public payment page for a bill (by token). No auth required.
     */
    public function show(string $token): View|Response
    {
        $bill = Bill::where('payment_token', $token)
            ->with([
                'patient',
                'lab',
                'appointment',
                'testOrders' => fn ($q) => $q->with(['test', 'package'])->orderBy('id'),
            ])
            ->first();

        if (!$bill) {
            abort(404, 'Payment link invalid or expired.');
        }

        if ($bill->due_amount <= 0) {
            return response()->view('bills.pay-already-paid', ['bill' => $bill], 200);
        }

        if ($bill->status === 'cancelled') {
            abort(404, 'This bill has been cancelled.');
        }

        $settings = WebsiteSetting::where('lab_id', $bill->lab_id)->first();
        $gateway = $settings ? $settings->payment_gateway : null;
        $paymentsEnabled = $settings ? (bool) $settings->payments_enabled : false;

        return view('bills.pay-public', [
            'bill' => $bill,
            'brandSettings' => $settings,
            'gateway' => $gateway,
            'paymentsEnabled' => $paymentsEnabled,
            'razorpayKey' => ($gateway === 'razorpay' && $settings) ? ($settings->payment_api_key ?? '') : '',
        ]);
    }

    /**
     * Create a Razorpay order and return order_id for frontend checkout.
     */
    public function createRazorpayOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $bill = Bill::where('payment_token', $request->token)->first();
        if (!$bill || $bill->due_amount <= 0) {
            return response()->json(['error' => 'Invalid or already paid bill.'], 400);
        }

        $settings = WebsiteSetting::where('lab_id', $bill->lab_id)->first();
        if (!$settings || !$settings->payments_enabled || ($settings->payment_gateway ?? '') !== 'razorpay' || empty($settings->payment_api_key) || empty($settings->payment_api_secret)) {
            return response()->json(['error' => 'Razorpay is not configured or payments are disabled.'], 400);
        }

        $amountPaise = (int) round($bill->due_amount * 100);
        if ($amountPaise < 100) {
            return response()->json(['error' => 'Amount too small.'], 400);
        }

        try {
            $api = new \Razorpay\Api\Api($settings->payment_api_key, $settings->payment_api_secret);
            $order = $api->order->create([
                'receipt' => 'bill-' . $bill->id,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'notes' => ['bill_id' => (string) $bill->id, 'bill_number' => $bill->bill_number],
            ]);

            return response()->json([
                'order_id' => $order->id,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'key' => $settings->payment_api_key,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not create order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify Razorpay payment and record payment.
     */
    public function verifyRazorpay(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'token' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $bill = Bill::where('payment_token', $request->token)->first();
        if (!$bill || $bill->due_amount <= 0) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Invalid or already paid bill.'], 400);
            }
            return redirect()->to('/pay-bill/' . $request->token)->with('error', 'Invalid or already paid bill.');
        }

        $settings = WebsiteSetting::where('lab_id', $bill->lab_id)->first();
        if (! $settings || ($settings->payment_gateway ?? '') !== 'razorpay' || empty($settings->payment_api_secret)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Razorpay not configured.'], 400);
            }
            return redirect()->to('/pay-bill/' . $request->token)->with('error', 'Payment gateway not configured.');
        }

        try {
            $api = new \Razorpay\Api\Api($settings->payment_api_key, $settings->payment_api_secret);
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Payment verification failed.'], 400);
            }
            return redirect()->to('/pay-bill/' . $request->token)->with('error', 'Payment verification failed.');
        }

        DB::beginTransaction();
        try {
            Payment::create([
                'lab_id' => $bill->lab_id,
                'bill_id' => $bill->id,
                'amount' => $bill->due_amount,
                'payment_method' => 'online',
                'transaction_id' => $request->razorpay_payment_id,
                'payment_date' => now(),
                'notes' => 'Paid via Razorpay (payment link)',
                'received_by' => null,
            ]);
            $bill->updatePaymentStatus();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Failed to record payment.'], 500);
            }
            return redirect()->to('/pay-bill/' . $request->token)->with('error', 'Failed to record payment.');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect' => url('/pay-bill/' . $bill->payment_token . '/success')]);
        }
        return redirect()->to('/pay-bill/' . $bill->payment_token . '/success')->with('success', 'Payment successful.');
    }

    /**
     * Create Stripe Checkout Session and return URL to redirect.
     */
    public function createStripeSession(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $bill = Bill::where('payment_token', $request->token)->with('patient')->first();
        if (! $bill || $bill->due_amount <= 0) {
            return response()->json(['error' => 'Invalid or already paid bill.'], 400);
        }

        $settings = WebsiteSetting::where('lab_id', $bill->lab_id)->first();
        if (! $settings || ($settings->payment_gateway ?? '') !== 'stripe') {
            return response()->json(['error' => 'Stripe is not configured. Put your Stripe Secret key (starts with sk_) in the API Secret field under Settings → Payments.'], 400);
        }

        $secret = $settings->payment_api_secret ?? '';
        if ($secret === '') {
            return response()->json(['error' => 'Stripe is not configured. Put your Stripe Secret key (starts with sk_) in the API Secret field under Settings → Payments.'], 400);
        }

        if (! str_starts_with($secret, 'sk_')) {
            return response()->json([
                'error' => 'Invalid Stripe secret key. Use the Secret key from Stripe Dashboard (starts with sk_test_ or sk_live_), not the Publishable key (pk_). Paste it in API Secret.',
            ], 400);
        }

        $amountCents = (int) round($bill->due_amount * 100);
        if ($amountCents < 50) {
            return response()->json(['error' => 'Amount too small.'], 400);
        }

        $patientLabel = $bill->patient?->name ?? 'Patient';

        try {
            // Classic Stripe PHP API (avoids StripeClient, which may be missing on some installs).
            \Stripe\Stripe::setApiKey($secret);
            // INR Checkout: cards + UPI. Turn on UPI in Stripe Dashboard → Settings → Payment methods
            // (India). No app change needed after that—Checkout shows both.
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card', 'upi'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => 'Bill '.$bill->bill_number,
                            'description' => 'Payment for bill #'.$bill->bill_number.' — '.$patientLabel,
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/pay-bill/'.$bill->payment_token.'/stripe-success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/pay-bill/'.$bill->payment_token),
                'metadata' => ['bill_id' => (string) $bill->id],
            ]);

            return response()->json(['url' => $session->url]);
        } catch (ApiErrorException $e) {
            Log::warning('Stripe Checkout session failed', [
                'bill_id' => $bill->id,
                'stripe_code' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout unexpected error', ['bill_id' => $bill->id, 'exception' => $e]);

            return response()->json([
                'error' => 'Could not start payment: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Stripe success return: verify session and record payment if not already recorded.
     */
    public function stripeSuccess(Request $request, string $token): View|Response
    {
        $bill = Bill::where('payment_token', $token)->with('patient')->first();
        if (!$bill) {
            abort(404, 'Payment link invalid.');
        }

        $sessionId = $request->query('session_id');
        if ($sessionId && $bill->due_amount > 0) {
            $settings = WebsiteSetting::where('lab_id', $bill->lab_id)->first();
            if ($settings && ($settings->payment_gateway ?? '') === 'stripe' && ! empty($settings->payment_api_secret)) {
                try {
                    \Stripe\Stripe::setApiKey($settings->payment_api_secret);
                    $session = \Stripe\Checkout\Session::retrieve($sessionId);
                    if ($session->payment_status === 'paid' && ($session->metadata->bill_id ?? '') == (string) $bill->id) {
                        $paymentIntentId = $session->payment_intent ?? null;
                        $existing = Payment::where('bill_id', $bill->id)->where('transaction_id', $sessionId)->exists();
                        if (!$existing) {
                            DB::beginTransaction();
                            try {
                                Payment::create([
                                    'lab_id' => $bill->lab_id,
                                    'bill_id' => $bill->id,
                                    'amount' => $bill->due_amount,
                                    'payment_method' => 'online',
                                    'transaction_id' => $sessionId,
                                    'payment_date' => now(),
                                    'notes' => 'Paid via Stripe (payment link)',
                                    'received_by' => null,
                                ]);
                                $bill->updatePaymentStatus();
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollBack();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Log and show success anyway if bill is paid
                }
            }
        }

        return view('bills.pay-success', ['bill' => $bill]);
    }

    /**
     * Success page for Razorpay (after verify redirect).
     */
    public function success(string $token): View|Response
    {
        $bill = Bill::where('payment_token', $token)->with('patient')->first();
        if (!$bill) {
            abort(404, 'Payment link invalid.');
        }

        return view('bills.pay-success', ['bill' => $bill]);
    }
}
