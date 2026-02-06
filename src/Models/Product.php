<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Pim\Base\ModelBase;
use Obelaw\Pim\Models\AttributeValue;
use Obelaw\Pim\Traits\HasMedia;

class Product extends ModelBase
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'sku',
        'name',
        'product_type',
        'description',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            $this->prefix . 'category_product',
            'product_id',
            'category_id'
        )->withTimestamps();
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            $this->prefix . 'product_attribute_values',
            'product_id',
            'attribute_value_id'
        )->withTimestamps();
    }
}
