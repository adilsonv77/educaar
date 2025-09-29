<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class ContaCriadaEmail extends Mailable
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
            subject: 'Conta criada no EducaAR',
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
