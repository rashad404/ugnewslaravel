<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsReaction extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'news_id',
        'likes',
        'dislikes',
        'time'
    ];

    protected $casts = [
        'likes' => 'boolean',
        'dislikes' => 'boolean',
        'time' => 'integer'
    ];

    // Add a mutator to ensure likes and dislikes are mutually exclusive
    public function setLikesAttribute($value)
    {
        $this->attributes['likes'] = $value;
        if ($value) {
            $this->attributes['dislikes'] = false;
        }
    }

    public function setDislikesAttribute($value)
    {
        $this->attributes['dislikes'] = $value;
        if ($value) {
            $this->attributes['likes'] = false;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}