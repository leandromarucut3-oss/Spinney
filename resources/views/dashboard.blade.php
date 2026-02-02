<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Balance Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Main Balance -->
                <div class="bg-gradient-to-br from-spinneys-green to-spinneys-green-800 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-medium opacity-90">Available Balance</div>
                        <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold mb-2">${{ number_format(Auth::user()->balance, 2) }}</div>
                    <div class="flex space-x-3">
                        <a href="{{ route('deposits.create') }}" class="flex-1 text-center bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs font-semibold transition">
                            Buy Shares
                        </a>
                        <a href="{{ route('withdrawals.create') }}" class="flex-1 text-center bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs font-semibold transition">
                            Withdraw
                        </a>
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
                        ${{ number_format(Auth::user()->investments()->where('status', 'active')->sum('amount'), 2) }}
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
                        ${{ number_format(Auth::user()->investments()->sum('total_earned'), 2) }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Lifetime interest earned
                    </div>
                </div>
            </div>

            <!-- Live Stock Market Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Spinneys Stock Market</h3>
                        <p class="text-sm text-gray-500">Real-time candlestick chart • Updates every 5 minutes</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white" id="current-price">--</div>
                        <div class="text-sm" id="price-change">
                            <span class="text-gray-500">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="stock-chart" class="w-full" style="height: 400px;"></div>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                let stockChart;
                let updateInterval;

                async function fetchStockData() {
                    try {
                        const response = await fetch('/api/stock-data', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        console.log('Stock data received:', data);
                        return data;
                    } catch (error) {
                        console.error('Error fetching stock data:', error);
                        return null;
                    }
                }

                function updatePriceDisplay(currentPrice, change, changePercent) {
                    const priceElement = document.getElementById('current-price');
                    const changeElement = document.getElementById('price-change');

                    priceElement.textContent = `$${currentPrice.toFixed(2)}`;

                    const isPositive = change >= 0;
                    const arrow = isPositive ? '↑' : '↓';
                    const colorClass = isPositive ? 'text-green-600' : 'text-red-600';

                    changeElement.innerHTML = `<span class="${colorClass} font-semibold">${arrow} $${Math.abs(change).toFixed(2)} (${changePercent.toFixed(2)}%)</span>`;
                }

                async function initChart() {
                    console.log('Initializing stock chart...');
                    const data = await fetchStockData();

                    if (!data || !data.candlesticks || data.candlesticks.length === 0) {
                        console.error('No stock data available');
                        document.getElementById('price-change').innerHTML = '<span class="text-red-600">Error loading chart data</span>';
                        return;
                    }

                    console.log(`Loaded ${data.candlesticks.length} candlesticks`);

                    const options = {
                        series: [{
                            name: 'Spinneys',
                            data: data.candlesticks
                        }],
                        chart: {
                            type: 'candlestick',
                            height: 400,
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: true,
                                    zoom: true,
                                    zoomin: true,
                                    zoomout: true,
                                    pan: true,
                                    reset: true
                                }
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        title: {
                            text: 'SPINNEYS - 5 Minute Intervals',
                            align: 'left',
                            style: {
                                color: document.documentElement.classList.contains('dark') ? '#fff' : '#111'
                            }
                        },
                        plotOptions: {
                            candlestick: {
                                colors: {
                                    upward: '#10b981',
                                    downward: '#ef4444'
                                },
                                wick: {
                                    useFillColor: true
                                }
                            }
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                style: {
                                    colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                                }
                            }
                        },
                        yaxis: {
                            tooltip: {
                                enabled: true
                            },
                            labels: {
                                formatter: function(value) {
                                    return '$' + value.toFixed(2);
                                },
                                style: {
                                    colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                                }
                            }
                        },
                        grid: {
                            borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        tooltip: {
                            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        }
                    };

                    stockChart = new ApexCharts(document.querySelector("#stock-chart"), options);
                    stockChart.render();

                    // Update price display
                    updatePriceDisplay(data.currentPrice, data.change, data.changePercent);

                    // Set up auto-refresh every 5 minutes
                    updateInterval = setInterval(async () => {
                        const newData = await fetchStockData();
                        if (newData) {
                            stockChart.updateSeries([{
                                data: newData.candlesticks
                            }]);
                            updatePriceDisplay(newData.currentPrice, newData.change, newData.changePercent);
                        }
                    }, 5 * 60 * 1000); // 5 minutes
                }

                // Initialize chart when page loads
                document.addEventListener('DOMContentLoaded', initChart);

                // Clean up interval when page unloads
                window.addEventListener('beforeunload', () => {
                    if (updateInterval) {
                        clearInterval(updateInterval);
                    }
                });
            </script>
            @endpush

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
                                        <div class="font-bold text-gray-900 dark:text-white">${{ number_format($investment->amount, 2) }}</div>
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
                                        <div class="text-sm font-medium text-spinneys-gold">${{ number_format($investment->total_earned, 2) }}</div>
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
                            ${{ number_format(Auth::user()->referrals()->sum('total_commission'), 2) }}
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
                                    {{ $transaction->isCredit() ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
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
