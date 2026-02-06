<?php

namespace Obelaw\Pim\Base;

use Obelaw\Stack\Bases\ModelBase as StackBaseModel;

class ModelBase extends StackBaseModel
{
    /**
     * Optional module name for table prefixing.
     *
     * @var string|null $module
     */
    protected ?string $module = 'pim_';
}
