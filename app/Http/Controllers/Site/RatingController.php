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
        $data['userId'] = Auth::id(); // Use Laravel's Auth system for user ID

        $channels = Channel::getTopChannels();
        $pagination = new LengthAwarePaginator(
            $channels,
            Channel::count(),
            10
        );

        $data['list'] = $pagination->items();
        $data['pagination'] = $pagination;

        return view('site.rating.channels', $data);
    }

    public function news()
    {
        $data = Seo::general();
        $data['userId'] = Auth::id(); 
    
        // Paginate the news with 10 items per page
        $page = request()->get('page', 1); // Current page
        $perPage = 10; // Items per page
        $newsList = News::getTopNews(); // Fetch the top news
    
        // Create a LengthAwarePaginator instance
        $pagination = new LengthAwarePaginator(
            $newsList->forPage($page, $perPage), // Items for the current page
            $newsList->count(), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Preserve query parameters
        );
    
        $data['list'] = $pagination;
        return view('site.rating.news', $data);
    }

}
