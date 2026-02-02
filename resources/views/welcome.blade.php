<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPINNEYS - Secure Investment Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center space-x-3">
                        <img src="{{ asset('SPINNEYS.AE-1e6e5338.png') }}" alt="SPINNEYS" class="h-10">
                        <img src="{{ asset('spinneys.png') }}" alt="SPINNEYS" class="h-8">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-spinneys-green">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-spinneys-green">Login</a>
                        <a href="{{ route('register') }}" class="bg-spinneys-green text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-spinneys-green-700 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-spinneys-green via-spinneys-green-800 to-spinneys-green-900 text-white overflow-hidden min-h-[500px]">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('4922a9ec-2f44-45fd-b9f6-1f5040479cf1.jpeg') }}" alt="Background" class="w-full h-full object-cover shadow-[inset_0_0_100px_rgba(0,0,0,0.6)]" style="box-shadow: inset 0 0 150px rgba(0,0,0,0.7);">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/70 to-spinneys-green-900/90"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6 text-white" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8), 0 0 25px rgba(0,0,0,0.5);">Grow Your Wealth with Confidence</h1>
                <div class="flex justify-center space-x-4">
                    @guest
                        <a href="{{ route('register') }}" class="bg-spinneys-gold text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-spinneys-gold-600 transition shadow-2xl hover:shadow-xl transform hover:scale-105">
                            Buy Shares
                        </a>
                        <a href="#packages" class="bg-white text-spinneys-green px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition shadow-2xl hover:shadow-xl transform hover:scale-105">
                            View Packages
                        </a>
                    @else
                        <a href="{{ route('investments.packages') }}" class="bg-spinneys-gold text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-spinneys-gold-600 transition shadow-2xl hover:shadow-xl transform hover:scale-105">
                            Browse Packages
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose SPINNEYS</h2>
                <p class="text-lg text-gray-600">Built for security, designed for growth</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-spinneys-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-spinneys-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Bank-Level Security</h3>
                    <p class="text-gray-600">Your funds are protected with enterprise-grade encryption and multi-factor authentication</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-spinneys-gold-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-spinneys-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Automated Processing</h3>
                    <p class="text-gray-600">Daily interest calculations and automatic compounding for maximum returns</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-spinneys-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-spinneys-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Referral Rewards</h3>
                    <p class="text-gray-600">Earn up to 8% commission across 3 levels when you refer new investors</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Packages Section -->
    <div id="packages" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Investment Packages</h2>
                <p class="text-lg text-gray-600">Choose the plan that fits your investment goals</p>
            </div>

            @php
                $packages = \App\Models\InvestmentPackage::where('is_active', true)->orderBy('min_amount')->get();
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($packages as $package)
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition @if($loop->index === 2) ring-2 ring-spinneys-gold @endif">
                        @if($loop->index === 2)
                            <div class="inline-block bg-spinneys-gold text-gray-900 px-3 py-1 rounded-full text-xs font-semibold mb-4">
                                Most Popular
                            </div>
                        @endif

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-600 mb-6">{{ $package->description }}</p>

                        <div class="mb-6">
                            <div class="text-5xl font-bold text-spinneys-green mb-2">
                                {{ number_format($package->daily_interest_rate, 1) }}%
                            </div>
                            <div class="text-sm text-gray-500">Daily Interest Rate</div>
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>${{ number_format($package->min_amount) }} - ${{ number_format($package->max_amount) }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $package->duration_days }} Days Duration</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ ucfirst($package->required_tier) }} Tier Required</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $package->available_slots }}/{{ $package->total_slots }} Slots Available</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="text-xs text-gray-500 mb-1">Example ROI (Min Investment)</div>
                            <div class="text-lg font-bold text-gray-900">
                                ${{ number_format($package->calculateTotalReturn($package->min_amount), 2) }}
                            </div>
                            <div class="text-xs text-green-600">
                                +${{ number_format($package->calculateTotalReturn($package->min_amount) - $package->min_amount, 2) }} profit
                            </div>
                        </div>

                        <a href="{{ route('investments.show', $package) }}" class="block w-full text-center py-3 rounded-lg font-semibold transition @if($loop->index === 2) bg-spinneys-gold text-gray-900 hover:bg-spinneys-gold-600 @else bg-spinneys-green text-white hover:bg-spinneys-green-700 @endif">
                            View Details & Invest
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <p class="text-sm text-blue-800">
                    <strong>Important:</strong> All investments are subject to our terms and conditions. Daily interest is calculated and credited automatically. Minimum investment amounts and tier requirements apply.
                </p>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600">Start investing in 4 simple steps</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-spinneys-green text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                    <h3 class="text-lg font-semibold mb-2">Create Account</h3>
                    <p class="text-gray-600">Sign up with your email and verify your account</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-spinneys-green text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                    <h3 class="text-lg font-semibold mb-2">Add Funds</h3>
                    <p class="text-gray-600">Deposit funds securely to your account balance</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-spinneys-green text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                    <h3 class="text-lg font-semibold mb-2">Choose Package</h3>
                    <p class="text-gray-600">Select an investment package that fits your goals</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-spinneys-gold text-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">4</div>
                    <h3 class="text-lg font-semibold mb-2">Earn Daily</h3>
                    <p class="text-gray-600">Watch your investment grow with automatic daily returns</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-spinneys-green to-spinneys-green-700 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-4">Ready to Start Growing Your Wealth?</h2>
            <p class="text-xl text-green-100 mb-8">Join thousands of successful investors on SPINNEYS platform</p>
            @guest
                <a href="{{ route('register') }}" class="inline-block bg-spinneys-gold text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-spinneys-gold-600 transition">
                    Create Free Account
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-block bg-spinneys-gold text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-spinneys-gold-600 transition">
                    Go to Dashboard
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <img src="{{ asset('SPINNEYS.AE-1e6e5338.png') }}" alt="SPINNEYS" class="h-8 mb-4">
                    <p class="text-gray-400 text-sm">Secure investment platform for building your financial future</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Investment Packages</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">How It Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Referral Program</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="text-gray-400">support@spinneys.com</li>
                        <li class="text-gray-400">24/7 Live Support</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p class="text-gray-400">&copy; {{ date('Y') }} SPINNEYS Financial Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
