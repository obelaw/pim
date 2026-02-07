<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Obelaw\Pim\Base\ModelBase;

class ProductStock extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'stockable_id',
        'stockable_type',
        'physical_stock',
        'reserved_stock',
        'available_stock',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(fn($stock) => $stock->available_stock = $stock->physical_stock - $stock->reserved_stock);
    }

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }
}
