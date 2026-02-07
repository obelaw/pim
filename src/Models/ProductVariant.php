<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Obelaw\Pim\Base\ModelBase;
use Obelaw\Pim\Traits\HasMedia;
use Obelaw\Pim\Traits\HasPrices;
use Obelaw\Pim\Traits\HasStock;

class ProductVariant extends ModelBase
{
    use HasFactory;
    use HasMedia;
    use HasPrices;
    use HasStock;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'stock',
        'uom_id',
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

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    public function product(): BelongsTo

    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany

    {
        return $this->belongsToMany(
            AttributeValue::class,
            $this->prefix . 'pim_product_variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        )->withTimestamps();
    }
}
