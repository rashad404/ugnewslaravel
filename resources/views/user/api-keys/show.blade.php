@extends('user.layouts.app')

@section('content')

@php
    $breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'API Keys', 'url' => route('user.api-keys.index')],
    ['title' => $apiKey->name, 'url' => '#']
];
@endphp

<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ $apiKey->name }}</h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('API Key Details') }}</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('API Key') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $apiKey->api_key }}</dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('Is Active') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $apiKey->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $apiKey->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-4 flex space-x-3">
        <a href="{{ route('user.api-keys.edit', $apiKey) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">{{ __('Edit') }}</a>
        <form action="{{ route('user.api-keys.destroy', $apiKey) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this API key?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">{{ __('Delete') }}</button>
        </form>
    </div>
</div>
@endsection
