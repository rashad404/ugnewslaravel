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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the top news based on views with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getTopChannels($perPage = 10)
    {
        return self::where('status', 1)
            ->orderBy('view', 'desc')
            ->paginate($perPage, ['id', 'name', 'name_url', 'image', 'subscribers', 'view']);
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

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscribers')
                    ->withTimestamps();
    }


    // public function subscribers()
    // {
    //     return $this->hasMany(Subscriber::class);
    // }
}