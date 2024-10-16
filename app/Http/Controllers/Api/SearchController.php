<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $country = Cookie::get('country', config('app.default_country'));

        $channels = Channel::searchChannels($query, 5);

        $news = News::where('status', 1)
            ->where('country_id', $country)
            ->where('publish_time', '<=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('text', 'like', "%{$query}%");
            })
            ->orderBy('publish_time', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'channels' => $channels,
            'news' => $news
        ]);
    }
}