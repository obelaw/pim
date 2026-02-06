<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Obelaw\Pim\Base\ModelBase;

class Price extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'price_list_id',
        'price',
        'special_price',
    ];

    protected $casts = [
        'price' => 'decimal:4',
        'special_price' => 'decimal:4',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
