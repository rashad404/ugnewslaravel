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

        $newsList = News::where('status', 1)
            ->where('publish_time', '<=', time())
            ->byTag($tag)
            ->orderBy('publish_time', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $cat_name = $tag;
        return view('site.news.tag', compact(
            'newsList',
            'cat_name',
        ));
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

        // Get a random ad
        $ad = Ad::inRandomOrder()->first();

        return view('site.news.show', compact('item', 'channel_info', 'subscribe_check', 'like_check', 'dislike_check', 'similar_news', 'ad'));
    }

    private function checkSubscription($channel_id)
    {
        if (Auth::check()) {
            return Auth::user()->subscriptions()->where('channel_id', $channel_id)->exists();
        }
        return false;
    }

    private function checkLike($news_id)
    {
        if (Auth::check()) {
            return Auth::user()->likes()->where('news_id', $news_id)->where('liked', 1)->exists();
        }
        return false;
    }

    private function checkDislike($news_id)
    {
        if (Auth::check()) {
            return Auth::user()->likes()->where('news_id', $news_id)->where('disliked', 1)->exists();
        }
        return false;
    }
}
