<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Error Alert for Suspension/General Errors -->
    @if ($errors->any() && $errors->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
            <div class="flex items-start gap-3">
                <div class="text-red-600 dark:text-red-400 text-2xl">⚠️</div>
                <div>
                    <h3 class="font-semibold text-red-800 dark:text-red-200">Account Suspended</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                        {{ $errors->first('error') }}
                    </p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                        If you believe this is a mistake, please contact your administrator.
                    </p>
                </div>
            </div>
        </div>
    @elseif ($errors->any())
        <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg">
            <div class="flex items-start gap-3">
                <div class="text-yellow-600 dark:text-yellow-400 text-2xl">⚠️</div>
                <div>
                    <h3 class="font-semibold text-yellow-800 dark:text-yellow-200">Login Failed</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                        Please check your email and password and try again.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full @error('email') border-red-500 @enderror" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input 
                id="password" 
                class="block mt-1 w-full @error('password') border-red-500 @enderror"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••" 
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Info Alert -->
        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
            <p class="text-xs text-blue-700 dark:text-blue-300 flex items-center gap-2">
                <span>ℹ️</span>
                <span>Make sure your account hasn't been suspended by your administrator.</span>
            </p>
        </div>

        <div class="flex items-center justify-between mt-6 gap-3">
            <div>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Sign Up Link -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold">
                        Sign up here
                    </a>
                @endif
            </p>
        </div>
    </form>
</x-guest-layout>