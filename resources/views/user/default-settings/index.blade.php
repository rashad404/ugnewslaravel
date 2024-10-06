@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('user.dashboard')],
        ['title' => 'Default Settings', 'url' => '#'],
    ];
    @endphp

    <h1 class="text-2xl font-bold mb-4">{{ __('Edit Default Settings') }}</h1>

    <div id="app">
        <form action="{{ route('user.default-settings.update', $defaultSetting->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <!-- Channel Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="channel_id">
                        {{ __('Channel') }}
                    </label>
                    <select name="channel_id" id="channel_id" class="select2 form-control w-full" required>
                        <option value="">{{ __('Select a channel') }}</option>
                        @foreach($channels as $channel)
                            <option value="{{ $channel->id }}" {{ $defaultSetting->channel_id == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Country Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="country_id">
                        {{ __('Country') }}
                    </label>
                    <select name="country_id" id="country_id" class="select2 form-control w-full" required>
                        <option value="">{{ __('Select a country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $defaultSetting->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Language Field -->
                <div class="col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="language_id">
                        {{ __('Language') }}
                    </label>
                    <select name="language_id" id="language_id" class="select2 form-control w-full" required>
                        <option value="">{{ __('Select a language') }}</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" {{ $defaultSetting->language_id == $language->id ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between mt-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    {{ __('Update') }}
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#channel_id, #country_id, #language_id').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
    </script>
@endpush