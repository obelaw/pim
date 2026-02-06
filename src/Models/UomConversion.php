<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Obelaw\Pim\Base\ModelBase;

class UomConversion extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'from_uom_id',
        'to_uom_id',
        'conversion_factor',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
    ];

    public function fromUom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'from_uom_id');
    }

    public function toUom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'to_uom_id');
    }
}
