<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * @var
     */
    public $code;
    
    /**
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * @var \App\Models\Profile
     */
    public $profile;
    
    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param $code
     */
    public function __construct(User $user, $code)
    {
        $this->code = $code;
        $this->user = $user;
        $this->profile = $user->profile;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verification_code')->subject('Verification Code');
    }
}
