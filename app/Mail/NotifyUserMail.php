<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyUserMail extends Mailable
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
            subject: 'Thank you for contacting GFiveTech!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notify',
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
