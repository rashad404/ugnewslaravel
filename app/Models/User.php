<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        'block' => 'boolean',
        'score' => 'integer',
        'balance' => 'decimal:2',
    ];

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(Channel::class, 'subscribers');
    }
    
    public function isSubscribedTo(Channel $channel)
    {
        return $this->subscribers()->where('channel_id', $channel->id)->exists();
    }
}
