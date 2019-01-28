<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use App\Models\QuestradeCredential;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('snakeCaseKeys', function () {
            return $this->keyBy(function ($value, $key) {
                return snake_case($key);
            });
        });

    }
}
