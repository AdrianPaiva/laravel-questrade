<?php

namespace App\Mail;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    /**
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * @var \App\Models\Domain
     */
    public $domain;
    
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
     * @param \App\Models\Domain $domain
     * @param $url
     */
    public function __construct(User $user, Domain $domain, $url)
    {
        $this->user = $user;
        $this->profile = $user->profile;
        $this->domain = $domain;
        $this->url = $url;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_welcome')->subject("Welcome to {$this->domain->name}");
    }
}
