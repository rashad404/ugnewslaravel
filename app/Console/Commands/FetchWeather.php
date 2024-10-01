<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Weather;
use Carbon\Carbon;

class FetchWeather extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather information using OpenWeatherMap API';

    private $cityTranslations = [
        'Baku' => 'Bakı',
        'Ganja' => 'Gəncə',
        'Şəki' => 'Şəki',
        'Lankaran' => 'Lənkəran'
    ];
    
    public function handle()
    {
        // Replace with your API key from OpenWeatherMap
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $cities = ['Baku', 'Ganja', 'Sheki', 'Lankaran']; // Add more cities as needed

        foreach ($cities as $city) {
            // Fetch weather data from OpenWeatherMap
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric'  // Get temperature in Celsius
            ]);

            if ($response->ok()) {
                $weatherData = $response->json();

                $translatedCity = $this->cityTranslations[$city] ?? $city;
                $slug = Str::slug($translatedCity); // Create a slug from the translated name

                // Insert or update weather data in the database
                Weather::updateOrCreate(
                    [
                        'slug' => $slug
                    ],
                    [
                        'city_name' => $weatherData['name'], 
                        'temp' => $weatherData['main']['temp'],
                        'feels_like' => $weatherData['main']['feels_like'],
                        'temp_min' => $weatherData['main']['temp_min'],
                        'temp_max' => $weatherData['main']['temp_max'],
                        'pressure' => $weatherData['main']['pressure'],
                        'humidity' => $weatherData['main']['humidity'],
                        'visibility' => $weatherData['visibility'] ?? null,
                        'wind_speed' => $weatherData['wind']['speed'],
                        'wind_deg' => $weatherData['wind']['deg'],
                        'cloudiness' => $weatherData['clouds']['all'] ?? null,
                        'weather_main' => $weatherData['weather'][0]['main'],
                        'weather_description' => $weatherData['weather'][0]['description'],
                        'weather_icon' => $weatherData['weather'][0]['icon'],
                        'sunrise' => Carbon::createFromTimestamp($weatherData['sys']['sunrise']),
                        'sunset' => Carbon::createFromTimestamp($weatherData['sys']['sunset']),
                        'country' => $weatherData['sys']['country'],
                    ]
                );

                $this->info('Weather data for ' . $city . ' stored successfully.');
            } else {
                $this->error('Failed to fetch weather data for ' . $city);
            }
        }
    }
}
