<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Balance Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Main Balance -->
                <div class="bg-gradient-to-br from-spinneys-green to-spinneys-green-800 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-medium opacity-90">Available Balance</div>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/40 text-xs font-semibold opacity-90">@currencyCode</span>
                    </div>
                    <div class="text-4xl font-bold mb-2">@money(Auth::user()->balance)</div>
                    @php
                        $hasBankInfo = Auth::user()->hasBankInfo();
                    @endphp
                    <div class="flex space-x-3">
                        <a href="{{ route('deposits.create') }}" class="flex-1 text-center bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs font-semibold transition">
                            Buy Shares
                        </a>
                        @if($hasBankInfo)
                            <a href="{{ route('withdrawals.create') }}" class="flex-1 text-center bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs font-semibold transition">
                                Withdraw
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="flex-1 text-center bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg text-xs font-semibold transition">
                                Add Bank Info
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Total Invested -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Invested</div>
                        <div class="w-10 h-10 bg-spinneys-green/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-spinneys-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                        @money(Auth::user()->investments()->where('status', 'active')->sum('amount'))
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ Auth::user()->investments()->where('status', 'active')->count() }} active investment(s)
                    </div>
                </div>

                <!-- Total Earnings -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Earnings</div>
                        <div class="w-10 h-10 bg-spinneys-gold/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-spinneys-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                        @money(Auth::user()->interestLogs()->where('status', 'processed')->sum('interest_amount'))
                    </div>
                    <div class="text-xs text-gray-500">
                        Interest Earned
                    </div>
                </div>
            </div>

            <!-- Stock Market Charts (Swipe) -->
            <div class="relative">
                <div class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-4 -mx-2 px-2 [scrollbar-width:none] [-ms-overflow-style:none]" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <!-- Spinneys Stock Market -->
                    <div id="stock-spinneys" class="min-w-full snap-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Spinneys Stock Market</h3>
                                <p class="text-sm text-gray-500">Yahoo Finance data</p>
                            </div>
                            <a href="https://finance.yahoo.com/quote/SPINNEYS.AE/chart?p=SPINNEYS.AE" target="_blank" rel="noopener" class="text-xs text-spinneys-green hover:text-spinneys-green-700 font-semibold">
                                Open Yahoo Finance
                            </a>
                        </div>
                        <div class="h-[260px]">
                            <canvas id="spinneysChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                    <!-- Ayala Corporation Stock Price -->
                    <div id="stock-ayala" class="min-w-full snap-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ayala Corporation Stock Price</h3>
                                <p class="text-sm text-gray-500">Yahoo Finance data</p>
                            </div>
                            <a href="https://finance.yahoo.com/quote/AYALY/chart?p=AYALY" target="_blank" rel="noopener" class="text-xs text-spinneys-green hover:text-spinneys-green-700 font-semibold">
                                Open Yahoo Finance
                            </a>
                        </div>
                        <div class="h-[260px]">
                            <canvas id="ayalaChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-2">
                    <a href="#stock-spinneys" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-spinneys-green transition" aria-label="Spinneys stock"></a>
                    <a href="#stock-ayala" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-spinneys-green transition" aria-label="Ayala stock"></a>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Active Investments -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Active Investments</h3>
                            <a href="{{ route('investments.index') }}" class="text-sm text-spinneys-green hover:text-spinneys-green-700 font-medium">View All</a>
                        </div>
                        <div class="p-6">
                            @php
                                $activeInvestments = Auth::user()->investments()->where('status', 'active')->with('package')->latest()->take(3)->get();
                            @endphp
                            @forelse($activeInvestments as $investment)
                            <div class="mb-4 last:mb-0 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $investment->package->name }}</div>
                                        <div class="text-sm text-gray-500">#{{ $investment->receipt_number }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-gray-900 dark:text-white">@money($investment->amount)</div>
                                        <div class="text-xs text-spinneys-green">{{ $investment->package->daily_interest_rate }}% daily</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <div>
                                        <div class="text-xs text-gray-500">Start Date</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $investment->start_date->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Maturity</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $investment->maturity_date->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Earned</div>
                                        <div class="text-sm font-medium text-spinneys-gold">@money($investment->total_earned)</div>
                                    </div>
                                </div>
                                <!-- Progress Bar -->
                                @php
                                    $totalDays = $investment->duration_days;
                                    $daysPassed = $investment->start_date->diffInDays(now());
                                    $progress = min(100, ($daysPassed / $totalDays) * 100);
                                @endphp
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $daysPassed }} / {{ $totalDays }} days</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-spinneys-green h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">No active investments yet</p>
                                <a href="{{ route('investments.packages') }}" class="inline-flex items-center px-4 py-2 bg-spinneys-green text-white rounded-lg hover:bg-spinneys-green-700 transition">
                                    Browse Investment Packages
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Referral Stats & Recent Transactions -->
                <div class="space-y-6">
                    <!-- Referral Stats -->
                    <div class="bg-gradient-to-br from-spinneys-gold to-spinneys-gold-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Referral Earnings</h3>
                            <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-bold mb-3">
                            @money(Auth::user()->referrals()->sum('total_commission'))
                        </div>
                        <div class="text-sm opacity-90 mb-4">
                            From {{ Auth::user()->referredUsers()->count() }} referred user(s)
                        </div>
                        <a href="{{ route('referrals.index') }}" class="block w-full text-center bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold transition">
                            View Referrals
                        </a>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity</h3>
                        </div>
                        <div class="p-6">
                            @php
                                $recentTransactions = Auth::user()->transactions()->latest()->take(5)->get();
                            @endphp
                            @forelse($recentTransactions as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                        {{ $transaction->isCredit() ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                                        <svg class="w-4 h-4 {{ $transaction->isCredit() ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->isCredit())
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-semibold {{ $transaction->isCredit() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->isCredit() ? '+' : '-' }} @money($transaction->amount)
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-6 text-gray-500">
                                No transactions yet
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        const createStockChart = async (canvasId, symbol) => {
            const canvas = document.getElementById(canvasId);
            if (!canvas) {
                return;
            }

            try {
                const response = await fetch(`/api/stock-data?symbol=${encodeURIComponent(symbol)}`, {
                    credentials: 'same-origin'
                });
                const data = await response.json();

                if (!data || !Array.isArray(data.series)) {
                    throw new Error('No chart data');
                }

                const labels = data.series.map(point => {
                    const date = new Date(point.x);
                    return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                });
                const values = data.series.map(point => point.y);

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: symbol,
                            data: values,
                            borderColor: '#0B7D3B',
                            backgroundColor: 'rgba(11, 125, 59, 0.08)',
                            borderWidth: 2,
                            pointRadius: 0,
                            tension: 0.25,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: (context) => `${symbol}: ${context.parsed.y}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { maxTicksLimit: 6 }
                            },
                            y: {
                                grid: { color: 'rgba(148, 163, 184, 0.2)' },
                                ticks: { maxTicksLimit: 5 }
                            }
                        }
                    }
                });
            } catch (error) {
                canvas.parentElement.innerHTML = '<div class="h-full flex items-center justify-center text-sm text-gray-500">Unable to load chart data.</div>';
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            createStockChart('spinneysChart', 'SPINNEYS.AE');
            createStockChart('ayalaChart', 'AYALY');
        });
    </script>
@endpush
