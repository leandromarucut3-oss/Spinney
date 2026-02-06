<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPINNEYS</title>
    @php($faviconVersion = filemtime(public_path('SPINNEYS.AE-1e6e5338.png')))
    <link rel="icon" type="image/png" href="{{ asset('SPINNEYS.AE-1e6e5338.png') }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('SPINNEYS.AE-1e6e5338.png') }}?v={{ $faviconVersion }}">
    <link rel="apple-touch-icon" href="{{ asset('SPINNEYS.AE-1e6e5338.png') }}?v={{ $faviconVersion }}">
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

    <!-- Press Release Section -->
    <div class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center mb-10">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <img src="{{ asset('Spinneyss.png') }}" alt="Spinneyss" class="h-12 sm:h-14 w-auto">
                    <img src="{{ asset('Spinneysxx.png') }}" alt="Spinneysxx" class="h-12 sm:h-14 w-auto">
                </div>
                <p class="mt-4 text-2xl font-semibold text-orange-500 text-center">
                    Spinneys partners with Ayala Corporation to open stores in the Philippines
                </p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 md:p-10 shadow-sm">
                <div class="space-y-5 text-gray-700 leading-relaxed">
                    <ul class="list-disc pl-5 space-y-2">
                        <li>The venture represents Spinneys’ first expansion outside of the Gulf Cooperation Council (GCC).</li>
                        <li>The venture plans to open premium grocery stores in the Philippines’ capital region.</li>
                        <li>Partnership is the latest in a string of international collaborations for Ayala, bringing world-class products and services to Filipinos.</li>
                    </ul>

                    <p>MANILA/DUBAI – Ayala Corporation and Spinneys, the leading premium fresh food supermarket chain in the United Arab Emirates, have entered a strategic business venture to open stores in the Philippines.</p>

                    <p>Spinneys is owned by the Al Seer Group, a consumer holdings company part of a UAE-based group with business interests in industries such as food, retail, hospitality, shipyards and construction with a presence in over 20 countries.</p>

                    <p>This venture follows Ayala’s recent announcement of its partnership with Thailand’s CP AXTRA to open Makro stores in the Philippines. These, together with Ayala’s earlier collaborations with Kmart Australia Ltd. to bring home and lifestyle brand Anko and with BYD to bring the world’s leading EV brand to the Philippines, underscore Ayala’s continued commitment to partnering with world-class companies to help build businesses that enable people to thrive across its portfolio.</p>

                    <p>The partnership with Spinneys will see Ayala combine its deep local market knowledge and strong experience across the property, retail, and logistics sectors with Spinneys’ operational and brand expertise in premium fresh food retail.</p>

                    <p>“The Philippines offers significant long-term growth potential, with strong economic fundamentals, a growing affluent population, and increasing demand for high-quality offerings. Our partnership with Ayala combines their deep local knowledge with our operational expertise, providing a strong foundation to grow. As we enter this next phase, we’re delighted to be bringing our high-quality and fresh offering to a new region that is natural for us to serve as we are proud to employ and cater to many Filipinos in our current market,” said Sunil Kumar, Chief Executive Officer at Spinneys.</p>

                    <p>“We are honored to be the first partner of Spinneys as it ventures outside the GCC. We hope this investment will catalyze trade and investment between the Philippines and the GCC. At Ayala, we take pride in partnering with some of the world’s leading companies and working alongside them to bring world-class products and services to the Philippines,” said Ayala Corporation President and CEO Cezar P. Consing.</p>
                </div>
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
                    <p class="text-gray-400 text-sm">Spinneys</p>
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
