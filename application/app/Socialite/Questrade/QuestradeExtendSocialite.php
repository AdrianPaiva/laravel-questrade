<?php

namespace App\Socialite\Questrade;

use SocialiteProviders\Manager\SocialiteWasCalled;

class QuestradeExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialite_was_called
     */
    public function handle(SocialiteWasCalled $socialite_was_called)
    {
        $socialite_was_called->extendSocialite(
            'questrade', __NAMESPACE__.'\Provider'
        );
    }
}
