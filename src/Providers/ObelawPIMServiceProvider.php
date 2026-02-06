<?php

namespace Obelaw\Pim\Providers;

use Illuminate\Support\ServiceProvider;
use Obelaw\Pim\Services\PIMService;

class ObelawPIMServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pbelaw.stack.pim', PIMService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
