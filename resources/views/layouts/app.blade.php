<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="h-full bg-gray-100">
<head>
    <base href="/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    
    @if ($metaImg)
        <meta property="og:image" content="{{ asset($metaImg) }}">
    @endif
    <meta property="og:url" content="https://{{ $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}">
    <meta property="og:site_name" content="{{config('app.name')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon/favicon-32x32.png') }}" type="image/png">


    <style>[x-cloak] { display: none !important; }</style>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') .'?'. rand(11111,99999) }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') .'?'. rand(11111,99999) }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        
        @include('layouts.header')
            
        <!-- Logo Header -->
        {{-- <header class="w-full bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center h-16">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/ug_news.svg') }}" alt="{{ config('app.name', 'Laravel') }}">
                    </a>
                </div>
            </div>
        </header> --}}

        <!-- Page Content -->
        <main class="flex-1 w-full">
            <div class="max-w-7xl container mx-auto py-8 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        @include('layouts.footer')
        @stack('scripts')
    </div>
</body>
</html>
