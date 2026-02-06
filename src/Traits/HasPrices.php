<?php

namespace Obelaw\Pim\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Obelaw\Pim\Models\Price;

trait HasPrices
{
    /**
     * Get all prices associated with this model.
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
