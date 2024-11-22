<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'senders_id',
        'receivers_id',
        'seen',
        'message',
        'ref'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seen' => 'bool',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
