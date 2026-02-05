<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pt-2">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Withdrawal Bank Information</h3>
            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Add your bank details to enable withdrawals.</p>
        </div>

        <div>
            <x-input-label for="bank_name" :value="__('Bank Name')" />
            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" :value="old('bank_name', $user->bank_name)" autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
        </div>

        <div>
            <x-input-label for="bank_account_name" :value="__('Account Holder Name')" />
            <x-text-input id="bank_account_name" name="bank_account_name" type="text" class="mt-1 block w-full" :value="old('bank_account_name', $user->bank_account_name)" autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_account_name')" />
        </div>

        <div>
            <x-input-label for="bank_account_number" :value="__('Account Number')" />
            <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-1 block w-full" :value="old('bank_account_number', $user->bank_account_number)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_account_number')" />
        </div>

        <div>
            <x-input-label for="bank_iban" :value="__('IBAN (optional)')" />
            <x-text-input id="bank_iban" name="bank_iban" type="text" class="mt-1 block w-full" :value="old('bank_iban', $user->bank_iban)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_iban')" />
        </div>

        <div>
            <x-input-label for="bank_swift_code" :value="__('SWIFT/BIC (optional)')" />
            <x-text-input id="bank_swift_code" name="bank_swift_code" type="text" class="mt-1 block w-full" :value="old('bank_swift_code', $user->bank_swift_code)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_swift_code')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
