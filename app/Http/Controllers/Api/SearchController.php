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
        $countryId = $request->input('countryId');


        $channels = Channel::searchChannels($query, 5);

        $news = News::where('status', 1)
            ->where('country_id', $countryId)
            ->where('publish_time', '<=', time())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('text', 'like', "%{$query}%");
            })
            ->orderBy('publish_time', 'DESC')->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        return response()->json([
            'channels' => $channels,
            'news' => $news
        ])->withCookie(cookie()->forget('country'));
    }
}