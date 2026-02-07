<?php

namespace Obelaw\Pim\Traits;

use Obelaw\Pim\Interfaces\StockManagerInterface;

trait HasStock
{
    protected function stockManager(): StockManagerInterface
    {
        return app(StockManagerInterface::class);
    }

    /**
     * Get the stock manager instance.
     */
    public function inventory(): StockManagerInterface
    {
        return $this->stockManager();
    }

    public function getPhysicalStock(): float
    {
        return $this->stockManager()->getPhysical($this);
    }

    public function getReservedStock(): float
    {
        return $this->stockManager()->getReserved($this);
    }

    public function getAvailableStock(): float
    {
        return $this->stockManager()->getAvailable($this);
    }
}
