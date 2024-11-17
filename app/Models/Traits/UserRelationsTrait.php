<?php

namespace App\Models\Traits;

use App\Models\Listing;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationsTrait
{
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
}