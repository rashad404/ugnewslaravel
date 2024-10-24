@extends('layouts.app')
@php
    use App\Helpers\Format;
@endphp
@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Top Channels') }}</h1>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Rank') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Channel') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Views') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Subscribers') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($list as $index => $channel)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $bgColor = match($index + 1) {
                                        1 => 'bg-yellow-500',
                                        2 => 'bg-gray-400',
                                        3 => 'bg-yellow-700',
                                        default => 'bg-red-700',
                                    };
                                @endphp
                                <span class="{{ $bgColor }} text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $list->firstItem() + $index }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if (!empty($channel->image))
                                        <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $channel->image) }}" alt="{{ $channel->name }}">
                                    @endif
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ Format::urlTextChannel($channel->name_url) }}" class="hover:text-blue-600">
                                            {{ Str::limit($channel->name, 30) }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($channel->view) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($channel->subscribers) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $list->links() }}
    </div>
@endsection
