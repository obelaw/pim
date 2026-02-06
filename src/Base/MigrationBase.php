<?php

namespace Obelaw\Pim\Base;

use Obelaw\Stack\Bases\MigrationBase as StackMigrationBase;

abstract class MigrationBase extends StackMigrationBase
{
    /**
     * Table postfix.
     *
     * @var string|null $module
     */
    protected ?string $module = 'pim_';
}
