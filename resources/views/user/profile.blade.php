@extends('user.layouts.app')

@section('content')
@php
use Carbon\Carbon;

$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'Profile', 'url' => '#']
];
@endphp

<main class="bg-gray-100 py-10">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Profile info') }}</h1>
        
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Profile Picture -->
                <div id="app" class="max-w-96">
                    <input type="file" name="image" ref="fileInput" class="hidden">
                    <image-upload-component
                        :remove-label="{{ json_encode(('Remove Image')) }}"
                        :choose-file-label="{{ json_encode(('Choose an Image')) }}"
                        :current-image="{{ json_encode($user->image ? asset('storage/' . $user->image) : null) }}"
                        image-class="rounded-full object-cover w-full h-full"
                    ></image-upload-component>
                </div>
                <!-- Name Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('First name') }}</label>
                        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('Last name') }}</label>
                        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Birthday Field -->
                <div>
                    <label for="birthday" class="block text-sm font-medium text-gray-700">{{ __('Birthday') }}</label>
                    <input type="date" id="birthday" name="birthday" value="{{ old('birthday', Carbon::parse($user->birthday)->format('Y-m-d')) }}" max="{{ now()->subYears(16)->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Gender Field -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">{{ __('Gender') }}</label>
                    <select id="gender" name="gender" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="0" {{ old('gender', $user->gender) == 0 ? 'selected' : '' }}>{{ __('Not selected') }}</option>
                        <option value="1" {{ old('gender', $user->gender) == 1 ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="2" {{ old('gender', $user->gender) == 2 ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                </div>

                <!-- Country and Phone Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                        <select id="country_code" name="country_code" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ($countryList as $country_code => $country_name)
                                <option value="{{ $country_code }}" {{ old('country_code', $user->country_code) == $country_code ? 'selected' : '' }}>{{ $country_name.' (+'.$country_code.')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone number') }}</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', substr($user->phone, strlen($user->country_code))) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Email Field (Disabled) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('E-Mail') }}</label>
                    <input id="email" type="email" value="{{ $user->email }}" disabled class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-gray-500">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicturePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush