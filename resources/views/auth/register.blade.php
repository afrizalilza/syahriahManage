<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-primary-dark font-poppins mb-1">Registrasi Akun</h2>
        <p class="text-primary text-sm font-poppins">Silakan isi data Anda untuk membuat akun baru</p>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-poppins text-primary-dark" />
            <x-text-input id="name"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="font-poppins text-primary-dark" />
            <x-text-input id="email"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
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

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-primary hover:text-primary-dark font-poppins underline" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Login') }}
            </a>

            <x-primary-button class="ml-4 bg-primary hover:bg-primary-dark border-0 font-poppins px-6 py-2 text-base">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
