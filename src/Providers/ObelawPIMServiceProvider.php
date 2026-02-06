<?php

namespace Obelaw\Pim\Providers;

use Illuminate\Support\ServiceProvider;
use Obelaw\Pim\Services\PIMService;
use Illuminate\Foundation\Console\AboutCommand;

class ObelawPIMServiceProvider extends ServiceProvider
{

    public function register()

    {
        $this->app->singleton('pbelaw.stack.pim', PIMService::class);
    }

    public function boot()

    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            AboutCommand::add('Obelaw Stack', fn () => ['Obelaw PIM' => '0.1.0']);
        }
    }
}
