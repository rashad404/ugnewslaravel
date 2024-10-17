<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';
    public $timestamps = false;

    protected $guarded = [];

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getTopChannels($limit = 10)
    {
        return self::where('status', 1)
                   ->orderBy('subscribers', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public static function getPopularChannels($limit = 10)
    {
        return self::where('status', 1)
                   ->orderBy('view', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public static function searchChannels($text, $limit = 10)
    {
        return self::where('status', 1)
                   ->where(function ($query) use ($text) {
                       $query->where('name', 'like', "%{$text}%")
                             ->orWhere('text', 'like', "%{$text}%");
                   })
                   ->orderBy('id', 'desc')
                   ->limit($limit)
                   ->get(['id', 'name', 'name_url', 'image', 'subscribers']);
    }
}