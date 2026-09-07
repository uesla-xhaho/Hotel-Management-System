<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;
    public $passwordi;
    public $username;

    /**
     * Create a new message instance.
     */
    public function __construct($password,$username)
    {
        //
        $this->passwordi=$password;
        $this->username=$username;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $password =  $this->passwordi;
        return new Content(
            view: 'email',
            with: [
                'password' => $this->passwordi,
            ],
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
