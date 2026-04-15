<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripePlatformWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $secret = config('services.stripe_platform.webhook_secret');
        if (! $secret) {
            abort(503, 'Stripe platform webhook is not configured.');
        }

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            abort(400, 'Invalid payload or signature.');
        }

        switch ($event->type) {
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $this->syncLabFromStripeSubscription($subscription, $event->type);
                break;
            default:
                break;
        }

        return response('OK', 200);
    }

    protected function syncLabFromStripeSubscription(object $subscription, string $eventType): void
    {
        $lab = null;
        if (! empty($subscription->metadata->lab_id)) {
            $lab = Lab::find((int) $subscription->metadata->lab_id);
        }
        if (! $lab && ! empty($subscription->customer)) {
            $lab = Lab::where('stripe_customer_id', $subscription->customer)->first();
        }
        if (! $lab) {
            \Log::warning('Stripe platform webhook: no lab matched subscription', [
                'subscription_id' => $subscription->id ?? null,
                'customer' => $subscription->customer ?? null,
            ]);

            return;
        }

        if ($eventType === 'customer.subscription.deleted') {
            $lab->update([
                'stripe_subscription_id' => null,
                'subscription_status' => 'cancelled',
            ]);

            return;
        }

        $stripeStatus = (string) ($subscription->status ?? '');
        $map = [
            'active' => 'active',
            'trialing' => 'active',
            'past_due' => 'suspended',
            'canceled' => 'cancelled',
            'unpaid' => 'expired',
            'incomplete' => 'expired',
            'incomplete_expired' => 'expired',
            'paused' => 'suspended',
        ];
        $subscriptionStatus = $map[$stripeStatus] ?? 'active';

        $end = $subscription->current_period_end ?? null;
        $endDate = $end ? Carbon::createFromTimestamp((int) $end) : null;

        $lab->update([
            'stripe_subscription_id' => $subscription->id,
            'stripe_customer_id' => $subscription->customer ?: $lab->stripe_customer_id,
            'subscription_status' => $subscriptionStatus,
            'subscription_end_date' => $endDate?->toDateString(),
        ]);
    }
}
