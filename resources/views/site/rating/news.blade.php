@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('TOP News') }}</h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Rank') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('News Title') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('View') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($list as $index => $news)
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
                            <td class="px-6 py-4">
                                <a href="{{ url($news->slug) }}" class="text-sm text-gray-900 hover:text-blue-600 transition duration-150 ease-in-out">
                                    {{ Str::limit($news->title, 60) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($news->view) }}
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
