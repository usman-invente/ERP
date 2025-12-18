<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromAddress;
    public $fromName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fromAddress, $fromName)
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }

    public function build()
    {
        return $this->from($this->fromAddress, $this->fromName)
                    ->subject('Test Email')
                    ->view('emails.test'); // Erstelle eine Blade-View in resources/views/emails/test.blade.php
    }
    
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Test Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.test',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
