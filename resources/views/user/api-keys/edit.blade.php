@extends('user.layouts.app')

@section('content')

@php
    $breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'API Keys', 'url' => route('user.api-keys.index')],
    ['title' => 'Edit', 'url' => '#']
];
@endphp


<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ __('Edit API Key') }}</h1>

    <form action="{{ route('user.api-keys.update', $apiKey) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">{{ __('API Key Name') }}</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" required value="{{ $apiKey->name }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="is_active">{{ __('Is Active') }}</label>
            <select name="is_active" id="is_active" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" {{ $apiKey->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !$apiKey->is_active ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">{{ __('Update API Key') }}</button>
        </div>
    </form>
</div>
@endsection
