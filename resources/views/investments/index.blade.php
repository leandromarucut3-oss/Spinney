<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">My Investments</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track your active and past investments</p>
                        </div>
                        <a href="{{ route('investments.packages') }}" class="px-4 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 transition-colors">
                            Browse Packages
                        </a>
                    </div>

                    <div id="upgrade" class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Upgrade Investments</div>
                                <div class="text-xs text-gray-600">Combine all active investments into a single package.</div>
                            </div>
                            <div class="text-sm text-gray-700">
                                Total Active: <span class="font-semibold">@money(auth()->user()->investments()->where('status', 'active')->sum('amount'))</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('investments.upgrade', $packages->first()) }}" class="mt-4 grid gap-3 md:grid-cols-[1fr_auto]" id="upgrade-form">
                            @csrf
                            <select id="upgrade-package" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green">
                                @foreach($packages as $package)
                                    <option value="{{ route('investments.upgrade', $package) }}">
                                        {{ $package->name }} (AED {{ number_format($package->min_amount) }} - AED {{ number_format($package->max_amount) }})
                                    </option>
                                @endforeach
                            </select>
                            <button class="px-4 py-2 rounded-lg bg-spinneys-gold text-white font-semibold hover:bg-spinneys-gold-700">Upgrade</button>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($investments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daily Interest</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Return</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dates</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($investments as $investment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-semibold">{{ $investment->package?->name ?? 'Package' }}</div>
                                                <div class="text-xs text-gray-500">#{{ $investment->receipt_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                @money($investment->amount)
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                @money($investment->daily_interest)
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                @money($investment->total_return)
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $status = $investment->status;
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $status === 'active' ? 'bg-green-100 text-green-800' : ($status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700') }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                <div>Start: {{ $investment->start_date?->format('M d, Y') }}</div>
                                                <div>Maturity: {{ $investment->maturity_date?->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">Earned: @money($investment->total_earned)</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $investments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No investments yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by choosing a package that fits your goals.</p>
                            <div class="mt-6">
                                <a href="{{ route('investments.packages') }}" class="inline-flex items-center px-4 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 transition-colors">
                                    Browse Packages
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('upgrade-form');
            const select = document.getElementById('upgrade-package');
            if (!form || !select) {
                return;
            }

            select.addEventListener('change', function () {
                form.setAttribute('action', select.value);
            });
        });
    </script>
    @endpush
</x-app-layout>
