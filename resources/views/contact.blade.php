@extends('layouts.main')

@section('title', 'Kontak - Sistem Manajemen Syahriah')

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="text-center mb-4">
    <span class="badge bg-success bg-gradient fs-6 px-4 py-2 mb-2 shadow">Kontak & Layanan</span>
    <h2 class="fw-bold mt-2" style="letter-spacing:1px;">Kontak Administrasi Syahriah</h2>
    <p class="lead text-secondary">Hubungi pengurus untuk keperluan administrasi, pembayaran, atau konfirmasi transfer Syahriah.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-lg bg-white bg-gradient">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-id-card fa-lg text-primary me-2"></i>Informasi Kontak</h5>
                <ul class="list-unstyled mb-2">
                    <li class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i> <strong>Alamat:</strong> Kalirejo Undaan Kudus</li>
                    <li class="mb-2"><i class="fas fa-envelope text-warning me-2"></i> <strong>Email:</strong> nadhifmood@gmail.com</li>
                    <li class="mb-2"><i class="fas fa-phone-alt text-success me-2"></i> <strong>Telepon:</strong> 08123456789</li>
                    <li class="mb-2"><i class="fas fa-user-tie text-info me-2"></i> <strong>Pengurus:</strong> Ustadz Lutfi (Bendahara)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-lg bg-light bg-gradient">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-clock fa-lg text-success me-2"></i>Waktu Pelayanan</h5>
                <ul class="list-unstyled mb-2">
                    <li class="mb-2"><i class="fas fa-calendar-day text-primary me-2"></i> <strong>Senin - Kamis:</strong> 08:00 - 15:00 WIB</li>
                    <li class="mb-2"><i class="fas fa-calendar-day text-primary me-2"></i> <strong>Jumat:</strong> 08:00 - 11:00 WIB</li>
                    <li class="mb-2"><i class="fas fa-calendar-day text-primary me-2"></i> <strong>Sabtu:</strong> 08:00 - 12:00 WIB</li>
                </ul>
                <p class="small text-muted mb-0"><em><i class="fas fa-info-circle me-1"></i>Di luar jam kerja, hubungi nomor pengurus untuk urusan mendesak.</em></p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow bg-gradient bg-info-subtle">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-university fa-lg text-info me-2"></i>Pembayaran Syahriah</h5>
                <ul class="mb-2">
                    <li>Pembayaran langsung ke kantor administrasi pada jam kerja</li>
                    <li>Transfer bank ke rekening pondok:<br>
                        <span class="fw-bold">Bank BRI</span>: <span class="text-primary">1234-5678-9012-3456</span><br>
                        <span class="fw-bold">A.n. Yayasan Pondok Pesantren</span>
                    </li>
                </ul>
                <div class="alert alert-warning py-2 px-3 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Setelah transfer, <b>WAJIB</b> konfirmasi dengan mengirimkan bukti transfer ke nomor pengurus di atas.</div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
@endsection
