<?php

namespace App\Mail;

use App\Models\HomepageContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HomepageContactSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HomepageContactMessage $contactMessage
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject
            ? '[JUBAF] '.$this->contactMessage->subject
            : '[JUBAF] Nova mensagem de contato';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.homepage-contact-submitted',
        );
    }
}
