<header class="bg-white shadow" x-data="{ mobileMenuOpen: false, searchDropdownOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4 lg:py-6">
            <div class="flex items-center">
                <a href="/" class="flex-shrink-0">
                    <img class="h-10 w-auto sm:h-10" width="107" height="40" src="{{ asset('images/ug_news.svg') }}" alt="{{ config('app.name') }} logo"/>
                </a>
                <div class="hidden lg:ml-6 lg:flex lg:space-x-6 pl-4">
                    @foreach ($menus->take(5) as $menu)
                        <a href="{{ $menu->url }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            {{ $menu['title_' . $locale] }}
                        </a>
                    @endforeach
                
                    @if ($menus->count() > 7)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-base font-medium text-gray-500 hover:text-gray-900 flex items-center">
                                {{ __('More') }}
                                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-10 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                @foreach ($menus->skip(7) as $menu)
                                    <a href="{{ $menu->url }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $menu['title_' . $locale] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
            </div>
            <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="max-w-lg w-full lg:max-w-xs relative hidden md:block">
                    <label for="search" class="sr-only">{{ __('Search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="header_search_input" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('Channel or News') }}" type="search">
                    </div>
                    <div id="headerSearchDropDown" class="absolute z-10 mt-2 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-96" style="display: none;">
                        <!-- Search results will be populated here -->
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                @include('layouts.country_dropdown')
                @include('layouts.account_dropdown')
            </div>
            <div class="-mr-2 -my-2 md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @foreach ($menus as $menu)
                <a href="{{ $menu['url'] }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    {{ $menu['title_' . $locale] }}
                </a>
            @endforeach
            @auth
                <a href="https://new.ug.news/user/profile" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Logout')}}</a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        {{ __('Sign in') }}
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        {{ __('Sign up') }}
                    </a>
                @endauth
            </div>
        </div>
    
        <div id="mainContentOverlay" class="fixed inset-0 bg-black opacity-50 z-40" style="display: none;"></div>
    </header>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerSearchInput = document.getElementById('header_search_input');
            const headerSearchDropDown = document.getElementById('headerSearchDropDown');
        
            if (headerSearchInput) {
                headerSearchInput.addEventListener('input', function() {
                    performSearch(this.value);
                });
            }
        
            function performSearch(inputVal) {
                if (inputVal.length >= 2) {
                    fetch(`/api/search?q=${encodeURIComponent(inputVal)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (headerSearchDropDown) {
                                headerSearchDropDown.innerHTML = renderSearchResults(data);
                                headerSearchDropDown.style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    if (headerSearchDropDown) {
                        headerSearchDropDown.style.display = 'none';
                    }
                }
            }
        
            function renderSearchResults(data) {
                let html = '';
                
                if (data.channels && data.channels.length > 0) {
                    html += '<div class="px-4 py-2 font-semibold text-gray-700">Xəbər Kanalları</div>';
                    data.channels.forEach(channel => {
                        html += `
                            <a href="/channel/${channel.id}" class="block px-4 py-2 hover:bg-gray-100 flex items-center">
                                <img src="${channel.image}" alt="${channel.name}" class="w-8 h-8 rounded-full mr-2">
                                <span>${channel.name}</span>
                                <span class="ml-auto text-sm text-gray-500">${channel.subscribers} abunə</span>
                            </a>
                        `;
                    });
                }
        
                if (data.news && data.news.length > 0) {
                    html += '<div class="px-4 py-2 font-semibold text-gray-700">Xəbərlər</div>';
                    data.news.forEach(newsItem => {
                        html += `
                            <a href="/news/${newsItem.id}" class="block px-4 py-2 hover:bg-gray-100">
                                <div class="font-medium">${newsItem.title}</div>
                                <div class="text-sm text-gray-500">${newsItem.publish_time}</div>
                            </a>
                        `;
                    });
                }
        
                return html;
            }
        });
        </script>
    
    <script>
        function countryDropdown() {
            return {
                open: false,
                countries: [],
                searchQuery: '',
                loadCountries() {
                    if (this.countries.length === 0) {
                        fetch('{{ route('api.countries') }}')
                            .then(response => response.json())
                            .then(data => {
                                this.countries = data;
                            })
                            .catch(error => console.error('Error fetching countries:', error));
                    }
                },
                filteredCountries() {
                    if (this.searchQuery === '') {
                        return this.countries;
                    }
                    return this.countries.filter(country => 
                        country.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        country.code.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                }
            };
        }
    </script>
    @endpush