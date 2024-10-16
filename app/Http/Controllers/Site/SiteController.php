<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\NamazTimes;
use App\Models\Channel;
use App\Models\News;
use App\Models\City;

class SiteController extends Controller
{
    public function index()
    {
        $usdRate = Currency::getUsdRate();
        $bakuWeatherInfo = $this->getWeatherInfo(); // You'll need to implement this method
        $todayNamaz = NamazTimes::getTodayTimes();
        $region = session('region', 16); // Default to 16 if not set

        $channelList = Channel::getTopChannels(10); // Adjust the number as needed
        $newsList = News::where("status", 1)->paginate(15);
        
        $cityList1 = City::getMainCities();
        $cityList2 = City::getSecondaryCities();

        return view('site.index', compact(
            'usdRate',
            'bakuWeatherInfo',
            'todayNamaz',
            'region',
            'channelList',
            'newsList',
            'cityList1',
            'cityList2'
        ));
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function submitContact(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Process the contact form submission
        // You might want to save this to the database or send an email

        return redirect()->route('site.contact')->with('success', 'Your message has been sent successfully!');
    }

    private function getWeatherInfo()
    {
        // Implement weather API call here
        // This is just a placeholder
        return '25°C';
    }
}