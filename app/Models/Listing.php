<?php

namespace App\Models;


use App\Models\Traits\ListingRelationsTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ListingQueryScopeFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory, HasUuids, ListingQueryScopeFilter, ListingRelationsTrait;
    protected $fillable = [
        'title',
        'user_id',
        'property_status',
        'property_type',
        'location',
        'price',
        'description',
        'ref'
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
        'user_id',
    ];
}
