<?php

namespace Obelaw\Pim\Tests;

use Obelaw\Pim\Providers\ObelawPIMServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            ObelawPIMServiceProvider::class,
        ];
    }
}
