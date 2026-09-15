<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerPasswordReset extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $resetLink;

    /**
     * Create a new message instance.
     */
    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Password Reset Request - Ajhuie Book Store & Photostat Services')
                    ->view('emails.customer-password-reset')
                    ->with(['resetLink' => $this->resetLink]);
    }
}
