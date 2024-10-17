@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => __('Dashboard'), 'url' => route('user.dashboard')],
        ['title' => __('Channels'), 'url' => route('user.channels.index')],
        ['title' => __('Edit'), 'url' => '#']
    ];
    @endphp

    <h1 class="text-2xl font-bold mb-4">{{ __('Edit Channel') }}</h1>

    <div id="app">
        <form action="{{ route('user.channels.update', $channel) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Image Upload Component -->
                <input type="file" name="image" ref="fileInput" class="hidden">
                <image-upload-component
                    :remove-label="{{ json_encode(__('Remove Image')) }}"
                    :choose-file-label="{{ json_encode(__('Choose an Image')) }}"
                    :current-image="{{ json_encode($channel->image ? asset('storage/' . $channel->image) : null) }}"
                ></image-upload-component>

                <!-- Name Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        {{ __('Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" value="{{ $channel->name }}" required>
                </div>

                <!-- Category Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        {{ __('Category') }}
                    </label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $channel->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Country Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="country_id">
                        {{ __('Country') }}
                    </label>
                    <select name="country_id" id="country_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $channel->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Language Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="language_id">
                        {{ __('Language') }}
                    </label>
                    <select name="language_id" id="language_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" {{ $channel->language_id == $language->id ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                        {{ __('Tags') }}
                    </label>
                    <select id="tags" name="tags[]" class="select2 form-control w-full" multiple="multiple">
                        @if ($channel->tags != null)
                            @foreach(explode(',', $channel->tags) as $tag)
                            <option value="{{ $tag }}" selected>{{ $tag }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <!-- About Field -->
            <div class="mb-4 mt-8">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="text">
                    {{ __('About') }}
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="text" name="text" rows="5">{{ $channel->text }}</textarea>
            </div>

            <!-- Status Field -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="status" value="1" {{ $channel->status ? 'checked' : '' }}>
                    <span class="ml-2">{{ __('Active') }}</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between mt-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    {{ __('Update Channel') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery and Select2 initialization -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#category_id, #country_id, #language_id').select2();

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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
