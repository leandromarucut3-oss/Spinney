<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Withdrawal Receipt</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Request submitted successfully.</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </div>

                    @if(session('success'))
                        <div class="mt-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-6 grid gap-4 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs uppercase text-gray-500">Receipt #</div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">WD-{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase text-gray-500">Date</div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs uppercase text-gray-500">Amount</div>
                                <div class="text-xl font-bold text-gray-900 dark:text-gray-100">@money($withdrawal->amount)</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase text-gray-500">Method</div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Bank Transfer</div>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-500">Bank Details</div>
                            <div class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $withdrawal->account_details }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-500">Notes</div>
                            <div class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $withdrawal->admin_notes ?: '—' }}</div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('withdrawals.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">View Withdrawals</a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
