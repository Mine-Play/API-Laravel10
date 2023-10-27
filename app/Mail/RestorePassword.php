<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestorePassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $pin;
    /**
     * Create a new message instance.
     */
    public function __construct($pin)
    {
        $this->pin=$pin;
        $this->afterCommit();
    }

    public function build()
    {
    return $this
        ->subject("Смена пароля")
        ->markdown('emails.restore');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Смена пароля',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.restore',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
