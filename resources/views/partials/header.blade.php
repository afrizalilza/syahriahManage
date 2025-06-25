<header class="main-header">
    <div class="header-top py-2" style="background-color: var(--blue-dark);">
        <div class="container">
            <div class="d-flex justify-content-end align-items-center">
                <div class="current-time text-white">
                    <i class="fas fa-clock me-2"></i>
                    {{ date('l, d F Y') }}
                </div>
            </div>
        </div>
    </div>
    <div class="header-main py-3" style="background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <!-- LOGO & APP INFO -->
                <div class="d-flex flex-row align-items-center mb-2 mb-md-0 w-100 w-md-auto justify-content-center justify-content-md-start">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo PP Nurul Asna" style="height:42px;width:42px;object-fit:cover;border-radius:50%;border:1px solid #eee;" class="me-2">
                    <div>
                        <span class="fw-bold" style="font-size:1rem;color:var(--blue-dark);line-height:1.1;">Syahriah PP Nurul Asna</span><br>
                        <span class="d-block" style="font-size:0.85rem;color:var(--blue-medium);line-height:1.1;">Sistem Manajemen<br class="d-md-none">Pembayaran Syahriah</span>
                    </div>
                </div>
                <!-- USER & ACTIONS -->
                <div class="d-flex flex-row align-items-center justify-content-center justify-content-md-end w-100 w-md-auto gap-2 mt-2 mt-md-0">
                    @if (auth()->check() && auth()->user()->role == 'admin' && isset($unreadNotifications))
                        <div class="dropdown">
                            <a href="#" class="text-decoration-none position-relative" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--blue-dark);">
                                <i class="fas fa-bell fs-5"></i>
                                @if ($unreadNotifications->count() > 0)
                                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="font-size:0.65em;padding:.2em .4em;">
                                        {{ $unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 320px;">
                                <li class="dropdown-header">{{ $unreadNotifications->count() }} Notifikasi Baru</li>
                                <li><hr class="dropdown-divider"></li>
                                @forelse($unreadNotifications as $notification)
                                    <li>
                                        <a href="{{ route('notification.markAsRead', ['id' => $notification->id]) }}" class="dropdown-item text-wrap">
                                            <small><i class="fas fa-user-plus me-2 text-primary"></i>{{ $notification->data['message'] }}</small>
                                            <small class="d-block text-muted text-end mt-1">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    </li>
                                @empty
                                    <li><a class="dropdown-item text-center text-muted" href="#"><small>Tidak ada notifikasi baru</small></a></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('notification.index') }}">Lihat Semua Notifikasi</a></li>
                            </ul>
                        </div>
                    @endif
                    <span class="d-flex align-items-center" style="font-size:0.97rem;color:var(--blue-dark);max-width:110px;word-break:break-word;">
                        <i class="fas fa-user-circle me-1" style="color:var(--blue-medium);"></i>
                        <span class="text-truncate">{{ Auth::user()->name }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="d-none d-sm-inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
/* FORCE dropdown notification solid background */
header .dropdown-menu,
header .dropdown-menu .dropdown-item,
header .dropdown-menu .dropdown-header,
header .dropdown-menu .dropdown-divider {
    background-color: #fff !important;
    opacity: 1 !important;
    backdrop-filter: none !important;
    background-image: none !important;
    filter: none !important;
    color: #222;
}
header .dropdown-menu {
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    border: 1px solid #eee;
}
</style>
