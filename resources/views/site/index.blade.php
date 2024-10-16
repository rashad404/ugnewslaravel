@extends('layouts.app')

@section('content')
@php
use App\Models\Currency;
use App\Models\NamazTimes;
use App\Helpers\Format;

// Prepare the info list dynamically
$info_list = [
    ['Valyuta', "1 USD = " . ($usdRate ?? 'N/A') . " AZN", 'valyuta'],
    ['Hava haqqında', "Bakı " . ($bakuWeatherInfo ?? 'N/A') . " °C", 'hava-haqqinda'],
    ['Namaz vaxtı', "Sübh: " . ($todayNamaz['fajr'] ?? 'N/A'), "namaz-vaxti"]
];

$tag_list = ($region == 16) 
    ? ['Bakı', 'Türkiyə', 'Hava', 'Neft qiyməti']
    : ['Bakı', 'New York', 'Oil price'];
@endphp

<!-- Info Boxes (Compact) -->
<div class="mt-4 flex flex-wrap justify-between items-center text-sm">
    @foreach ($info_list as $list)
        <a href="{{ $list[2] }}" class="mb-2 px-3 py-1 bg-white rounded-full shadow hover:bg-gray-50">
            <span class="font-medium text-gray-600">{{ $list[0] }}:</span>
            <span class="text-gray-800">{{ $list[1] }}</span>
        </a>
    @endforeach
</div>

<!-- Featured Tags (Compact) -->
<div class="mt-4 flex flex-wrap gap-2">
    @foreach ($tag_list as $tag)
        <a href="{{ url('tags/' . Format::urlTextTag($tag)) }}" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
            #{{ Format::shortText($tag, 20) }}
        </a>
    @endforeach
</div>

<!-- Channels Section (Compact) -->
@if ($newsList->onFirstPage())
    <div class="mt-8 px-4 py-6 sm:px-6 lg:px-8 bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">
                {{ __('Channels') }}
            </h2>
            <a href="{{ route('rating.channels') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                {{ __('TOP') }} <i class="fas fa-chart-bar"></i>
            </a>
        </div>
        <div class="flex overflow-x-auto pb-2 -mx-4 sm:mx-0">
            <div class="flex-none px-4 sm:px-0 mr-4 lg:mr-16">
                <a href="{{ route('create.channel') }}" class="block w-20 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-2 shadow">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-600">{{ __('Create Channel') }}</span>
                </a>
            </div>
            @foreach ($channelList as $list)
                <div class="flex-none px-4 sm:px-0 mr-4 lg:mr-16">
                    <a href="{{ Format::urlTextChannel($list['name_url']) }}" class="block w-20 text-center">
                        <img src="{{ asset('storage/' . $list['image']) }}" alt="{{ $list['name'] }}" class="w-20 h-20 object-cover rounded-full mb-2 shadow">
                        <span class="text-xs text-gray-600">{{ Format::listTitle($list['name'], 20) }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Latest News -->
<div class="mt-8">
    @include('site.news_include')
</div>

<!-- Local News (Compact) -->
@if ($newsList->onFirstPage() && count($cityList1) > 0)
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            {{ __('Local News') }}
        </h2>
        <div class="flex flex-wrap gap-2">
            @php
            $allCities = array_merge($cityList1, $cityList2);
            @endphp
            @foreach (array_slice($allCities, 0, 12) as $list)
                <a href="{{ url('city/' . $list['id'] . '/' . Format::urlText($list['name'])) }}" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
                    {{ Format::listTitle($list['name'], 20) }}
                </a>
            @endforeach
            @if (count($allCities) > 12)
                <button id="show-more-cities" class="px-3 py-1 bg-indigo-100 text-sm font-medium text-indigo-700 rounded-full hover:bg-indigo-200 transition-colors duration-200">
                    {{ __('More') }} +
                </button>
            @endif
        </div>
        @if (count($allCities) > 12)
            <div id="more-cities" class="hidden mt-2 flex flex-wrap gap-2">
                @foreach (array_slice($allCities, 12) as $list)
                    <a href="{{ url('city/' . $list['id'] . '/' . Format::urlText($list['name'])) }}" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
                        {{ Format::listTitle($list['name'], 20) }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButton = document.getElementById('show-more-cities');
        const moreCities = document.getElementById('more-cities');

        if (showMoreButton && moreCities) {
            showMoreButton.addEventListener('click', function() {
                moreCities.classList.toggle('hidden');
                showMoreButton.textContent = moreCities.classList.contains('hidden') 
                    ? '{{ __('More') }} +' 
                    : '{{ __('Less') }} -';
            });
        }
    });
</script>
@endpush

@endsection