<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Seo;
use App\Models\Weather;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    // General weather page for all cities
    public function index()
    {

        $data = Seo::weather();

        $data['currentWeather'] = Weather::getWeatherBySlug('baki'); // Default city as Baku
        $data['all_weather'] = Weather::getAllWeather(); // All cities' weather

        return view('site.weather.index', $data);
    }

    // City-specific weather page
    public function city($slug)
    {
        $weather = Weather::getWeatherBySlug($slug);
        $allWeather = Weather::getAllWeather(); // All cities' weather for table display

        $data = Seo::weather_city(__($weather->city_name));
        $data['weather'] = Weather::getWeatherBySlug($slug);
        $data['all_weather'] = Weather::getAllWeather(); // All cities' weather

        return view('site.weather.city', $data);
    }
}
