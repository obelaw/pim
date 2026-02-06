<?php

namespace Obelaw\Pim\Data;

class ProductDTO
{
    public function __construct(
        public string $sku,
        public string $name,
        public string $productType,
        public ?string $description = null,
        public ?float $price = null,
        public ?float $specialPrice = null,
        public ?string $specialPriceFrom = null,
        public ?string $specialPriceTo = null,
        public bool $isActive = true,
        public array $attributes = [],
        public array $categories = [],
        /** @var MediaDTO[] */
        public array $media = [],
        public array $variants = [],
    ) {}

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'product_type' => $this->productType,
            'description' => $this->description,
            'price' => $this->price,
            'special_price' => $this->specialPrice,
            'special_price_from' => $this->specialPriceFrom,
            'special_price_to' => $this->specialPriceTo,
            'is_active' => $this->isActive,
            'attributes' => $this->attributes,
            'categories' => $this->categories,
            'media' => array_map(fn($item) => $item->toArray(), $this->media),
            'variants' => array_map(fn($variant) => $variant->toArray(), $this->variants),
        ];
    }
}
