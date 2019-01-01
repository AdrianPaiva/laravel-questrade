<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Cache;
use Laravel\Passport\Passport;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User'   => 'App\Policies\UserPolicy',
    ];
    
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        
        Passport::tokensExpireIn(now()->addMinutes(30));
        
        // Passport::enableImplicitGrant();
    }
}
