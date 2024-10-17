<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Helpers\Sms;

class ChannelController extends Controller
{

    public function create()
    {
        // Render the view with the data
        return view('site.channel.create');
    }

    public function inner($url)
    {
        // Fetch logged-in user ID
        $data['userId'] = Auth::id(); 

        // Get the channel by URL (decoded)
        $data['item'] = Channel::where('name_url', urldecode($url))->first();

        // If the channel doesn't exist, return an error or 404
        if (!$data['item']) {
            return abort(404, 'Wrong Channel');
        }

        // Set meta data
        $data['meta_img'] = $data['item']->image;
        $data['title'] = $data['item']->name;
        $data['keywords'] = $data['item']->name;
        $data['description'] = $data['item']->name;

        // Pagination setup for related news
        $data['newsList'] = News::where('channel_id', $data['item']->id)
                            ->paginate(24);

        // Set region from cookie or default
        $data['region'] = Cookie::get('set_region', config('app.default_country'));

        // Load country list
        $data['countryList'] = Sms::getCountryList();
        $data['subscribe_check'] = 1;

        // Render the view with the data
        return view('site.channel.show', $data);
    }
}
