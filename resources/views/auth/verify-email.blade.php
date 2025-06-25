<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-primary-dark font-poppins mb-1">Verifikasi Email Anda</h2>
        <p class="text-primary text-sm font-poppins mb-4">
            Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan mengklik link
            yang baru saja kami kirimkan.
        </p>
        <p class="text-primary text-sm font-poppins">
            Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkannya lagi.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 font-poppins text-center">
            Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between w-full">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="bg-primary hover:bg-primary-dark border-0 font-poppins px-6 py-2 text-base">
                {{ __('Kirim Ulang Email Verifikasi') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-primary hover:text-primary-dark font-poppins underline">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
