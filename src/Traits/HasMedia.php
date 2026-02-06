<?php

namespace Obelaw\Pim\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Obelaw\Pim\Models\Media;

trait HasMedia
{
    /**
     * Get all media assets for the model.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the primary image for the model.
     */
    public function primaryImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->ofMany([
                'is_primary' => 'max',
                'id' => 'max',
            ], function ($query) {
                $query->where('file_type', 'image');
            });
    }

    /**
     * Get only video assets.
     */
    public function videos()
    {
        return $this->media()->where('file_type', 'video');
    }

    /**
     * Get only manual/document assets.
     */
    public function manuals()
    {
        return $this->media()->where('file_type', 'manual');
    }
}
