<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Format;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Channel;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function tag($tag)
    {
        $tag = Format::deUrlText($tag);

        $data['newsList'] = News::where('status', 1)
            ->where('publish_time', '<=', time())
            ->byTag($tag)
            ->orderBy('publish_time', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $data['cat_name'] = $tag;

        $data['metaTitle'] = "{$tag} Xəbərləri, {$tag} xeberleri";
        $data['metaKeywords'] = "{$tag} Xəbərləri, {$tag} xeberleri";
        $data['metaDescription'] = "{$tag} Xəbərləri, {$tag} xeberleri";


        return view('site.news.tag', $data);
    }

    public function show($channel, $slug)
    {
        $fullSlug = $channel . '/' . $slug;
        $item = News::where('slug', $fullSlug)->firstOrFail();
        
        // Increment view count
        // $item->increment('view');

        $channel_info = Channel::find($item->channel_id);
        $subscribe_check = $this->checkSubscription($item->channel_id);
        $like_check = $this->checkLike($item->id);
        $dislike_check = $this->checkDislike($item->id);

        // Get similar news
        $similar_news = News::getSimilarNews($item->id);

        $metaTitle = $item->title;
        $metaKeywords = $item->title;
        $metaDescription = $item->title;

        // Get a random ad
        $ad = Ad::inRandomOrder()->first();
        return view('site.news.show', compact(
            'item', 'channel_info', 'subscribe_check', 'like_check', 
            'dislike_check', 'similar_news', 'ad',
            'metaTitle', 'metaKeywords', 'metaDescription'
        ));
    }

    private function checkSubscription($channel_id)
    {
        if (Auth::check()) {
            return true;
            // return Auth::user()->subscriptions()->where('channel_id', $channel_id)->exists();
        }
        return false;
    }

    private function checkLike($news_id)
    {
        if (Auth::check()) {
            return true;
            // return Auth::user()->likes()->where('news_id', $news_id)->where('liked', 1)->exists();
        }
        return false;
    }

    private function checkDislike($news_id)
    {
        if (Auth::check()) {
            return true;
            // return Auth::user()->likes()->where('news_id', $news_id)->where('disliked', 1)->exists();
        }
        return false;
    }
}
