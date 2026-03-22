<?php

namespace App\Mail;

use App\Models\InventoryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InventoryAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $alertType;

    /**
     * Create a new message instance.
     */
    public function __construct(InventoryItem $item, string $alertType)
    {
        $this->item = $item;
        $this->alertType = $alertType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'low_stock' => 'Low Stock Alert - ' . $this->item->name,
            'out_of_stock' => 'Out of Stock Alert - ' . $this->item->name,
            'expiring_soon' => 'Expiring Soon Alert - ' . $this->item->name,
            'expired' => 'Expired Item Alert - ' . $this->item->name,
        ];

        return new Envelope(
            subject: $subjects[$this->alertType] ?? 'Inventory Alert - ' . $this->item->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.inventory.alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
