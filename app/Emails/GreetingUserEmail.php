<?php

namespace App\Emails;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GreetingUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public function __construct($user)
    {
        $this->user = $user ;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('email.greeting_user');
    }
}

