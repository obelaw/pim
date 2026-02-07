<?php

namespace Obelaw\Pim\Managers;

use Exception;
use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductStock;
use Obelaw\Pim\Models\ProductVariant;

class PimStockManager implements StockManagerInterface
{
    public function getPhysical(Product|ProductVariant $entity): float
    {
        return $this->getRecord($entity)->physical_stock ?? 0.0;
    }

    public function getReserved(Product|ProductVariant $entity): float
    {
        return $this->getRecord($entity)->reserved_stock ?? 0.0;
    }

    public function getAvailable(Product|ProductVariant $entity): float
    {
        return $this->getRecord($entity)->available_stock ?? 0.0;
    }

    public function reserve(Product|ProductVariant $entity, float $quantity): void
    {
        $stock = $this->getRecord($entity);
        
        if ($stock->available_stock < $quantity) {
            throw new Exception("Local PIM: Insufficient stock. Available: {$stock->available_stock}, Requested: {$quantity}");
        }

        $stock->reserved_stock += $quantity;
        $stock->save();
    }

    public function release(Product|ProductVariant $entity, float $quantity): void
    {
        $stock = $this->getRecord($entity);
        $stock->reserved_stock = max(0, $stock->reserved_stock - $quantity);
        $stock->save();
    }

    public function fulfill(Product|ProductVariant $entity, float $quantity): void
    {
        $stock = $this->getRecord($entity);
        
        $stock->physical_stock -= $quantity;
        $stock->reserved_stock -= $quantity;
        $stock->save();
    }

    protected function getRecord($entity)
    {
        return ProductStock::firstOrCreate(
            [
                'stockable_type' => get_class($entity),
                'stockable_id' => $entity->id,
            ],
            [
                'physical_stock' => 0,
                'reserved_stock' => 0,
                'available_stock' => 0,
            ]
        );
    }
}
