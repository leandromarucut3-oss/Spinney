<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Payment QR Code / Image Section -->
            <div class="mb-8 max-w-7xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 text-center">Buy Shares - Payment Information</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">Scan or click any QR code below to make your payment</p>

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- First Payment Option -->
                        <div class="text-center">
                            <button type="button" onclick="openDepositModal()" class="inline-block floating-image">
                                <img src="{{ asset('att.xBFsDBd84k7Emg9IXZ736suHeKlJ9jzrsZAsAcv1ndQ.jpeg') }}"
                                     alt="Payment Information"
                                     class="max-w-full h-auto rounded-lg shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer border-2 border-gray-200 dark:border-gray-700 hover:border-spinneys-gold mx-auto hover:scale-105"
                                     style="max-height: 500px;">
                            </button>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Payment Option 1</p>
                        </div>

                        <!-- Second Payment Option -->
                        <div class="text-center">
                            <button type="button" onclick="openDepositModal()" class="inline-block floating-image">
                                <img src="{{ asset('att.HZqOIC2SXNJF-eMhtrFy9xkTNQQM3NChxilUyiP0aSk.jpeg') }}"
                                     alt="Alternative Payment Information"
                                     class="max-w-full h-auto rounded-lg shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer border-2 border-gray-200 dark:border-gray-700 hover:border-spinneys-gold mx-auto hover:scale-105"
                                     style="max-height: 500px;">
                            </button>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Payment Option 2</p>
                        </div>

                        <!-- Third Payment Option -->
                        <div class="text-center">
                            <button type="button" onclick="openDepositModal()" class="inline-block floating-image">
                                <img src="{{ asset('att._SOR9a9qW-PHTNrT3zKo6L_RmMYae3z_sHLMVJ6NLwM.jpeg') }}"
                                     alt="Third Payment Information"
                                     class="max-w-full h-auto rounded-lg shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer border-2 border-gray-200 dark:border-gray-700 hover:border-spinneys-gold mx-auto hover:scale-105"
                                     style="max-height: 500px;">
                            </button>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Payment Option 3</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-6 text-center">Click any image to proceed with deposit</p>
                </div>
            </div>

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
            </style>

            <!-- Investment Packages Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Available Investment Packages</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Review our investment packages before depositing to know how much you need</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($packages as $package)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-2 border-gray-200 dark:border-gray-700 hover:border-spinneys-green transition-colors">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $package->name }}</h3>
                                <div class="text-3xl font-bold text-spinneys-green mb-4">
                                    {{ number_format($package->interest_rate, 1) }}%
                                    <span class="text-sm text-gray-600 dark:text-gray-400">daily</span>
                                </div>
                                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                    <p><strong class="text-gray-900 dark:text-gray-100">Min:</strong> ${{ number_format($package->minimum_investment, 0) }}</p>
                                    <p><strong class="text-gray-900 dark:text-gray-100">Max:</strong> ${{ number_format($package->maximum_investment, 0) }}</p>
                                    <p><strong class="text-gray-900 dark:text-gray-100">Duration:</strong> ${{ $package->duration_days }} days</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDepositModal() {
            const modal = document.getElementById('depositModal');
            console.log('Opening modal', modal);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDepositModal() {
            const modal = document.getElementById('depositModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDepositModal();
            }
        });
    </script>
    @endpush
</x-app-layout>

<!-- Deposit Form Modal - OUTSIDE APP LAYOUT -->
<div id="depositModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDepositModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Make a Deposit</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Submit a deposit request with proof of payment for admin approval</p>
                    </div>
                    <button type="button" onclick="closeDepositModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('deposits.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount ($)</label>
                    <input type="number" step="0.01" min="10" name="amount" id="amount" value="{{ old('amount') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-spinneys-green focus:ring-spinneys-green dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum deposit: $10</p>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-spinneys-green focus:ring-spinneys-green dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select payment method</option>
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
                    <button type="button" onclick="closeDepositModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-spinneys-green text-white font-medium rounded-lg hover:bg-spinneys-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-spinneys-green transition-colors">
                        Submit Deposit Request
                    </button>
                </div>
                </form>
        </div>
    </div>
</div>
