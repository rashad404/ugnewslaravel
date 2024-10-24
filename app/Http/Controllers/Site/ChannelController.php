<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Seo;
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
        $data = Seo::create_channel();
        return view('site.channel.create', $data);
    }

    public function show($url)
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
        $data['metaImg'] = $data['item']->image;
        $data['metaTitle'] = $data['item']->name;
        $data['metaKeywords'] = $data['item']->name;
        $data['metaDescription'] = $data['item']->name;

        // Pagination setup for related news
        $data['newsList'] = News::where('channel_id', $data['item']->id)->orderBy('id', 'desc')
                            ->paginate(24);

        // Set region from cookie or default
        $data['region'] = Cookie::get('set_region', config('app.default_country'));

        // Load country list
        $data['countryList'] = Sms::getCountryList();
        $data['subscribe_check'] = Auth::check() ? Auth::user()->isSubscribedTo($data['item']) : false;

        // Render the view with the data
        return view('site.channel.show', $data);
    }
}
