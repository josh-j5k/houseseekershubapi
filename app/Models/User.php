<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;



use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\UserRelationsTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, UserRelationsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'ref',
        "email_verified_at",
        'provider_id',
        'provider'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'id',
        'remember_token',
        'provider_id',
        'provider',
        'avatar',
        "created_at",
        "updated_at"
    ];
    protected $appends = [
        'picture'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return Attribute
     */
    public function picture(): Attribute
    {
        $avatar = $this->avatar && $this->provider ? $this->avatar : ($this->avatar ? config('app.url') . "/$this->avatar" : null);
        return Attribute::make(
            get: fn() => $avatar
        );
    }

}
