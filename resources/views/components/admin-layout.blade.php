<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen">
            <header class="bg-gradient-to-r from-spinneys-green to-spinneys-green-800 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('SPINNEYS.AE-1e6e5338.png') }}" alt="Spinneys" class="h-8">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 text-sm font-semibold">ADM</span>
                            </div>
                            <div>
                                <div class="text-sm uppercase tracking-wide text-white/70">Admin Control</div>
                                <div class="text-lg font-semibold">Spinneys Admin</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white">User Dashboard</a>
                            <span class="text-white/40">|</span>
                            <span class="text-white/80">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center gap-6 h-12 text-sm font-medium text-gray-600">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-spinneys-green' : 'hover:text-spinneys-green' }}">Overview</a>
                        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'text-spinneys-green' : 'hover:text-spinneys-green' }} inline-flex items-center gap-2">
                            Users
                            @if(($adminNavCounts['newUsers'] ?? 0) > 0)
                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-spinneys-green text-white text-[10px] font-semibold">
                                    {{ $adminNavCounts['newUsers'] }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.deposits') }}" class="{{ request()->routeIs('admin.deposits') ? 'text-spinneys-green' : 'hover:text-spinneys-green' }} inline-flex items-center gap-2">
                            Deposits
                            @if(($adminNavCounts['pendingDeposits'] ?? 0) > 0)
                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-spinneys-gold text-white text-[10px] font-semibold">
                                    {{ $adminNavCounts['pendingDeposits'] }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="{{ request()->routeIs('admin.withdrawals') ? 'text-spinneys-green' : 'hover:text-spinneys-green' }} inline-flex items-center gap-2">
                            Withdrawals
                            @if(($adminNavCounts['pendingWithdrawals'] ?? 0) > 0)
                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[10px] font-semibold">
                                    {{ $adminNavCounts['pendingWithdrawals'] }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>
            </div>

            <main class="py-8">
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
