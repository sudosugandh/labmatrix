<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquirySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactInquiry $inquiry,
        public string $labName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Website contact: '.$this->inquiry->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.website.contact-inquiry',
        );
    }
}
