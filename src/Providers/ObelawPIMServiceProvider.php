<?php

namespace Obelaw\Pim\Providers;

use Illuminate\Support\ServiceProvider;
use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Managers\PimStockManager;
use Obelaw\Pim\Services\PIMService;
use Illuminate\Foundation\Console\AboutCommand;

class ObelawPIMServiceProvider extends ServiceProvider
{

    public function register()

    {
        $this->app->singleton('pbelaw.stack.pim', PIMService::class);

        // Bind Stock Manager based on Config
        $this->app->bind(StockManagerInterface::class, function ($app) {
            $managerClass = config('obelaw.pim.stock_manager', PimStockManager::class);
            return $app->make($managerClass);
        });
    }

    public function boot()

    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            AboutCommand::add('Obelaw Stack', fn () => ['Obelaw PIM' => '0.1.0']);
        }
    }
}
