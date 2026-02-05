<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 {{ Route::is('login', 'register') ? 'relative' : '' }}"
            @if(Route::is('login', 'register'))
                style="background-image: url('{{ asset('Screenshot_8.png') }}'); background-size: cover; background-position: center;"
            @endif
        >
            @if(Route::is('login', 'register'))
                <div class="absolute inset-0 bg-black/60"></div>
            @endif

            <div class="relative z-10 flex flex-col items-center space-y-3">
                <a href="/">
                    @if(Route::is('login', 'register'))
                        <img src="{{ asset('SPINNEYS.AE.D-7bb1a4ef.png') }}" alt="SPINNEYS" class="h-16">
                    @else
                        <img src="{{ asset('SPINNEYS.AE-1e6e5338.png') }}" alt="SPINNEYS" class="h-16">
                    @endif
                </a>
                @if(Route::is('login', 'register'))
                    <img src="{{ asset('spinneyside.png') }}" alt="SPINNEYS" class="h-10">
                @else
                    <img src="{{ asset('spinneys.png') }}" alt="SPINNEYS" class="h-10">
                @endif
            </div>

            @if(Route::is('login'))
                <div class="relative z-10 w-full max-w-md mt-6">
                    {{ $slot }}
                </div>
            @else
                <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white/95 dark:bg-gray-800/95 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </body>
</html>
