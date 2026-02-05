<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $package->name }} - Investment Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Package Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Package Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-spinneys-green to-spinneys-green-800 p-6 text-white">
                            <h1 class="text-3xl font-bold mb-2">{{ $package->name }}</h1>
                            <p class="text-gray-100">{{ $package->description }}</p>
                            <div class="mt-4 inline-flex items-center px-4 py-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <span class="text-4xl font-bold mr-2">{{ $package->daily_interest_rate }}%</span>
                                <span class="text-sm">Daily Interest</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Package Features</h3>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-spinneys-green/10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-5 h-5 text-spinneys-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">Investment Range</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            AED {{ number_format($package->min_amount) }} - AED {{ number_format($package->max_amount) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-spinneys-green/10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-5 h-5 text-spinneys-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">Duration</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $package->duration_days }} days</div>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-10 h-10 {{ $package->available_slots > 0 ? 'bg-spinneys-green/10' : 'bg-red-100' }} rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-5 h-5 {{ $package->available_slots > 0 ? 'text-spinneys-green' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">Availability</div>
                                        <div class="text-sm {{ $package->available_slots > 0 ? 'text-spinneys-green' : 'text-red-600' }}">
                                            {{ $package->available_slots }} / {{ $package->total_slots }} slots
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Calculator -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Investment Calculator</h3>
                        <div class="space-y-4" x-data="{
                            amount: {{ $package->min_amount }},
                            rate: {{ $package->daily_interest_rate }},
                            days: {{ $package->duration_days }},
                            get dailyInterest() { return (this.amount * this.rate / 100).toFixed(2); },
                            get totalInterest() { return (this.dailyInterest * this.days).toFixed(2); },
                            get totalReturn() { return (parseFloat(this.amount) + parseFloat(this.totalInterest)).toFixed(2); }
                        }">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Investment Amount: AED <span x-text="parseFloat(amount).toLocaleString()"></span>
                                </label>
                                <input type="range"
                                       x-model="amount"
                                       min="{{ $package->min_amount }}"
                                       max="{{ $package->max_amount }}"
                                       step="100"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>AED {{ number_format($package->min_amount) }}</span>
                                    <span>AED {{ number_format($package->max_amount) }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 pt-4 border-t">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Daily Interest</div>
                                    <div class="text-lg font-bold text-spinneys-green">AED <span x-text="dailyInterest"></span></div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Interest</div>
                                    <div class="text-lg font-bold text-spinneys-gold">AED <span x-text="parseFloat(totalInterest).toLocaleString()"></span></div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Return</div>
                                    <div class="text-lg font-bold text-spinneys-green">AED <span x-text="parseFloat(totalReturn).toLocaleString()"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Investment Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Make Investment</h3>

                        <!-- User Balance -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Your Available Balance</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                AED {{ number_format(Auth::user()->balance, 2) }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('investments.invest', $package) }}">
                            @csrf

                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Investment Amount
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">AED</span>
                                    </div>
                                    <input type="number"
                                           name="amount"
                                           id="amount"
                                           min="{{ $package->min_amount }}"
                                           max="{{ min($package->max_amount, Auth::user()->balance) }}"
                                           step="0.01"
                                           value="{{ old('amount', $package->min_amount) }}"
                                           required
                                           class="pl-7 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-spinneys-green focus:ring-spinneys-green">
                                </div>
                                @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Min: AED {{ number_format($package->min_amount) }} | Max: AED {{ number_format($package->max_amount) }}
                                </p>
                            </div>

                            @if($package->available_slots > 0 && Auth::user()->canInvestInPackage($package))
                                <button type="submit"
                                        class="w-full bg-spinneys-green hover:bg-spinneys-green-700 text-white font-bold py-3 rounded-lg transition shadow-md hover:shadow-lg">
                                    Invest Now
                                </button>
                            @else
                                <div class="text-center py-3 px-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-800 dark:text-red-400">
                                    @if($package->available_slots == 0)
                                        No slots available for this package
                                    @else
                                        You are not eligible to invest in this package
                                    @endif
                                </div>
                            @endif
                        </form>

                        <!-- Important Notes -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Important Notes</h4>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-spinneys-green mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Daily interest is automatically credited at 00:30 UTC
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-spinneys-green mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Principal + total earnings returned on maturity
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-spinneys-green mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Investment receipt generated immediately
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
