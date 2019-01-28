<?php

namespace App\Providers;

use App\Models\QuestradeCredential;
use Illuminate\Support\ServiceProvider;

class QuestradeCredentialServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(QuestradeCredential::class, function ($app) {
        //     return QuestradeCredential::where('user_id', auth()->user()->id)->latest('updated_at')->first();
        // });
    }
}
