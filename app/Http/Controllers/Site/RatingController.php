<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Seo;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\News;

class RatingController extends Controller
{

    public function channels()
    {
        $data = Seo::general();
        $data['userId'] = Auth::id();

        $data['list'] = Channel::getTopChannels(10);

        return view('site.rating.channels', $data);
    }

    public function news()
    {
        $data = Seo::general();
        $data['userId'] = Auth::id(); 
        $data['list'] = News::getTopNews(10);

    
        return view('site.rating.news', $data);
    }

}
