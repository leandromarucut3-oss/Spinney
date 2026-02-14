<x-admin-layout>
    @php
        $usersData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'tier' => $user->tier,
                'is_verified' => (bool) $user->is_verified,
                'is_suspended' => (bool) $user->is_suspended,
                'balance' => (float) $user->balance,
                'total_deposits' => (float) ($user->total_deposits ?? 0),
                'total_invested' => (float) ($user->total_invested ?? 0),
                'created_at' => $user->created_at?->format('M d, Y'),
                'last_login_at' => $user->last_login_at?->format('M d, Y H:i'),
                'investments' => $user->investments->map(function ($investment) {
                    return [
                        'id' => $investment->id,
                        'receipt_number' => $investment->receipt_number,
                        'package' => $investment->package?->name,
                        'status' => $investment->status,
                        'amount' => (float) $investment->amount,
                        'daily_interest' => (float) $investment->daily_interest,
                        'total_earned' => (float) $investment->total_earned,
                        'start_date' => $investment->start_date?->format('M d, Y'),
                        'maturity_date' => $investment->maturity_date?->format('M d, Y'),
                    ];
                })->values(),
            ];
        })->values();
    @endphp
    <div
        class="py-6"
        x-data="{
            usersData: @js($usersData),
            selectedUser: null,
            formatMoney(value) {
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value ?? 0);
            },
            openUser(id) {
                this.selectedUser = this.usersData.find(user => user.id === id) || null;
                this.$dispatch('open-modal', 'user-details');
            }
        }"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Users</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">All registered users</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Registered Users</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Deposits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Invested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="cursor-pointer hover:bg-gray-50" role="button" tabindex="0" x-on:click="openUser({{ $user->id }})" x-on:keydown.enter.prevent="openUser({{ $user->id }})">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-semibold">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">AED {{ number_format($user->balance, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">AED {{ number_format($user->total_deposits ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">AED {{ number_format($user->total_invested ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-modal name="user-details" maxWidth="2xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900" x-text="selectedUser ? selectedUser.name : 'User details'"></h2>
                <p class="text-sm text-gray-500" x-text="selectedUser ? selectedUser.email : ''"></p>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Available Balance</div>
                        <div class="text-lg font-semibold text-gray-900" x-text="selectedUser ? 'AED ' + formatMoney(selectedUser.balance) : 'AED 0.00'"></div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Total Deposits</div>
                        <div class="text-lg font-semibold text-gray-900" x-text="selectedUser ? 'AED ' + formatMoney(selectedUser.total_deposits) : 'AED 0.00'"></div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Total Invested</div>
                        <div class="text-lg font-semibold text-gray-900" x-text="selectedUser ? 'AED ' + formatMoney(selectedUser.total_invested) : 'AED 0.00'"></div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Username</div>
                        <div class="text-sm font-semibold text-gray-900" x-text="selectedUser ? selectedUser.username || '—' : '—'"></div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Tier</div>
                        <div class="text-sm font-semibold text-gray-900" x-text="selectedUser ? selectedUser.tier || '—' : '—'"></div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="text-sm font-semibold text-gray-900" x-text="selectedUser ? (selectedUser.is_suspended ? 'Suspended' : (selectedUser.is_verified ? 'Verified' : 'Pending')) : '—'"></div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Joined</div>
                        <div class="text-sm font-semibold text-gray-900" x-text="selectedUser ? selectedUser.created_at || '—' : '—'"></div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500">Last Login</div>
                        <div class="text-sm font-semibold text-gray-900" x-text="selectedUser ? selectedUser.last_login_at || '—' : '—'"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-900">Investments</h3>
                        <span class="text-xs text-gray-500" x-text="selectedUser ? selectedUser.investments.length + ' total' : '0 total'"></span>
                    </div>
                    <template x-if="selectedUser && selectedUser.investments.length">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Interest</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earned</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maturity</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="investment in selectedUser.investments" :key="investment.id">
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="investment.receipt_number"></td>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="investment.package || '—'"></td>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="'AED ' + formatMoney(investment.amount)"></td>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="'AED ' + formatMoney(investment.daily_interest)"></td>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="'AED ' + formatMoney(investment.total_earned)"></td>
                                            <td class="px-4 py-2 text-sm text-gray-500" x-text="investment.start_date || '—'"></td>
                                            <td class="px-4 py-2 text-sm text-gray-500" x-text="investment.maturity_date || '—'"></td>
                                            <td class="px-4 py-2 text-sm text-gray-900" x-text="investment.status"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                    <template x-if="selectedUser && !selectedUser.investments.length">
                        <div class="text-sm text-gray-500">No investments yet.</div>
                    </template>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="button" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" x-on:click="$dispatch('close-modal', 'user-details')">Close</button>
            </div>
        </x-modal>
    </div>
</x-admin-layout>
