<?php

namespace Obelaw\Pim\Repositories;

use Illuminate\Support\Facades\DB;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Data\ProductDTO;
use Exception;

class ProductRepository
{
    public function create(ProductDTO $data): Product

    {
        try {
            return DB::transaction(function () use ($data) {
                $product = Product::create([
                    'sku' => $data->sku,
                    'name' => $data->name,
                    'product_type' => $data->productType,
                    'description' => $data->description,
                    'price' => $data->price,
                    'special_price' => $data->specialPrice,
                    'special_price_from' => $data->specialPriceFrom,
                    'special_price_to' => $data->specialPriceTo,
                    'is_active' => $data->isActive,
                    'uom_id' => $data->uomId,
                ]);

                if ($product->product_type === 'simple' && !empty($data->attributes)) {
                    $product->attributeValues()->sync($data->attributes);
                }

                if (!empty($data->categories)) {
                    $product->categories()->sync($data->categories);
                }

                if (!empty($data->media)) {
                    foreach ($data->media as $mediaDTO) {
                        $product->media()->create($mediaDTO->toArray());
                    }
                }

                if ($product->product_type === 'configurable' && !empty($data->variants)) {
                    foreach ($data->variants as $variantData) {
                        $variant = $product->variants()->create([
                            'sku' => $variantData->sku,
                            'price' => $variantData->price,
                            'special_price' => $variantData->specialPrice,
                            'special_price_from' => $variantData->specialPriceFrom,
                            'special_price_to' => $variantData->specialPriceTo,
                            'stock' => $variantData->stock,
                            'uom_id' => $variantData->uomId,
                        ]);

                        if (!empty($variantData->attributes)) {
                            $variant->attributeValues()->sync($variantData->attributes);
                        }
                    }
                }

                return $product;
            });
        } catch (Exception $e) {
            throw new Exception('Failed to create product: ' . $e->getMessage());
        }
    }

    public function find(int $id): ?Product
    {
        return Product::with([
            'variants.attributeValues.attribute',
            'variants.uom',
            'attributeValues.attribute',
            'categories',
            'media',
            'uom',
        ])->find($id);
    }
}
