<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h2>
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

            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $users->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-sm text-gray-500">Admin Funds</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">AED {{ number_format(auth()->user()->balance, 2) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-sm text-gray-500">Pending Deposits</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingDeposits->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-sm text-gray-500">Pending Withdrawals</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingWithdrawals->count() }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Transfer Admin Funds</h3>
                <form method="POST" action="{{ route('admin.transfers.store') }}" class="grid gap-4 md:grid-cols-4">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="user_id">Select User</label>
                        <select id="user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                            <option value="">Choose user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="amount">Amount (AED)</label>
                        <input id="amount" name="amount" type="number" step="0.01" min="1" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="message">Note (optional)</label>
                        <input id="message" name="message" type="text" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" placeholder="Reason or note">
                    </div>
                    <div class="md:col-span-4">
                        <button class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Transfer Funds</button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-sm text-gray-500">Total Approved Deposits</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    AED {{ number_format($users->sum('total_deposits'), 2) }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
