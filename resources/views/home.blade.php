@extends('layouts.main')

@section('title', 'Dashboard - Sistem Syahriah PP Nurul Asna')

@section('container')
    {{-- ======= SALDO KAS GLOBAL ======= --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 bg-success bg-gradient text-white text-center p-4 mb-2">
                <div class="fw-bold fs-4 mb-1"><i class="fas fa-wallet me-2"></i>Saldo Kas Global</div>
                <div class="display-5 fw-bold mb-0">Rp {{ number_format($saldoSyahriah, 0, ',', '.') }}</div>
                <div class="small mt-1">Saldo kas syahriah seluruh waktu</div>
            </div>
        </div>
    </div>

    {{-- ======= HEADER & FILTER ======= --}}
    <div class="row mb-4 align-items-center gx-3 gy-2">
        <div class="col-lg-7 col-12 mb-2 mb-lg-0">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
                    style="width:48px;height:48px;">
                    <i class="fas fa-tachometer-alt text-white fs-3"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h1 class="mb-0 fw-bold fs-3" style="letter-spacing:0.5px;">Dashboard</h1>
                        <span class="badge bg-primary bg-gradient text-white align-middle"
                            style="font-size:1rem;">Syahriah</span>
                    </div>
                    <div class="text-muted small">Ringkasan data syahriah Pondok Pesantren Nurul Asna</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-12 text-lg-end text-start">
            <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-2 gap-lg-3">
                <form method="GET" action="" class="d-inline-block flex-shrink-0">
                    <div class="input-group input-group-sm shadow-sm rounded" style="max-width:220px;background:#fff;">
                        <span class="input-group-text bg-white border-end-0" style="border-radius:0.4rem 0 0 0.4rem;">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </span>
                        <input type="month" name="periode" class="form-control border-start-0" value="{{ $periode }}"
                            max="{{ date('Y-m') }}" style="border-radius:0 0.4rem 0.4rem 0;">
                        <button class="btn btn-primary px-3" type="submit" style="border-radius:0.4rem;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                @if ($recentBebas->count())
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-info dropdown-toggle py-2 px-3 mb-0 d-flex align-items-center gap-2"
                            type="button" id="dropdownRecentBebas" data-bs-toggle="dropdown" aria-expanded="false"
                            style="font-size:0.97rem;box-shadow:0 2px 8px #2193b022;border-radius:0.8rem;">
                            <i class="fas fa-gift me-1 text-primary"></i>
                            <strong class="me-1">Hak Bebas Biaya Baru</strong>
                            <span class="badge bg-success text-white">{{ $recentBebas->count() }}</span>
                        </button>
                        <ul class="dropdown-menu p-2" aria-labelledby="dropdownRecentBebas"
                            style="min-width:250px;max-height:260px;overflow-y:auto;">
                            @foreach ($recentBebas as $bebas)
                                <li class="mb-1">
                                    <span class="badge bg-success text-white w-100 text-start"
                                        style="font-size:0.97rem;white-space:normal;">
                                        <i class="fas fa-user me-1"></i>{{ $bebas->santri->nama ?? '-' }}<br>
                                        <small class="text-white-50 ms-4">{{ $bebas->biaya->nama_biaya ?? '-' }}</small>
                                    </span>
                                </li>
                            @endforeach
                            @if ($recentBebas->count() === 0)
                                <li class="text-muted px-2">Tidak ada data.</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ======= STATISTIC CARDS ======= --}}
    <div class="row g-4 mb-5 justify-content-center">
        <div class="col-6 col-md-3">
            <div class="stat-modern-card bg-primary bg-gradient text-white rounded-4 shadow-sm text-center p-4 h-100">
                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                    style="width:56px; height:56px;">
                    <i class="fas fa-users fs-2"></i>
                </div>
                <div class="fw-bold display-5 mb-1">{{ $totalSantri }}</div>
                <div class="small">Santri Aktif</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-modern-card bg-success bg-gradient text-white rounded-4 shadow-sm text-center p-4 h-100">
                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                    style="width:56px; height:56px;">
                    <i class="fas fa-check-circle fs-2"></i>
                </div>
                <div class="fw-bold display-5 mb-1">{{ $lunas }}</div>
                <div class="small">Lunas Bulan Ini</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-modern-card bg-warning bg-gradient text-white rounded-4 shadow-sm text-center p-4 h-100">
                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                    style="width:56px; height:56px;">
                    <i class="fas fa-exclamation-circle fs-2"></i>
                </div>
                <div class="fw-bold display-5 mb-1">{{ $belumLunas }}</div>
                <div class="small">Belum Lunas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-modern-card bg-info bg-gradient text-white rounded-4 shadow-sm text-center p-4 h-100">
                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                    style="width:56px; height:56px;">
                    <i class="fas fa-percentage fs-2"></i>
                </div>
                <div class="fw-bold display-5 mb-1">{{ $persenLunas }}%</div>
                <div class="small">Persentase Lunas</div>
            </div>
        </div>
    </div>

    {{-- ======= MENU CEPAT ======= --}}
    <div class="row mb-5 justify-content-center">
        <div class="col-12 col-md-10">
            <div class="quick-menu-card p-4 mb-3 shadow-lg rounded-4 border-0"
                style="background:linear-gradient(90deg,#e0eafc 0%,#cfdef3 100%);display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                <div class="fw-bold text-primary mb-3" style="font-size:1.35rem;"><i class="fas fa-bolt me-2"></i>Menu Cepat
                </div>
                <div class="row g-3 justify-content-center w-100">
                    @php
                        $menus = [
                            [
                                'route' => route('pembayaran.index'),
                                'icon' => 'fas fa-money-bill-wave',
                                'color' => 'bg-success',
                                'label' => 'Pembayaran',
                            ],
                            [
                                'route' => route('santri.index'),
                                'icon' => 'fas fa-users',
                                'color' => 'bg-primary',
                                'label' => 'Data Santri',
                            ],
                            [
                                'route' => route('tunggakan.index'),
                                'icon' => 'fas fa-exclamation-triangle',
                                'color' => 'bg-warning',
                                'label' => 'Cek Tunggakan',
                            ],
                            [
                                'route' => route('laporan.bulanan'),
                                'icon' => 'fas fa-file-alt',
                                'color' => 'bg-info',
                                'label' => 'Laporan Bulanan',
                            ],
                            [
                                'route' => route('kas.index'),
                                'icon' => 'fas fa-wallet',
                                'color' => 'bg-danger',
                                'label' => 'Kas Syahriah',
                            ],
                        ];
                    @endphp
                    @foreach ($menus as $menu)
                        <div class="col-6 col-md-4 col-lg-2 d-flex">
                            <a href="{{ $menu['route'] }}" class="w-100 text-decoration-none quick-menu-btn">
                                <div class="card shadow-sm border-0 rounded-4 text-center quick-menu-card h-100 py-4">
                                    <div class="mx-auto mb-2 quick-menu-icon {{ $menu['color'] }} bg-gradient rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:54px;height:54px;">
                                        <i class="{{ $menu['icon'] }} text-white fs-3"></i>
                                    </div>
                                    <div class="fw-semibold text-dark" style="font-size:1.05rem;">{{ $menu['label'] }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ======= KAS SUMMARY CARD (Dinonaktifkan sementara karena logika salah) ======= --}}
    {{--
<div class="row mb-5 justify-content-center">
    <div class="col-12 col-md-10">
        <div class="card border-0 shadow-lg rounded-4 bg-white">
            <div class="card-body">
                <div class="fw-bold fs-5 mb-3 text-primary"><i class="fas fa-wallet me-2"></i>Saldo Kas Per Jenis Biaya</div>
                <div class="row g-3">
                    @foreach ($biayas as $biaya)
                    @php
                        $saldo = $saldoKasPerJenis[$biaya->id] ?? 0;
                        $isPositive = $saldo > 0;
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow-sm border-0 rounded-4 text-center h-100 p-3 kas-summary-card {{ $isPositive ? 'bg-light' : 'bg-danger bg-opacity-10' }}">
                            <div class="mx-auto mb-2 bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i class="fas fa-wallet text-white fs-5"></i>
                            </div>
                            <div class="fw-bold mb-1" style="font-size:1.1rem;">{{ $biaya->nama_biaya }}</div>
                            <div class="fw-semibold {{ $isPositive ? 'text-success' : 'text-danger' }}" style="font-size:1.3rem;">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow border-0 rounded-4 text-center h-100 p-3 bg-success bg-gradient text-white">
                            <div class="mx-auto mb-2 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i class="fas fa-coins text-white fs-5"></i>
                            </div>
                            <div class="fw-bold mb-1" style="font-size:1.1rem;">Total Saldo Kas</div>
                            <div class="fw-semibold fs-4">
                                Rp {{ number_format(array_sum($saldoKasPerJenis), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

    {{-- ======= PEMBAYARAN BULAN INI & TUNGGAKAN ======= --}}
    <div class="row mb-5 justify-content-center">
        <div class="col-12 col-md-5 mb-3 mb-md-0">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-4 py-4">
                    <div class="icon-circle bg-primary bg-gradient text-white shadow"
                        style="width:48px;height:48px;font-size:1.6rem;"><i class="fas fa-money-check-alt"></i></div>
                    <div>
                        <div class="fw-bold fs-4 mb-1">Rp {{ number_format($pembayaranBulanIni, 0, ',', '.') }}</div>
                        <div class="text-muted small">Pembayaran Bulan Ini<br><span class="fw-normal">Total
                                {{ $periodeLabel }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-4 py-4">
                    <div class="icon-circle bg-danger bg-gradient text-white shadow"
                        style="width:48px;height:48px;font-size:1.6rem;"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <div class="fw-bold fs-4 mb-1">Rp {{ number_format($tunggakanBulanIni, 0, ',', '.') }}</div>
                        <div class="text-muted small">Total Tunggakan Bulan Ini<br><span class="fw-normal">Total tunggakan
                                santri periode {{ $periode }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======= RIWAYAT & BELUM LUNAS ======= --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="riwayat-modern-card shadow-lg rounded-4 bg-white p-4 mb-4">
                <div class="riwayat-header mb-3 fw-bold text-primary"><i class="fas fa-history"></i> Pembayaran Terakhir
                </div>
                <ul class="riwayat-list ps-0">
                    @foreach ($pembayaranTerakhir->take(5) as $pembayaran)
                        <li class="riwayat-item d-flex justify-content-between align-items-center border-bottom py-2">
                            <span
                                class="riwayat-date text-primary fw-semibold">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}</span>
                            <span class="riwayat-nama">{{ $pembayaran->santri->nama ?? '-' }}</span>
                            <span class="riwayat-nominal fw-bold">Rp
                                {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="riwayat-modern-card shadow-lg rounded-4 bg-white p-4 h-100">
                <div class="riwayat-header bg-danger mb-3 fw-bold text-white rounded-3 px-3 py-2"
                    style="background: linear-gradient(90deg, #e52d27 0%, #ff6a00 100%) !important;">
                    <i class="fas fa-user-clock"></i> Santri Belum Lunas Bulan Ini
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-danger dropdown-toggle w-100 mb-2 rounded-3 d-flex align-items-center justify-content-between" type="button"
                        id="dropdownSantriBelumLunas" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:1rem; min-width:0;">
                        <span class="flex-grow-1 text-start text-truncate" style="overflow:hidden;">Tampilkan Semua Santri Belum Lunas</span>
                        <span class="ms-2 fw-bold text-nowrap" style="color:#e52d27;">({{ $santriBelumLunasList->count() }})</span>
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="dropdownSantriBelumLunas"
                        style="max-height:260px;overflow-y:auto;min-width:100%;">
                        @forelse($santriBelumLunasList as $santri)
                            <li class="mb-1">
                                <span class="badge bg-danger text-white w-100 text-start text-truncate"
                                    style="font-size:0.97rem;white-space:normal;max-width:100%;">
                                    <i class="fas fa-user me-1"></i>{{ $santri['nama'] }} <span
                                        class="text-white-50">({{ $santri['nis'] }})</span> <span class="float-end">Rp
                                        {{ number_format($santri['sisa'], 0, ',', '.') }}</span>
                                </span>
                            </li>
                        @empty
                            <li class="text-muted px-2">Semua santri sudah lunas bulan ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 1.6rem;
            box-shadow: 0 2px 8px #2193b044;
        }

        .stat-modern-card {
            transition: box-shadow .2s, transform .2s;
        }

        .stat-modern-card:hover {
            box-shadow: 0 10px 32px #2193b055;
            transform: translateY(-4px) scale(1.04);
        }

        .riwayat-modern-card .riwayat-header {
            background: none;
            font-size: 1.15rem;
            font-weight: bold;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .riwayat-modern-card .riwayat-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .riwayat-modern-card .riwayat-item {
            font-size: 1.08rem;
        }

        .riwayat-modern-card .riwayat-date {
            color: #2193b0;
            font-weight: 500;
            min-width: 110px;
        }

        .riwayat-modern-card .riwayat-nama {
            color: #444;
            font-weight: 500;
            flex: 1;
            text-align: left;
            padding-left: 0.5rem;
        }

        .riwayat-modern-card .riwayat-nominal {
            color: #0d294e;
            font-weight: bold;
            text-align: right;
            min-width: 120px;
        }

        @media (max-width: 768px) {
            .stat-modern-card .icon-circle {
                width: 38px;
                height: 38px;
                font-size: 1.2rem;
            }

            .stat-modern-card .fw-bold.display-5 {
                font-size: 1.2rem !important;
            }

            .riwayat-modern-card .riwayat-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.3rem;
            }

            .riwayat-modern-card .riwayat-date,
            .riwayat-modern-card .riwayat-nama,
            .riwayat-modern-card .riwayat-nominal {
                min-width: 0;
                text-align: left;
            }
        }

        .quick-menu-card {
            transition: box-shadow .2s, background .2s;
        }

        .quick-menu-btn {
            transition: background .2s, color .2s;
            border: none;
            background: transparent;
            padding: 0;
            width: 100%;
            display: block;
            border-radius: 1.2rem;
        }

        .quick-menu-icon {
            transition: background .2s, color .2s, transform .2s;
        }

        .quick-menu-btn:hover .quick-menu-icon.bg-success,
        .quick-menu-btn:focus .quick-menu-icon.bg-success {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%) !important;
            color: #fff !important;
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 4px 16px #43e97b44;
        }

        .quick-menu-btn:hover .quick-menu-icon.bg-primary,
        .quick-menu-btn:focus .quick-menu-icon.bg-primary {
            background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%) !important;
            color: #fff !important;
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 4px 16px #2193b044;
        }

        .quick-menu-btn:hover .quick-menu-icon.bg-warning,
        .quick-menu-btn:focus .quick-menu-icon.bg-warning {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%) !important;
            color: #fff !important;
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 4px 16px #ffd20044;
        }

        .quick-menu-btn:hover .quick-menu-icon.bg-info,
        .quick-menu-btn:focus .quick-menu-icon.bg-info {
            background: linear-gradient(90deg, #56ccf2 0%, #2f80ed 100%) !important;
            color: #fff !important;
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 4px 16px #56ccf244;
        }

        .quick-menu-btn:hover .quick-menu-icon.bg-danger,
        .quick-menu-btn:focus .quick-menu-icon.bg-danger {
            background: linear-gradient(90deg, #e52d27 0%, #ff6a00 100%) !important;
            color: #fff !important;
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 4px 16px #e52d2744;
        }

        .quick-menu-btn:hover .quick-menu-icon i,
        .quick-menu-btn:focus .quick-menu-icon i {
            color: #fff !important;
        }

        .kas-summary-card {
            transition: box-shadow .2s, background .2s;
        }

        .kas-summary-card:hover {
            box-shadow: 0 6px 24px #2193b044;
            background: #e3f2fd !important;
        }
    </style>

    <style>
        @media (max-width: 576px) {
            #dropdownSantriBelumLunas {
                font-size: 0.95rem;
                padding-left: 0.7em;
                padding-right: 0.7em;
            }
            .dropdown-menu .badge {
                font-size: 0.93rem;
                padding: .65em .6em;
                line-height: 1.3;
                word-break: break-word;
            }
        }
    </style>

    <style>
        @media (max-width: 576px) {
            #dropdownSantriBelumLunas {
                font-size: 0.93rem;
                padding-left: 0.5em;
                padding-right: 0.5em;
            }
            #dropdownSantriBelumLunas .fw-bold {
                font-size: 0.93em;
            }
        }
    </style>
    <style>
        @media (max-width: 576px) {
            #dropdownSantriBelumLunas {
                font-size: 0.93rem;
                padding-left: 0.5em;
                padding-right: 0.5em;
                min-width: 0;
            }
            #dropdownSantriBelumLunas .fw-bold {
                font-size: 0.93em;
            }
        }
    </style>
@endsection
