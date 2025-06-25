<x-guest-layout>
    <div class="container text-center mt-5">
        <h2>Akun Anda Belum Aktif</h2>
        <p>Terima kasih telah mendaftar.<br>
            Akun Anda sedang menunggu persetujuan admin.<br>
            Silakan tunggu, Anda akan dihubungi jika sudah aktif.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Keluar</button>
        </form>
    </div>
</x-guest-layout>
