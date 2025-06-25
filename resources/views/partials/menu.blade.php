<nav class="navbar navbar-expand-lg" style="background-color: var(--blue-medium);">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white {{ request()->routeIs('santri.*', 'biaya.*', 'santri_biaya_bebas.*') ? 'active fw-bold' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-database me-2"></i>Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('santri.*') ? 'active' : '' }}"
                                href="{{ route('santri.index') }}">
                                <i class="fas fa-users me-2"></i>Data Santri
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('biaya.*') ? 'active' : '' }}"
                                href="{{ route('biaya.index') }}">
                                <i class="fas fa-money-bill me-2"></i>Jenis Biaya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('santri_biaya_bebas.*') ? 'active' : '' }}"
                                href="{{ route('santri_biaya_bebas.index') }}">
                                <i class="fas fa-star me-2"></i>Hak Bebas Biaya
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white {{ request()->routeIs('pembayaran.*', 'tunggakan.*') ? 'active fw-bold' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-exchange-alt me-2"></i>Transaksi
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}"
                                href="{{ route('pembayaran.index') }}">
                                <i class="fas fa-money-bill-wave me-2"></i>Pembayaran Syahriah
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('tunggakan.*') ? 'active' : '' }}"
                                href="{{ route('tunggakan.index') }}">
                                <i class="fas fa-exclamation-triangle me-2"></i>Data Tunggakan
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white {{ request()->routeIs('laporan.*') ? 'active fw-bold' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-alt me-2"></i>Laporan
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('laporan.bulanan') ? 'active' : '' }}"
                                href="{{ route('laporan.bulanan') }}">
                                <i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('laporan.tahunan') ? 'active' : '' }}"
                                href="{{ route('laporan.tahunan') }}">
                                <i class="fas fa-calendar me-2"></i>Laporan Tahunan
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('kas.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('kas.index') }}">
                        <i class="fas fa-wallet me-2"></i>Kas Syahriah
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-warning {{ request()->routeIs('user.pending') ? 'active fw-bold' : '' }}"
                            href="{{ route('user.pending') }}">
                            <i class="fas fa-user-clock me-2"></i>User Pending Approval
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('about') ? 'active fw-bold' : '' }}"
                        href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-2"></i>Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('contact') ? 'active fw-bold' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-envelope me-2"></i>Kontak
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
