<?php

namespace Obelaw\Pim\Services;

use Obelaw\Pim\Models\PriceList;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductVariant;

class PriceResolverService
{
    /**
     * Resolve the price for a product/variant from a specific price list
     * IF that price list is currently valid.
     */
    public function resolve(Product|ProductVariant $entity, int $priceListId): ?float
    {
        // 1. Validate Price List Validity
        // We only fetch if the pricelist itself is active and within date range
        $isValidList = PriceList::active()->where('id', $priceListId)->exists();

        if (!$isValidList) {
            return null; // Pricelist is expired or inactive
        }

        // 2. Try to fetch price directly from the entity
        $price = $this->fetchPriceFromEntity($entity, $priceListId);

        if ($price !== null) {
            return $price;
        }

        // 3. Fallback: If it is a Variant and no price found, try the parent Product
        if ($entity instanceof ProductVariant) {
            return $this->fetchPriceFromEntity($entity->product, $priceListId);
        }

        return null;
    }

    private function fetchPriceFromEntity($entity, int $priceListId): ?float
    {
        $priceRecord = $entity->prices()
            ->where('price_list_id', $priceListId)
            ->first();

        if (!$priceRecord) {
            return null;
        }

        // Return special price if it exists, otherwise regular price
        return $priceRecord->special_price ?? $priceRecord->price;
    }
}
