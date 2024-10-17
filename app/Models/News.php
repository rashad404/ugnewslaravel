<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class News extends Model
{
    protected $table = 'news';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getLatestNews()
    {
        $region = Cookie::get('set_region', config('app.default_country'));
        return self::where('status', 1)
                   ->where('country_id', $region)
                   ->where('publish_time', '<=', now())
                   ->orderBy('publish_time', 'desc');
    }

    public static function getSimilarNews($id, $limit = 6)
    {
        $news = self::find($id);
        $result =  self::whereRaw("MATCH(title, text) AGAINST(? IN NATURAL LANGUAGE MODE)", [$news->title])
                   ->where('id', '!=', $id)
                   ->where('status', 1)
                   ->where('country_id', Cookie::get('country', config('app.default_country')))
                   ->orderByRaw('MATCH(title, text) AGAINST(? IN NATURAL LANGUAGE MODE) DESC', [$news->title])
                   ->limit($limit)
                   ->get();
        return $result;
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByChannel($query, $channelId)
    {
        return $query->where('channel_id', $channelId);
    }

    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereRaw("FIND_IN_SET(?, tags)", [$tag]);
    }

    public static function getCatName($id)
    {
        $category = Category::find($id);
        return $category ? $category->name : '';
    }

    /**
     * Get the top news based on views.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopNews($limit = 10)
    {
        return self::where('status', 1)  // Ensure only active news
                   ->orderBy('view', 'desc')  // Order by view count in descending order
                   ->limit($limit)  // Limit the number of results
                   ->get(['id', 'title', 'slug', 'image', 'view']);  // Fetch specific columns
    }
}