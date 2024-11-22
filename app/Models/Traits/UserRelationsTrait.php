<?php

namespace App\Models\Traits;

use App\Models\Listing;
use App\Models\Bookmark;
use App\Models\Message;
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
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}