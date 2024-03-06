<?php

namespace App\Emails;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user , $otp;
    public function __construct($user,$otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('email.forget_password');
    }
}
