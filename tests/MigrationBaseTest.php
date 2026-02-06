<?php

namespace Obelaw\Pim\Tests;

use Obelaw\Pim\Base\MigrationBase;

class MigrationBaseTest extends MigrationBase
{
    public function getPrefix()
    {
        return $this->prefix;
    }
}