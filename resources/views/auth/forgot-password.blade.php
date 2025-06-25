<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-primary-dark font-poppins mb-1">Lupa Password</h2>
        <p class="text-primary text-sm font-poppins">Masukkan email Anda untuk menerima link reset.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-poppins text-primary-dark" />
            <x-text-input id="email"
                class="block mt-1 w-full font-poppins border-primary-light focus:border-primary focus:ring-primary"
                type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-primary hover:text-primary-dark font-poppins underline" href="{{ route('login') }}">
                {{ __('Kembali ke Login') }}
            </a>

            <x-primary-button class="ml-4 bg-primary hover:bg-primary-dark border-0 font-poppins px-6 py-2 text-base">
                {{ __('Kirim Link Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
