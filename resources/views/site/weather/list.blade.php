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
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4"><a href="{{ url('hava-haqqinda', $weather->slug) }}" class="text-blue-500 hover:underline">{{ $weather->city_name }}</a></td>
                    <td class="px-6 py-4">{{ round($weather->temp) }} °C</td>
                    <td class="px-6 py-4">{{ round($weather->feels_like) }} °C</td>
                    <td class="px-6 py-4">{{ $weather->humidity }}%</td>
                    <td class="px-6 py-4">{{ $weather->wind_speed }} m/s</td>
                    <td class="px-6 py-4">{{ $weather->weather_description }}</td>
                    <td class="px-6 py-4"><img class="w-10 h-10" src="https://openweathermap.org/img/wn/{{ $weather->weather_icon }}@2x.png" alt="Weather Icon"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
