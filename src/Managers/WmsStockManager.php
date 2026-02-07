<?php

namespace Obelaw\Pim\Managers;

use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductVariant;

class WmsStockManager implements StockManagerInterface
{
    public function getPhysical(Product|ProductVariant $entity): float
    {
        return 9999.0; // Stub
    }

    public function getReserved(Product|ProductVariant $entity): float
    {
        return 0.0; // Stub
    }

    public function getAvailable(Product|ProductVariant $entity): float
    {
        return 9999.0; // Stub
    }

    public function reserve(Product|ProductVariant $entity, float $quantity): void
    {
        // WMS Logic
    }

    public function release(Product|ProductVariant $entity, float $quantity): void
    {
        // WMS Logic
    }

    public function fulfill(Product|ProductVariant $entity, float $quantity): void
    {
        // WMS Logic
    }
}
