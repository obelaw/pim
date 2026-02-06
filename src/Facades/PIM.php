<?php

namespace Obelaw\Pim\Facades;

use Illuminate\Support\Facades\Facade;

class PIM extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pbelaw.stack.pim';
    }
}
