<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:80',
            'message' => 'required|string|max:5000',
        ]);

        $labId = currentLabId();
        if (! $labId) {
            return redirect()->back()->with('error', 'Unable to send message. Please try again later.');
        }

        $inquiry = ContactInquiry::create([
            'lab_id' => $labId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
        ]);

        $settings = WebsiteSetting::getForCurrentLab();
        $to = trim((string) ($settings->contact_email ?? '')) ?: trim((string) ($settings->email ?? ''));
        $labName = trim((string) ($settings->brand_name ?? '')) ?: (currentLab()?->name ?? 'Lab');

        if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($to)->send(new ContactInquirySubmitted($inquiry, $labName));
            } catch (\Throwable $e) {
                Log::warning('Contact inquiry email failed: '.$e->getMessage(), ['inquiry_id' => $inquiry->id]);
            }
        }

        return redirect()
            ->to(url('/contact'))
            ->with('success', 'Thank you — we received your message and will get back to you shortly.');
    }
}
