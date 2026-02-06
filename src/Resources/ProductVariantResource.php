<?php

namespace Obelaw\Pim\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'special_price' => $this->special_price,
            'special_price_from' => $this->special_price_from,
            'special_price_to' => $this->special_price_to,
            'stock' => $this->stock,
            'attributes' => $this->attributes_map,
        ];
    }
}
