<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Investment Packages
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header Info -->
            <div class="bg-gradient-to-r from-spinneys-green to-spinneys-green-800 rounded-xl shadow-lg p-8 mb-6 text-white">
                <div class="max-w-3xl">
                    <h1 class="text-3xl font-bold mb-3">Choose Your Investment Plan</h1>
                    <p class="text-gray-100 text-lg">Spinneys</p>
                    <div class="mt-6 grid grid-cols-3 gap-6">
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-spinneys-gold mb-1">0.5% - 0.9%</div>
                            <div class="text-sm opacity-90">Daily Interest</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-spinneys-gold mb-1">90-150</div>
                            <div class="text-sm opacity-90">Days Duration</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-spinneys-gold mb-1">24/7</div>
                            <div class="text-sm opacity-90">Auto Processing</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Investment Packages Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $package)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 {{ $loop->index === 2 ? 'border-spinneys-gold ring-4 ring-spinneys-gold/20' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    @if($loop->index === 2)
                    <div class="bg-gradient-to-r from-spinneys-gold to-spinneys-gold-600 text-white text-xs font-bold text-center py-2 uppercase tracking-wide">
                        ⭐ Most Popular
                    </div>
                    @endif

                    <!-- Package Image -->
                    @if($package->image)
                    <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <img src="{{ asset($package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <div class="p-6">
                        <!-- Package Header -->
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $package->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $package->description }}</p>
                        </div>

                        <!-- Interest Rate -->
                        <div class="text-center mb-6 p-4 bg-spinneys-green/5 rounded-xl">
                            <div class="text-5xl font-bold text-spinneys-green mb-1">{{ $package->daily_interest_rate }}%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Daily Interest Rate</div>
                        </div>

                        <!-- Package Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-spinneys-green mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">AED {{ number_format($package->min_amount, 0) }}</span> -
                                    <span class="font-semibold">AED {{ number_format($package->max_amount, 0) }}</span> investment range
                                </span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-spinneys-green mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">{{ $package->duration_days }} days</span> investment period
                                </span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-spinneys-green mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold capitalize">{{ $package->tier_required }}</span> tier required
                                </span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 {{ $package->available_slots > 0 ? 'text-spinneys-green' : 'text-red-500' }} mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold {{ $package->available_slots > 0 ? 'text-spinneys-green' : 'text-red-500' }}">
                                        {{ $package->available_slots }}</span> / {{ $package->total_slots }} slots available
                                </span>
                            </div>
                        </div>

                        <!-- Expected Return Example -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wide">Example: AED {{ number_format($package->min_amount) }} Investment</div>
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xs text-gray-500">Total Return</div>
                                    <div class="text-lg font-bold text-spinneys-green">
                                        AED {{ number_format($package->calculateTotalReturn($package->min_amount), 2) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">Total Profit</div>
                                    <div class="text-lg font-bold text-spinneys-gold">
                                        AED {{ number_format($package->calculateTotalReturn($package->min_amount) - $package->min_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ route('investments.show', $package) }}"
                           class="block w-full text-center px-6 py-3 {{ $loop->index === 2 ? 'bg-spinneys-gold hover:bg-spinneys-gold-600' : 'bg-spinneys-green hover:bg-spinneys-green-700' }} text-white font-bold rounded-lg transition shadow-md hover:shadow-lg">
                            View Details & Invest
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Info Section -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Important Information</h4>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <li>• Daily interest is automatically calculated and credited at 00:30 UTC</li>
                            <li>• Principal + earnings are returned to your balance upon maturity</li>
                            <li>• Ensure your account tier matches the package requirements</li>
                            <li>• Slots are limited and allocated on a first-come, first-served basis</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
