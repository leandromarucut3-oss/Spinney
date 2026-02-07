<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Referral Program</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Invite friends and earn commissions</p>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-semibold">Your Code:</span> {{ $user->referral_code }}
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="text-sm text-gray-500">Total Referrals</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalReferrals }}</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="text-sm text-gray-500">Total Commission</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">@money($totalCommission)</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="text-sm text-gray-500">Referral Link</div>
                            <div class="mt-2 flex items-center gap-2">
                                <input
                                    id="referral-link"
                                    type="text"
                                    readonly
                                    value="{{ url('/register') }}?ref={{ $user->referral_code }}"
                                    class="flex-1 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md px-2 py-1"
                                />
                                <button
                                    type="button"
                                    id="copy-referral"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-md bg-spinneys-green text-white hover:bg-spinneys-green-700 transition"
                                >
                                    Copy
                                </button>
                            </div>
                            <div id="copy-feedback" class="mt-2 text-xs text-spinneys-green hidden">Copied!</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Referred Users</h3>
                    @if($referredUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tier</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Investments</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Investment Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($referredUsers as $referredUser)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-semibold">{{ $referredUser->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $referredUser->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($referredUser->tier) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $referredUser->investments_count }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">@money($referredUser->active_investments_amount ?? 0)</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $referredUser->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">No referred users yet.</div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Referral Commissions</h3>
                    @if($referrals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Referred User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Signup Bonus</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Commission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($referrals as $referral)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-semibold">{{ $referral->referred?->name ?? '—' }}</div>
                                                <div class="text-xs text-gray-500">{{ $referral->referred?->email ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Level {{ $referral->level }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">@money($referral->signup_bonus)</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">@money($referral->total_commission)</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $referral->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                                    {{ ucfirst($referral->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">No referral commissions yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('copy-referral');
            const input = document.getElementById('referral-link');
            const feedback = document.getElementById('copy-feedback');

            if (!button || !input) {
                return;
            }

            button.addEventListener('click', async () => {
                const text = input.value;
                const originalText = button.textContent;
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        input.select();
                        document.execCommand('copy');
                        input.setSelectionRange(0, 0);
                    }
                    if (feedback) {
                        feedback.classList.remove('hidden');
                        setTimeout(() => feedback.classList.add('hidden'), 1500);
                    }
                    button.textContent = 'Copied!';
                    button.classList.add('bg-spinneys-gold', 'hover:bg-spinneys-gold-700');
                    button.classList.remove('bg-spinneys-green', 'hover:bg-spinneys-green-700');
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-spinneys-gold', 'hover:bg-spinneys-gold-700');
                        button.classList.add('bg-spinneys-green', 'hover:bg-spinneys-green-700');
                    }, 1500);
                } catch (error) {
                    // No-op
                }
            });
        });
    </script>
@endpush
