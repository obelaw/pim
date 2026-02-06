<?php

namespace Obelaw\Pim\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'type' => $this->product_type,
            'description' => $this->description,
            'price' => $this->price,
            'special_price' => $this->special_price,
            'special_price_from' => $this->special_price_from,
            'special_price_to' => $this->special_price_to,
            'is_active' => $this->is_active,
            'attributes' => $this->attributes_map,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
