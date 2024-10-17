@extends('layouts.app')

@section('content')
<main class="bg-gradient-to-r from-blue-50 to-blue-100 py-8">
    <section>
        <div class="container mx-auto px-4">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-800">{{ $weather->city_name }} {{ __('Weather App') }}</h1>
                <p class="text-lg text-gray-600 mt-2">{{ date('d-m-Y') }} | {{ __('Weather Forecast for') }} {{ $weather->city_name }}</p>
            </div>

            <!-- Current Weather Card -->
            <div class="mb-10">
                <div class="bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-blue-900 mb-4">{{ $weather->city_name }} {{ __('Current Weather') }}</h2>
                        <ul class="text-gray-800 space-y-2">
                            <li><strong>{{ __('Temperature') }}:</strong> {{ round($weather->temp) }} °C</li>
                            <li><strong>{{ __('Feels Like') }}:</strong> {{ round($weather->feels_like) }} °C</li>
                            <li><strong>{{ __('Humidity') }}:</strong> {{ $weather->humidity }}%</li>
                            <li><strong>{{ __('Wind Speed') }}:</strong> {{ $weather->wind_speed }} m/s</li>
                            <li><strong>{{ __('Weather Condition') }}:</strong> {{ __($weather->weather_description) }}</li>
                        </ul>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <img class="w-24 h-24" src="https://openweathermap.org/img/wn/{{ $weather->weather_icon }}@2x.png" alt="Weather Icon">
                        <p class="text-2xl text-gray-900 font-bold mt-2">{{ __($weather->weather_main) }}</p>
                    </div>
                </div>
            </div>

            <!-- Weather Forecast Table for Multiple Cities -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('City') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Temperature') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Feels Like') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Humidity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Wind Speed') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Weather Condition') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Icon') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($all_weather as $weather)
                            <tr class="hover:bg-gray-100 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ url('/hava-haqqinda', $weather->slug) }}" class="text-blue-500 hover:underline">
                                        {{ $weather->city_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ round($weather->temp) }} °C</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ round($weather->feels_like) }} °C</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $weather->humidity }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $weather->wind_speed }} m/s</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $weather->weather_description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img class="w-10 h-10" src="https://openweathermap.org/img/wn/{{ $weather->weather_icon }}@2x.png" alt="Weather Icon">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
@endsection
