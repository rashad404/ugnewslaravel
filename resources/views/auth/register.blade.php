@extends('layouts.app')

@section('content')
<main class="bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white shadow rounded-lg p-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Registration</h2>
            
            <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('last_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Not selected</option>
                        <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Male</option>
                        <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700">Country</label>
                        <select name="country_code" id="country_code" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach ($countryList as $country_code => $country_name)
                                <option value="{{ $country_code }}" {{ old('country_code') == $country_code ? 'selected' : '' }}>{{ $country_name.' (+'.$country_code.')' }}</option>
                            @endforeach
                        </select>
                        @error('country_code') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                    <div>
                        <label for="birth_month" class="block text-sm font-medium text-gray-700">Birth Month</label>
                        <select name="birth_month" id="birth_month" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}" {{ old('birth_month') == $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endforeach
                        </select>
                        @error('birth_month') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="birth_day" class="block text-sm font-medium text-gray-700">Day</label>
                        <select name="birth_day" id="birth_day" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach (range(1, 31) as $day)
                                <option value="{{ $day }}" {{ old('birth_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('birth_day') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="birth_year" class="block text-sm font-medium text-gray-700">Year</label>
                        <select name="birth_year" id="birth_year" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach (range(date('Y') - 16, 1950) as $year)
                                <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('birth_year') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">If you have an account, then</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
