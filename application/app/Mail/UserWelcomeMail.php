<?php

namespace App\Mail;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    /**
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * @var string
     */
    public $url;

    
    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param $url
     */
    public function __construct(User $user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_welcome')->subject("Welcome to ");
    }
}
