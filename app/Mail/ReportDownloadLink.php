<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportDownloadLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $patientName  Greeting name
     * @param  string  $reportTitle  Report / test display name
     * @param  string  $downloadUrl  Absolute URL to PDF download
     * @param  string  $labName  Lab display name
     * @param  string|null  $labPhone
     * @param  string|null  $labEmail
     * @param  string|null  $labAddress
     * @param  string|null  $brandTagline  Short line under lab name in footer
     */
    public function __construct(
        public string $patientName,
        public string $reportTitle,
        public string $downloadUrl,
        public string $labName,
        public ?string $labPhone = null,
        public ?string $labEmail = null,
        public ?string $labAddress = null,
        public ?string $brandTagline = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your report download link – ' . $this->labName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.download-link',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
