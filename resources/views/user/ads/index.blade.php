@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => __('Dashboard'), 'url' => route('user.dashboard')],
        ['title' => __('Ads'), 'url' => '#']
    ];
    @endphp

    <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ __('Ads') }}</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.ads.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
            {{ __('Create Ad') }}
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Image') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Clicks') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Views') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($ads as $ad)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4">
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="py-4 px-4">{{ $ad->title }}</td>
                    <td class="py-4 px-4">{{ $ad->click }}</td>
                    <td class="py-4 px-4">{{ $ad->view }}</td>
                    <td class="py-4 px-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ad->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ad->status ? __('Active') : __('Inactive') }}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('user.ads.edit', $ad) }}" class="text-yellow-500 hover:text-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('user.ads.destroy', $ad) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('{{ __('Are you sure?') }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ads->links() }}
    </div>
</div>
@endsection
