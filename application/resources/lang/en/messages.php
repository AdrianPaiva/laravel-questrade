<?php

return [
    'auth' => [
        'set-password' => [
            'already-set' => 'Password has Already been set for given User.',
            'success'     => 'Password Set Successfully.',
        ],
        
        'verify-code' => [
            'sent'    => "Verification Code Sent.",
            'failed'  => 'Verification Code Does not match.',
            'sms'     => "Your Verification Code is - :code.",
        ],
        'login'       => [
            'failed' => 'Password is Incorrect.',
        ],
        'magic-link'  => [
            'sent' => 'Magic Link Sent to your Email ID - :email.',
            'used' => 'This Link has Already Expired.',
        ],
        
        'logout' => 'You have Logged Out Successfully.',
        
        'forgot-password' => [
            'reset-link-sent' => "Reset Password Link have been Sent to your Email.",
        ],
    ],
];
