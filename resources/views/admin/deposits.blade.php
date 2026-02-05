<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Deposits</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pending deposit approvals</p>
                </div>
            </div>

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pending Deposits</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pendingDeposits as $deposit)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-semibold">{{ $deposit->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $deposit->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">AED {{ number_format($deposit->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $deposit->payment_method)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline-block">
                                                @csrf
                                                <button class="min-w-[90px] inline-flex items-center justify-center px-3 py-1 rounded border border-green-700 bg-green-600 text-white text-xs font-semibold hover:bg-green-700">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="admin_notes" value="Rejected by admin">
                                                <button class="min-w-[90px] inline-flex items-center justify-center px-3 py-1 rounded border border-red-700 bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-6 text-center text-sm text-gray-500" colspan="5">No pending deposits.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
