<?php

namespace Obelaw\Pim\Facades;

use Illuminate\Support\Facades\Facade;

class PIM extends Facade
{
    protected static function getFacadeAccessor()

    {
        return 'pbelaw.stack.pim';
    }
}
