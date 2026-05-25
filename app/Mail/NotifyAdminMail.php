<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message - GFiveTech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin',
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
