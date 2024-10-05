@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'News', 'url' => route('user.news.index')],
    ['title' => 'Create', 'url' => '#']
    ];
    @endphp

    <h1 class="text-2xl font-bold mb-4">{{ __('Create News Article') }}</h1>

    <div id="app">
        <form action="{{ route('user.news.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Image Field -->
                <input type="file" name="image" ref="fileInput" class="hidden">
                <image-upload-component :remove-label="{{ json_encode(__('Remove Image')) }}"
                    :choose-file-label="{{ json_encode(__('Choose an Image')) }}"></image-upload-component>

                <!-- Title Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        {{ __('Title') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title" type="text" name="title" required>
                </div>


                <!-- Category Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        {{ __('Category') }}
                    </label>
                    <select name="category_id" id="category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="city_id">
                        {{ __('City') }}
                    </label>
                    <select name="city_id" id="city_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="" disabled selected>{{ __('Please select a city') }}</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>

                </div>

                <!-- Publish Time Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="publish_time">
                        {{ __('Publish Time') }}
                    </label>
                    <input type="datetime-local" name="publish_time" id="publish_time"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Title Extra Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title_extra">
                        {{ __('Extra Title') }}
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title_extra" type="text" name="title_extra">
                </div>

                <!-- Channel Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="channel_id">
                        {{ __('Channel') }}
                    </label>
                    <select name="channel_id" id="channel_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        @foreach($channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                        {{ __('Tags') }}
                    </label>
                    <select id="tags" name="tags[]" class="select2 form-control w-full" multiple="multiple">
                    </select>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="mb-4 mt-8">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                    {{ __('Content') }} <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="text" id="text_content">
                <text-editor-component></text-editor-component>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between mt-4">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    {{ __('Create News Article') }}
                </button>
            </div>
        </form>
    </div>


</div>

@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush
@push('scripts')
<!-- jQuery and Select2 initialization -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('#tags').select2({
        tags: true,
        tokenSeparators: [',', ' '],
        createTag: function (params) {
            return {
            id: params.term,
            text: params.term,
            newOption: true
            };
        }
        });
    });
</script>
@endpush