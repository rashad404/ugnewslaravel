<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'country_id',
        'language_id',
        'user_id',
    ];

    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}