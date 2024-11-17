<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Upload;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ListingRelationsTrait
{
    public function uploads(): MorphMany
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
}