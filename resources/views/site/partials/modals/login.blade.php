<div x-data="{ activeTab: 'login' }" class="bg-white rounded-lg shadow-xl max-w-md w-full mx-auto">
    <div class="flex border-b border-gray-200">
        <button @click="activeTab = 'login'" :class="{ 'border-b-2 border-blue-500': activeTab === 'login' }" class="flex-1 py-4 px-2 text-center text-gray-700 font-medium hover:text-blue-500 focus:outline-none">
            {{ __('Login') }}
        </button>
        <button @click="activeTab = 'register'" :class="{ 'border-b-2 border-blue-500': activeTab === 'register' }" class="flex-1 py-4 px-2 text-center text-gray-700 font-medium hover:text-blue-500 focus:outline-none">
            {{ __('Register') }}
        </button>
    </div>

    <div class="p-6">
        <!-- Login Form -->
        <form x-show="activeTab === 'login'" action="login" method="POST" class="space-y-4">
            @csrf
            <input id="redirect_url_login" type="hidden" name="redirectUrl" value={{$redirectUrl}}/>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Login or E-mail') }}</label>
                <input id="email" name="email" type="text" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Login') }}
                </button>
            </div>
        </form>

        <!-- Register Form -->
        <form x-show="activeTab === 'register'" action="register" method="POST" class="space-y-4">
            @csrf
            <input id="redirect_url_login" type="hidden" name="redirectUrl" value={{$redirectUrl}}/>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('First name') }}</label>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('Last name') }}</label>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('E-mail') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">{{ __('Gender') }}</label>
                    <select id="gender" name="gender" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>{{ __('Not selected') }}</option>
                        <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                </div>
                <div>
                    <label for="country_code" class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                    <select id="country_code" name="country_code" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        @foreach ($countryList as $country_code => $country_name)
                            <option value="{{ $country_code }}" {{ old('country_code') == $country_code ? 'selected' : '' }}>
                                {{ $country_name }} (+{{ $country_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone number') }}</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="birth_month" class="block text-sm font-medium text-gray-700">{{ __('Birth Month') }}</label>
                    <select name="birth_month" id="birth_month" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ old('birth_month') == $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                        @endforeach
                    </select>
                    @error('birth_month') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="birth_day" class="block text-sm font-medium text-gray-700">{{ __('Day') }}</label>
                    <select name="birth_day" id="birth_day" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach (range(1, 31) as $day)
                            <option value="{{ $day }}" {{ old('birth_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('birth_day') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="birth_year" class="block text-sm font-medium text-gray-700">{{ __('Year') }}</label>
                    <select name="birth_year" id="birth_year" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach (range(date('Y') - 16, 1950) as $year)
                            <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('birth_year') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>

    @if (isset($data['postData']['google_client']))
    <div class="px-6 pb-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
                    {{ __('Or continue with') }}
                </span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ $data['postData']['google_client']->createAuthUrl() }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">{{ __('Sign in with Google') }}</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                </svg>
                <span class="ml-2">{{ __('Continue with Google') }}</span>
            </a>
        </div>
    </div>
    @endif
</div>
