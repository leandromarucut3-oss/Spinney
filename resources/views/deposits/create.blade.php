<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <style>
                @keyframes float-shadow {
                    0%, 100% {
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15), 0 5px 15px rgba(0, 0, 0, 0.1);
                        transform: perspective(1000px) rotateX(0deg);
                    }
                    50% {
                        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25), 0 10px 30px rgba(0, 0, 0, 0.15);
                        transform: perspective(1000px) rotateX(2deg);
                    }
                }

                .floating-image img {
                    animation: float-shadow 4s ease-in-out infinite;
                    transform-style: preserve-3d;
                }

                .floating-image:hover img {
                    animation-play-state: paused;
                }

                #depositModal.is-active {
                    display: block !important;
                }

                #depositModal.is-active > div {
                    display: flex;
                }
            </style>

            @php
                $activeInvestments = Auth::user()->investments()->where('status', 'active')->get();
                $activeTotal = $activeInvestments->sum('amount');
                $activePackageIds = $activeInvestments->pluck('package_id')->filter()->unique()->values();
            @endphp

            <!-- Investment Packages Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buy Shares</h2>
                    <button type="button" data-open-faq-modal class="inline-flex items-center justify-center w-6 h-6 rounded-full border border-gray-300 text-gray-600 hover:text-spinneys-green hover:border-spinneys-green transition" aria-controls="faq-modal" aria-expanded="false">
                        <span class="text-xs font-bold">i</span>
                    </button>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Choose a package to start buying shares and set your investment amount</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($packages as $package)
                        <button type="button" data-open-deposit-modal data-package-id="{{ $package->id }}" data-min="{{ $package->min_amount }}" data-max="{{ $package->max_amount }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-2 border-gray-200 dark:border-gray-700 hover:border-spinneys-green transition-colors p-4 flex items-center justify-center">
                            @if($package->image)
                                <img src="{{ asset($package->image) }}" alt="{{ $package->name }}" class="w-full h-auto object-contain">
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Form Modal (inside layout to avoid DOM issues) -->
    <div id="depositModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" data-close-deposit-modal></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buy Shares</h2>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Submit a deposit request with proof of payment for admin approval</p>
                        </div>
                        <button type="button" data-close-deposit-modal class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('deposits.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="package_id" id="package_id" value="">

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (AED)</label>
                            <input type="number" step="0.01" min="10" name="amount" id="amount" value="{{ old('amount') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-spinneys-green focus:ring-spinneys-green dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="amount-range">Minimum deposit: AED 10</p>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                            <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-spinneys-green focus:ring-spinneys-green dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select payment method</option>
                                <option value="account_balance" {{ old('payment_method') == 'account_balance' ? 'selected' : '' }}>Use Available Balance</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="cryptocurrency" {{ old('payment_method') == 'cryptocurrency' ? 'selected' : '' }}>Cryptocurrency</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Reference -->
                        <div>
                            <label for="transaction_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Reference (Optional)</label>
                            <input type="text" name="transaction_reference" id="transaction_reference" value="{{ old('transaction_reference') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-spinneys-green focus:ring-spinneys-green dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('transaction_reference')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter your transaction ID or reference number</p>
                        </div>

                        <!-- Proof of Payment -->
                        <div>
                            <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proof of Payment (Optional)</label>
                            <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-spinneys-green file:text-white hover:file:bg-spinneys-green-700">
                            @error('proof_of_payment')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a screenshot or receipt (Max: 5MB, Images only)</p>
                        </div>

                        <!-- Important Note -->
                        <div class="bg-spinneys-gold bg-opacity-10 border-l-4 border-spinneys-gold p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-spinneys-gold" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <strong>Important:</strong> Your deposit will be reviewed by our admin team. Once approved, the funds will be added to your account balance.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 mt-6">
                            <button type="button" data-close-deposit-modal class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                Cancel
                            </button>
                            <button type="button" id="upgrade-button" class="hidden px-6 py-2 bg-spinneys-gold text-white font-medium rounded-lg hover:bg-spinneys-gold-700 transition-colors">
                                Upgrade to Selected Package
                            </button>
                            <button type="submit" class="px-6 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-spinneys-green transition-colors">
                                Submit Deposit Request
                            </button>
                        </div>
                    </form>
                    <form id="upgrade-form" method="POST" action="#" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Modal -->
    <div id="faqModal" class="hidden fixed inset-0 z-[10000] overflow-y-auto" aria-labelledby="faq-title" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" data-close-faq-modal></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 id="faq-title" class="text-2xl font-bold text-gray-900 dark:text-gray-100">FAQs</h2>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">How buying shares works</p>
                        </div>
                        <button type="button" data-close-faq-modal class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">What shares am I buying?</div>
                            <div>Your funds are deployed into two focus stocks: Spinneys and Ayala Corporation.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">How does the system work?</div>
                            <div>An automated model analyzes market conditions and executes buy/sell decisions within defined risk controls to target the daily rate shown in your package.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">Why do I see a fixed daily rate?</div>
                            <div>Each package is built around a target daily rate. The system actively manages positions to pursue that target based on the selected package.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">Is the daily rate guaranteed?</div>
                            <div>The rate shown is a target. Performance depends on market conditions and the package terms.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">How is risk managed?</div>
                            <div>We apply predefined risk controls and position sizing rules to maintain disciplined exposure within each package.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">How are trades executed?</div>
                            <div>Orders are routed based on liquidity and pricing conditions, with automated checks to reduce slippage.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">How often is performance reviewed?</div>
                            <div>Portfolio performance is monitored continuously, with regular internal reviews against package targets.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">Are there any fees?</div>
                            <div>Any applicable fees are disclosed in the package terms before you confirm a purchase.</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">What happens at maturity?</div>
                            <div>The investment completes at maturity and transitions based on the package terms shown on your dashboard.</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex justify-end">
                    <button type="button" data-close-faq-modal class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDepositModal() {
            const modal = document.getElementById('depositModal');
            if (!modal) {
                return;
            }

            modal.classList.add('is-active');
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeDepositModal() {
            const modal = document.getElementById('depositModal');
            if (!modal) {
                return;
            }

            modal.classList.remove('is-active');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        function openFaqModal() {
            const modal = document.getElementById('faqModal');
            if (!modal) {
                return;
            }
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeFaqModal() {
            const modal = document.getElementById('faqModal');
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const activeTotal = {{ $activeTotal }};
            const activePackageIds = @json($activePackageIds);
            const upgradeButton = document.getElementById('upgrade-button');
            const upgradeForm = document.getElementById('upgrade-form');

            document.querySelectorAll('[data-open-faq-modal]').forEach(function (button) {
                button.addEventListener('click', openFaqModal);
            });
            document.querySelectorAll('[data-close-faq-modal]').forEach(function (button) {
                button.addEventListener('click', closeFaqModal);
            });

            document.querySelectorAll('[data-open-deposit-modal]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const packageId = button.dataset.packageId || '';
                    const min = parseFloat(button.dataset.min || '10');
                    const max = parseFloat(button.dataset.max || '0');
                    const amountInput = document.getElementById('amount');
                    const rangeText = document.getElementById('amount-range');
                    const packageInput = document.getElementById('package_id');

                    if (packageInput) {
                        packageInput.value = packageId;
                    }

                    if (amountInput) {
                        amountInput.min = min;
                        if (max > 0) {
                            amountInput.max = max;
                        } else {
                            amountInput.removeAttribute('max');
                        }
                        amountInput.value = min;
                    }

                    if (rangeText) {
                        rangeText.textContent = max > 0
                            ? `Minimum deposit: AED ${min.toLocaleString()} (Max: AED ${max.toLocaleString()})`
                            : `Minimum deposit: AED ${min.toLocaleString()}`;
                    }

                    if (upgradeButton && upgradeForm) {
                        const eligible = activeTotal > 0
                            && activeTotal >= min
                            && (max === 0 || activeTotal <= max)
                            && !activePackageIds.includes(parseInt(packageId, 10));

                        if (eligible) {
                            upgradeButton.classList.remove('hidden');
                            upgradeForm.setAttribute('action', `{{ url('/investments/upgrade') }}/${packageId}`);
                        } else {
                            upgradeButton.classList.add('hidden');
                            upgradeForm.setAttribute('action', '#');
                        }
                    }

                    openDepositModal();
                });
            });

            document.querySelectorAll('[data-close-deposit-modal]').forEach(function (button) {
                button.addEventListener('click', closeDepositModal);
            });

            if (upgradeButton && upgradeForm) {
                upgradeButton.addEventListener('click', function () {
                    if (upgradeForm.getAttribute('action') && upgradeForm.getAttribute('action') !== '#') {
                        upgradeForm.submit();
                    }
                });
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDepositModal();
                closeFaqModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
