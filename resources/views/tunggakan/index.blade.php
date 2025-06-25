@extends('layouts.main')

@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-exclamation-triangle me-2"></i>Data Tunggakan</h2>
                    <p class="lead mb-0">Monitoring tunggakan pembayaran syahriah santri</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Summary Cards -->
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Tunggakan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h3>
                    <small>dari {{ $totalSantri }} santri</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Rata-rata Tunggakan</h6>
                    <h3 class="mb-0">Rp {{ number_format($rataRataTunggakan, 0, ',', '.') }}</h3>
                    <small>per santri</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Tunggakan</h5>
                    <div>
                        <a href="{{ route('tunggakan.exportExcel', array_filter(['periode' => request('periode', $periode), 'q' => request('q')])) }}"
                            class="btn btn-success me-2">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                        <a href="{{ route('tunggakan.exportPdf', request()->all()) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <form method="GET" action="{{ route('tunggakan.index') }}">
                        <div class="row mb-4">
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <label class="form-label">Periode</label>
                                <input type="month" class="form-control" name="periode"
                                    value="{{ request('periode', $periode) }}">
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <label class="form-label">Cari Santri</label>
                                <input type="text" class="form-control" name="q" placeholder="Nama atau NIS..."
                                    value="{{ request('q') }}">
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Cari
                                    </button>
                                    <a href="{{ route('tunggakan.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-sync-alt me-2"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>Total Tunggakan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tunggakans->where('jumlah_tunggakan', '>', 0) as $tunggakan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tunggakan['santri']->nis ?? '-' }}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $tunggakan['santri']->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($tunggakan['jumlah_tunggakan'], 0, ',', '.') }}</td>
                                        <td><span class="badge bg-danger">Belum Lunas</span></td>
                                        <td>
                                            <a href="{{ route('tunggakan.detail', ['santri_id' => $tunggakan['santri']->id, 'periode' => $tunggakan['periode']]) }}"
                                                class="btn btn-sm btn-success me-1" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data tunggakan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.95rem;
    }
    .table td, .table th {
        vertical-align: middle;
        white-space: nowrap;
    }
    .table .btn {
        margin-bottom: 4px;
    }
}
</style>
@endsection
