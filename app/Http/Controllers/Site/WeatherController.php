<?php

namespace App\Http\Controllers\Site;

use App\Models\Weather;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    // General weather page for all cities
    public function index()
    {
        $currentWeather = Weather::getWeatherBySlug('baki'); // Default city as Baku
        $allWeather = Weather::getAllWeather(); // All cities' weather

        return view('site.weather.index', [
            'currentWeather' => $currentWeather,
            'all_weather' => $allWeather
        ]);
    }

    // City-specific weather page
    public function city($slug)
    {
        $weather = Weather::getWeatherBySlug($slug);
        $allWeather = Weather::getAllWeather(); // All cities' weather for table display

        return view('site.weather.city', [
            'weather' => $weather,
            'all_weather' => $allWeather
        ]);
    }
}
