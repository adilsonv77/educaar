<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private $password, private $user)
    {
        //
    }

    public function envelope() {
        return new Envelope(
            subject: 'Assunto Teste',
            from: env('MAIL_FROM_ADDRESS'),
        );
    }

    public function content() {
        return new Content(
            view: 'mail.passwordregister',
            with: ['password' => $this->password, 'user' => $this->user]
        );
    }
}
