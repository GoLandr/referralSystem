<?php

namespace App\Providers;

use App\Managers\ReferralKeyManager;
use Illuminate\Support\ServiceProvider;

class MainProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReferralKeyManager::class, function ($app) {
            return new ReferralKeyManager($app['db']);
        });
    }
}
