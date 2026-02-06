<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Pim\Base\ModelBase;

class Attribute extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
