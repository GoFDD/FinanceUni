<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PendingUser;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public PendingUser $pendingUser;
    public string $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
        // Monta a URL de confirmação usando o token
        $this->verificationUrl = config('app.frontend_url') . "/verify/{$pendingUser->verification_token}";

    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('Confirme seu e-mail - FinanceUni')
            ->view('emails.confirm-email')
            ->with([
                'userName' => $this->pendingUser->name,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }
}
