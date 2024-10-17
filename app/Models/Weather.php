<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Example for getting weather by city slug
    public static function getWeatherBySlug($slug)
    {
        return self::where('slug', $slug)->orderBy('created_at', 'desc')->first();
    }

    public static function getAllWeather()
    {
        return self::orderBy('created_at', 'desc')->get();
    }
}
