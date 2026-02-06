<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Obelaw\Pim\Base\ModelBase;
use Obelaw\Pim\Traits\HasMedia;

class ProductVariant extends ModelBase
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'stock',
    ];

    protected $casts = [
        'special_price_from' => 'date',
        'special_price_to' => 'date',
    ];

    protected $appends = ['attributes_map'];

    public function getAttributesMapAttribute()
    {
        return $this->attributeValues->mapWithKeys(function ($item) {
            return [$item->attribute->name => $item->value];
        });
    }

    public function product(): BelongsTo

    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany

    {
        return $this->belongsToMany(
            AttributeValue::class,
            $this->prefix . 'product_variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        )->withTimestamps();
    }
}
