<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
    
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') .'?'. rand(11111,99999) }}">
    
        @stack('styles')
        
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') .'?'. rand(11111,99999) }}" defer></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        
    </head>
    <body class="font-sans antialiased">
        <!-- Sidebar menu button desktop-->
        <button id="sidebar-toggle-desktop" class="top-2 left-2 p-2 fixed rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="min-h-screen flex bg-gray-100">
            <!-- Sidebar-->
            @include('user.layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col md:ml-64">
            
                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto">
                    <div class="mx-auto  py-4 px-4 md:px-8">
                        @include('user.layouts.header')
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>