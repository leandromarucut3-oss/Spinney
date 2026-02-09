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
            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-semibold">Please fix the highlighted errors.</div>
                    <ul class="mt-2 list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Activate Package for User</h3>
                <form method="POST" action="{{ route('admin.investments.activate') }}" class="grid gap-4 md:grid-cols-4" id="admin-activate-form">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="activate_user_id">Select User</label>
                        <select id="activate_user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                            <option value="">Choose user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="activate_package_id">Select Package</label>
                        <select id="activate_package_id" name="package_id" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                            <option value="">Choose package</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" data-min="{{ $package->min_amount }}" data-max="{{ $package->max_amount }}">
                                    {{ $package->name }} (AED {{ number_format($package->min_amount, 0) }} - AED {{ number_format($package->max_amount, 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="activate_amount">Amount (AED)</label>
                        <input id="activate_amount" name="amount" type="number" step="0.01" min="1" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                        <div class="mt-1 text-xs text-gray-500" id="activate-range">Select a package to see the valid range.</div>
                    </div>
                    <div class="md:col-span-4">
                        <button class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Activate Package</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Manual Daily Interest</h3>
                <p class="text-sm text-gray-500 mb-4">Use this to backfill missed daily interest. Limit 31 days per run.</p>
                <form method="POST" action="{{ route('admin.interest.backfill') }}" class="grid gap-4 md:grid-cols-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="interest_from_date">From</label>
                        <input id="interest_from_date" name="from_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="interest_to_date">To</label>
                        <input id="interest_to_date" name="to_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="interest_user_id">User (optional)</label>
                        <select id="interest_user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green">
                            <option value="">All users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="interest_investment_id">Investment ID (optional)</label>
                        <input id="interest_investment_id" name="investment_id" type="number" min="1" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" placeholder="e.g. 1024">
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="interest_dry_run" name="dry_run" type="checkbox" value="1" class="rounded border-gray-300 text-spinneys-green focus:ring-spinneys-green">
                        <label class="text-sm text-gray-700" for="interest_dry_run">Dry run (no balance changes)</label>
                    </div>
                    <div class="md:col-span-4">
                        <button class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Run Manual Interest</button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const packageSelect = document.getElementById('activate_package_id');
        const amountInput = document.getElementById('activate_amount');
        const rangeText = document.getElementById('activate-range');

        if (!packageSelect || !amountInput || !rangeText) {
            return;
        }

        const updateRange = () => {
            const selected = packageSelect.options[packageSelect.selectedIndex];
            const min = selected?.dataset?.min;
            const max = selected?.dataset?.max;

            if (!min || !max) {
                amountInput.removeAttribute('min');
                amountInput.removeAttribute('max');
                rangeText.textContent = 'Select a package to see the valid range.';
                return;
            }

            amountInput.setAttribute('min', min);
            amountInput.setAttribute('max', max);
            rangeText.textContent = `Valid range: AED ${Number(min).toLocaleString()} - AED ${Number(max).toLocaleString()}`;
        };

        packageSelect.addEventListener('change', updateRange);
        updateRange();
    });
</script>
@endpush
