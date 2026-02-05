<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Request Withdrawal</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Available balance: @money($user->balance)</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        <div class="font-semibold">Bank Information on File</div>
                        <div class="mt-1 text-xs text-blue-800">
                            {{ $user->getBankAccountSummary() }}
                        </div>
                        <div class="mt-2 text-xs text-blue-800">
                            Withdrawal method: Bank transfer to your saved account.
                        </div>
                        <a href="{{ route('profile.edit') }}" class="mt-2 inline-flex text-xs font-semibold text-spinneys-green hover:text-spinneys-green-700">Edit bank details</a>
                    </div>

                    <form method="POST" action="{{ route('withdrawals.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="amount">Amount (@currencyCode)</label>
                            <input id="amount" name="amount" type="number" step="0.01" min="75" max="{{ $user->balance }}" value="{{ old('amount') }}" class="w-full rounded-lg border-gray-300 focus:border-spinneys-green focus:ring-spinneys-green" required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded-lg bg-spinneys-green text-white font-semibold hover:bg-spinneys-green-700">Submit Request</button>
                            <a href="{{ route('withdrawals.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
