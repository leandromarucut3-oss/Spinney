<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-lg p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Welcome to Spinneys!</h2>
                <p class="text-sm text-gray-600">Please sign in to continue.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 text-sm">
                    <div class="flex flex-wrap items-center gap-3">
                        @if (Route::has('password.request'))
                            <a class="underline text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a class="underline text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                                {{ __('Create account') }}
                            </a>
                        @endif
                    </div>

                    <label class="inline-flex items-center text-sm text-gray-600">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-spinneys-green shadow-sm focus:ring-spinneys-green" name="remember">
                        <span class="ms-2">{{ __('Remember me') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white/95 dark:bg-gray-800/95 rounded-2xl shadow-lg p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Preferred currency:</span>
                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="preferred_currency" value="AED" class="peer sr-only" {{ old('preferred_currency', 'AED') === 'AED' ? 'checked' : '' }}>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border border-gray-300 peer-checked:bg-spinneys-green peer-checked:text-white peer-checked:border-spinneys-green">AED</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="preferred_currency" value="PHP" class="peer sr-only" {{ old('preferred_currency') === 'PHP' ? 'checked' : '' }}>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border border-gray-300 peer-checked:bg-spinneys-green peer-checked:text-white peer-checked:border-spinneys-green">PHP</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full rounded-xl bg-white/95 text-gray-900 font-semibold py-3 shadow-lg border border-gray-200 hover:bg-white">
                {{ __('Proceed') }}
            </button>
        </div>
    </form>
</x-guest-layout>
