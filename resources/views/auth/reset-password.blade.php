<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-primary-dark font-poppins mb-1">Reset Password</h2>
        <p class="text-primary text-sm font-poppins">Buat password baru Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-poppins text-primary-dark" />
            <x-text-input id="email"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="font-poppins text-primary-dark" />
            <x-text-input id="password"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-poppins text-primary-dark" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button
                class="w-full justify-center bg-primary hover:bg-primary-dark border-0 font-poppins px-6 py-2 text-base">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
