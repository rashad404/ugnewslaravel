<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $dates = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCatNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }

    public function getChannelNameAttribute()
    {
        return $this->channel ? $this->channel->name : null;
    }
}
