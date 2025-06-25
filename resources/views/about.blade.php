@extends('layouts.main')

@section('title', 'Tentang - Sistem Syahriah PP Nurul Asna')

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="text-center mb-4">
    <span class="badge bg-primary bg-gradient fs-6 px-4 py-2 mb-2 shadow">Versi Sistem: 1.0.0</span>
    <h2 class="fw-bold mt-2" style="letter-spacing:1px;">Sistem Syahriah PP Nurul Asna</h2>
    <p class="lead text-secondary">Sistem pengelolaan pembayaran syahriah & administrasi keuangan yang <span class="fw-bold text-primary">modern</span>, <span class="fw-bold text-success">responsif</span>, dan <span class="fw-bold text-info">user-friendly</span>.</p>
</div>

            <div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-lg bg-light bg-gradient">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-bullseye fa-lg text-primary me-2"></i>Tujuan Sistem</h5>
                <ul class="list-unstyled mb-2">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Efisiensi & transparansi keuangan pondok</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Pencatatan pembayaran & tunggakan otomatis</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Laporan bulanan & tahunan rapi, mudah diakses</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Notifikasi real-time <span class="badge bg-info text-dark ms-1">SweetAlert2 Toast</span></li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Antarmuka modern, responsif, ramah mobile</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-lg bg-white bg-gradient">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-layer-group fa-lg text-success me-2"></i>Dukungan & Modul</h5>
                <ul class="list-unstyled mb-2">
                    <li class="mb-2"><i class="fas fa-dot-circle text-primary me-2"></i> Biaya Madin (Madrasah Diniyah)</li>
                    <li class="mb-2"><i class="fas fa-dot-circle text-primary me-2"></i> Biaya Kos</li>
                    <li class="mb-2"><i class="fas fa-dot-circle text-primary me-2"></i> Biaya Pondok</li>
                    <li class="mb-2"><i class="fas fa-dot-circle text-primary me-2"></i> Hak Bebas Biaya (subsidi/gratis)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

            <div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow bg-white bg-gradient text-center p-3">
            <div class="mb-3">
                <i class="fas fa-users fa-3x text-primary"></i>
            </div>
            <h6 class="fw-bold mb-2">Manajemen Santri</h6>
            <p class="small text-muted">Pengelolaan data santri aktif, pencarian, filter, dan riwayat pembayaran terintegrasi.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow bg-white bg-gradient text-center p-3">
            <div class="mb-3">
                <i class="fas fa-money-bill fa-3x text-success"></i>
            </div>
            <h6 class="fw-bold mb-2">Pembayaran & Hak Bebas</h6>
            <p class="small text-muted">Pencatatan pembayaran, pengelolaan hak bebas biaya, serta approval massal/otomatis untuk wali santri.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow bg-white bg-gradient text-center p-3">
            <div class="mb-3">
                <i class="fas fa-chart-bar fa-3x text-info"></i>
            </div>
            <h6 class="fw-bold mb-2">Laporan & Notifikasi</h6>
            <p class="small text-muted">Laporan bulanan/tahunan, rekap otomatis, serta notifikasi sukses/gagal dengan <span class="badge bg-info text-dark">SweetAlert2 Toast</span> di seluruh fitur.</p>
        </div>
    </div>
</div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pengembang</h5>
                    <p>Sistem ini dikembangkan oleh tim IT Pondok Pesantren Nurul Asna untuk memenuhi kebutuhan administrasi
                        dan pengelolaan keuangan pondok.</p>
                    <p class="mb-0">Versi sistem: 1.0.0</p>
                </div>
            </div>
        </div>
    </div>
@endsection
