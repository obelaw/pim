<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Pim\Base\ModelBase;

class UnitOfMeasure extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
    ];

    /**
     * Get conversions where this UOM is the source.
     */
    public function conversionsFrom(): HasMany
    {
        return $this->hasMany(UomConversion::class, 'from_uom_id');
    }

    /**
     * Get conversions where this UOM is the target.
     */
    public function conversionsTo(): HasMany
    {
        return $this->hasMany(UomConversion::class, 'to_uom_id');
    }
}
