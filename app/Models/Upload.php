<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'description'
    ];
    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
