<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Deposit History</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track all your deposit requests and their status</p>
                        </div>
                        <a href="{{ route('deposits.create') }}" class="px-4 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 transition-colors">
                            Make Deposit
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Deposits Table -->
                    @if($deposits->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Proof</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($deposits as $deposit)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $deposit->created_at->format('M d, Y') }}<br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $deposit->created_at->format('h:i A') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                ${{ number_format($deposit->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ ucwords(str_replace('_', ' ', $deposit->payment_method)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $deposit->transaction_reference ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($deposit->status === 'approved')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Approved
                                                    </span>
                                                @elseif($deposit->status === 'pending')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($deposit->proof_of_payment)
                                                    <a href="{{ Storage::url($deposit->proof_of_payment) }}" target="_blank" class="text-spinneys-green hover:text-spinneys-green-700 font-medium">
                                                        View Proof
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">No proof</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $deposits->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No deposits yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by making your first deposit.</p>
                            <div class="mt-6">
                                <a href="{{ route('deposits.create') }}" class="inline-flex items-center px-4 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 transition-colors">
                                    Make Your First Deposit
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
