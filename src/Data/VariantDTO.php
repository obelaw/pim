<?php

namespace Obelaw\Pim\Data;

class VariantDTO
{
    public function __construct(
        public string $sku,
        public float $price,
        public int $stock = 0,
        public ?float $specialPrice = null,
        public ?string $specialPriceFrom = null,
        public ?string $specialPriceTo = null,
        public array $attributes = [],
    ) {}

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'special_price' => $this->specialPrice,
            'special_price_from' => $this->specialPriceFrom,
            'special_price_to' => $this->specialPriceTo,
            'attributes' => $this->attributes,
        ];
    }
}
