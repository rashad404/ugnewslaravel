@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('user.dashboard')],
        ['title' => 'Ads', 'url' => route('user.ads.index')],
        ['title' => 'Edit', 'url' => '#']
    ];
    @endphp

    <h1 class="text-2xl font-bold mb-4">{{ __('Edit Ad') }}</h1>

    <div id="app">
        <form action="{{ route('user.ads.update', $ad) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Field -->
                <div class="col-span-1">
                    <input type="file" name="image" ref="fileInput" class="hidden">
                    <image-upload-component
                        :remove-label="{{ json_encode(__('Remove Image')) }}"
                        :choose-file-label="{{ json_encode(__('Choose an Image')) }}"
                        :current-image="{{ json_encode($ad->image ? asset('storage/' . $ad->image) : null) }}"
                    ></image-upload-component>
                </div>

                <div class="col-span-1">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            {{ __('Title') }}
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" required maxlength="20" value="{{ $ad->title }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="text">
                            {{ __('Text') }}
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="text" name="text" maxlength="50">{{ $ad->text }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="link">
                            {{ __('Link') }}
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link" type="url" name="link" required value="{{ $ad->link }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            {{ __('Status') }}
                        </label>
                        <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="1" {{ $ad->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$ad->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    {{ __('Update Ad') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection