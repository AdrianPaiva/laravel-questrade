<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MagicLinkMail extends Mailable implements ShouldQueue
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
     * @var \App\Models\Profile
     */
    public $profile;
    
    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param $url
     */
    public function __construct(User $user, $url)
    {
        $this->user = $user;
        $this->profile = $user->profile;
        $this->url = $url;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.magic_link')->subject('Magic Link');
    }
}
