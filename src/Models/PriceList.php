<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Pim\Base\ModelBase;

class PriceList extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'name',
        'currency_code',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Scope to find currently active price lists based on date.
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();
        
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
    }
}
