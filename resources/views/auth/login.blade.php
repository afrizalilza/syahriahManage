<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-primary-dark font-poppins mb-1">Login Akun</h2>
        <p class="text-primary text-sm font-poppins">Selamat datang kembali! Silakan login.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Social Login -->
    <div>
        <a href="{{ route('google.redirect') }}"
            class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 font-poppins">
            <img class="h-5 w-5" src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
            <span class="ml-3">Login dengan Google</span>
        </a>
    </div>

    <div class="my-4 flex items-center">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-4 flex-shrink text-gray-400 font-poppins">atau</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-poppins text-primary-dark" />
            <x-text-input id="email"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="font-poppins text-primary-dark" />
            <x-text-input id="password"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-primary-light text-primary focus:ring-primary" name="remember">
                <span class="ml-2 text-sm text-gray-600 font-poppins">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="flex flex-col items-start">
                @if (Route::has('password.request'))
                    <a class="text-sm text-primary hover:text-primary-dark font-poppins underline mb-2"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
                <a class="text-sm text-primary hover:text-primary-dark font-poppins underline"
                    href="{{ route('register') }}">
                    {{ __('Belum punya akun? Daftar') }}
                </a>
            </div>

            <x-primary-button class="ml-4 bg-primary hover:bg-primary-dark border-0 font-poppins px-6 py-2 text-base">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
