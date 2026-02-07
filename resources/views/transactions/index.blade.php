<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Transaction History</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review your account credits and debits</p>
                        </div>
                        <a href="{{ route('deposits.index') }}" class="px-4 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 transition-colors">
                            View Deposits
                        </a>
                    </div>

                    <form method="GET" class="mb-6 grid gap-4 md:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="type">Type</label>
                            <select id="type" name="type" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green">
                                <option value="">All Types</option>
                                <option value="deposit" @selected(request('type') === 'deposit')>Deposit</option>
                                <option value="withdrawal" @selected(request('type') === 'withdrawal')>Withdrawal</option>
                                <option value="investment" @selected(request('type') === 'investment')>Investment</option>
                                <option value="daily_interest" @selected(request('type') === 'daily_interest')>Interest Earned</option>
                                <option value="referral_bonus" @selected(request('type') === 'referral_bonus')>Referral Bonus</option>
                                <option value="admin_package_activation" @selected(request('type') === 'admin_package_activation')>Admin Package Activation</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="from">From</label>
                            <input id="from" name="from" type="date" value="{{ request('from') }}" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="to">To</label>
                            <input id="to" name="to" type="date" value="{{ request('to') }}" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green">
                        </div>
                        <div class="flex items-end gap-2">
                            <button class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Filter</button>
                            <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Reset</a>
                        </div>
                    </form>

                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Balance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->created_at->format('M d, Y') }}<br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('h:i A') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                                @if($transaction->isNeutral())
                                                    <span class="text-gray-600">@money($transaction->amount)</span>
                                                @elseif($transaction->isCredit())
                                                    <span class="text-green-700">+ @money($transaction->amount)</span>
                                                @else
                                                    <span class="text-red-700">- @money($transaction->amount)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                @money($transaction->balance_after)
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $transaction->description }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->reference }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                            <span class="font-medium">Total</span>
                            @php
                                $displayTotal = $netTotal ?? 0;
                            @endphp
                            <span class="font-semibold {{ $displayTotal >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                {{ $displayTotal >= 0 ? '+' : '-' }} @money(abs($displayTotal))
                            </span>
                        </div>

                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No transactions yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your account activity will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
