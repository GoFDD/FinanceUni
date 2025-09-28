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

    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
        $this->verificationUrl = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/') . "/verify-email/{$pendingUser->verification_token}";
    }

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