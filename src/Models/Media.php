<?php

namespace Obelaw\Pim\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Obelaw\Pim\Base\ModelBase;

class Media extends ModelBase
{
    use HasFactory;

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */

    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('file_type', 'image');
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('file_type', 'video');
    }

    public function scopeDocuments(Builder $query): Builder
    {
        return $query->where('file_type', 'document');
    }

    public function scopeManuals(Builder $query): Builder
    {
        return $query->where('file_type', 'manual');
    }
}
