<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Weather;

class FetchWeather extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather information using OpenWeatherMap API';

    public function handle()
    {
        // Replace with your API key from OpenWeatherMap
        $apiKey = '3b89a838fe235e57464a9017a9785847';
        $cities = ['Baku', 'Ganja', 'Sheki', 'Lankaran']; // Add more cities as needed

        foreach ($cities as $city) {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric'  // Get temperature in Celsius
            ]);

            if ($response->ok()) {
                $weatherData = $response->json();
                dump($weatherData);

                Weather::updateOrCreate(
                    ['city_name' => $city, 'day' => now()->toDateString()],
                    [
                        'night_temperature' => $weatherData['main']['temp_min'] . '°C',
                        'day_temperature' => $weatherData['main']['temp_max'] . '°C',
                        'wind_condition' => $weatherData['wind']['speed'] . ' m/s',
                    ]
                );
            } else {
                $this->error('Failed to fetch weather data for ' . $city);
            }
        }

        $this->info('Weather data fetched and stored successfully.');
    }
}
