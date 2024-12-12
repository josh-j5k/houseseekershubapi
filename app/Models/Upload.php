<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'description'
    ];
    protected $hidden = [
        'id',
        "uploadable_id",
        "uploadable_type",
        "created_at",
        "updated_at"
    ];
    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
