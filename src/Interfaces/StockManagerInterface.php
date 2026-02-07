<?php

namespace Obelaw\Pim\Interfaces;

use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductVariant;

interface StockManagerInterface
{
    public function getPhysical(Product|ProductVariant $entity): float;

    public function getReserved(Product|ProductVariant $entity): float;

    public function getAvailable(Product|ProductVariant $entity): float;

    /**
     * Reserve items (Decrease available, Increase reserved)
     */
    public function reserve(Product|ProductVariant $entity, float $quantity): void;

    /**
     * Release reserved items (Increase available, Decrease reserved)
     */
    public function release(Product|ProductVariant $entity, float $quantity): void;

    /**
     * Fulfill items (Decrease Physical AND Reserved)
     */
    public function fulfill(Product|ProductVariant $entity, float $quantity): void;
}
