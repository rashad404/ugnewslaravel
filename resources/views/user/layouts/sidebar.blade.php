<aside id="sidebar" class="bg-white w-64 min-h-screen fixed left-0 top-0 z-20
transform transition-transform duration-200 ease-in-out -translate-x-full
md:translate-x-0
">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between h-16 px-4">
            <!-- Logo on the left -->
            <img class="h-8 w-auto" src="{{ asset('images/ug_news.svg') }}" alt="{{ config('app.name', 'Laravel') }}">

            <!-- Sidebar menu button on the right -->
            <button id="sidebar-toggle-mobile"
                class="z-50 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- LEFT Navigation --}}
        <nav class="mt-5 flex-1 overflow-y-auto">
            <!-- Dashboard (ungrouped) -->
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.dashboard') ? 'bg-gray-100 text-gray-900' : '' }}">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </a>

            <!-- Content Management Group -->
            <div class="mt-4">
                <h3 class="px-6 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Content Management
                </h3>
                <a href="{{ route('user.news.index') }}"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.news.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    Your News
                </a>
                <a href="{{ route('user.channels.index') }}"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.channels.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                        </path>
                    </svg>
                    Your Channels
                </a>
            </div>

            <!-- Settings Group -->
            <div class="mt-4">
                <h3 class="px-6 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Settings
                </h3>
                <a href="{{ route('user.default-settings.index') }}"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('settings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Default Settings
                </a>
            </div>

            <!-- Advertising Group -->
            <div class="mt-4">
                <h3 class="px-6 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Advertising
                </h3>
                <a href="{{ route('user.ads.index') }}"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.ads.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Your Ads
                </a>
            </div>

            <!-- User Account Group -->
            <div class="mt-4">
                <h3 class="px-6 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Account
                </h3>
                <a href="{{ route('user.profile') }}"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.profile') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                    class="flex items-center px-6 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.profile') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </nav>

    </div>
</aside>