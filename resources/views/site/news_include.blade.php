{{-- resources/views/news_include.blade.php --}}

@php
use App\Helpers\Format;
@endphp

<div class="bg-gray-100 py-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        {{ __('Latest News') }}
        <a href="{{ route('rating.news') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            {{ __('TOP') }} <i class="fas fa-chart-bar"></i>
        </a>
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($newsList as $list)
            <div class="bg-white rounded-lg overflow-hidden shadow-md flex flex-col h-full">
                <a href="{{ $list['slug'] }}" class="block flex-grow">
                    @if (!empty($list['image']))
                        <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $list['image']) }}" alt="{{ $list['title'] }}" />
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">{{ __('No Image Available') }}</span>
                        </div>
                    @endif
                    <div class="p-4 flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-3">{{ Format::listTitle($list['title'], 100) }} <span class="text-red-600">{{ $list['title_extra'] }}</span></h3>
                        <p class="text-gray-600 text-sm line-clamp-3">{{ Format::listText($list['text'], 150) }}</p>
                    </div>
                </a>

                <div class="px-4 py-3 bg-gray-50 mt-auto">
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-orange-600 font-medium">
                            {{ __(\App\Models\News::getCatName($list['category_id'])) }}
                        </span>
                        <span class="text-gray-500">
                            {{ date("d.m.Y", $list['publish_time']) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            @php $channel_info = \App\Models\Channel::find($list['channel_id']); @endphp
                            <img class="w-8 h-8 rounded-full mr-2 object-cover" src="{{ asset('storage/' . $channel_info['image']) }}" alt="{{ $channel_info['name'] }}" />
                            <a href="{{ Format::urlTextChannel($channel_info['name_url']) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">
                                {{ Format::listTitle($channel_info['name'], 20) }}
                            </a>
                        </div>
                        <div class="text-gray-500 flex items-center text-sm">
                            <i class="fas fa-eye mr-1"></i> {{ number_format($list['view']) }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 flex justify-center">
        {{ $newsList->links() }}
    </div>
</div>