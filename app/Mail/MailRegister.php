<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;

class MailRegister extends Mailable
{
    use Queueable, SerializesModels;
    protected $first_name, $last_name;
    
    /**
     * Create a new message instance.
     */
    public function __construct($first_name, $last_name)
    {
        //
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks To Register',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.customers.register',
            with: [
                'url' => env('APP_URL'),
                'customer_name' => $this->first_name. ' ' . $this->last_name,
            ]
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
