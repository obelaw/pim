<?php

namespace Obelaw\Pim\Tests;

use Obelaw\Pim\Providers\ObelawPIMServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ObelawPIMServiceProvider::class,
        ];
    }
}
