@extends('layouts.main')

@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-money-bill-wave me-2"></i>Pembayaran Syahriah</h2>
                    <p class="lead mb-0">Kelola pembayaran syahriah santri</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaksi Pembayaran</h5>
                    <button class="btn btn-primary" onclick="window.location='{{ route('pembayaran.create') }}'">
                        <i class="fas fa-plus me-2"></i>Tambah Pembayaran
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <form method="GET" action="{{ route('pembayaran.index') }}">
                        <div class="row mb-4">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label class="form-label">Periode</label>
                                <input type="month" class="form-control" name="periode" value="{{ request('periode') }}">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas
                                    </option>
                                    <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>
                                        Belum Lunas</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label class="form-label">Cari Santri</label>
                                <input type="text" class="form-control" name="q" placeholder="Nama atau NIS..."
                                    value="{{ request('q') }}">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Cari
                                    </button>
                                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary w-100">
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
                                    <th>Tanggal</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>Jenis Biaya</th>
                                    <th>Periode</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Group pembayaran by kode_transaksi
                                    $grouped = $pembayarans->groupBy('kode_transaksi');
                                @endphp
                                @foreach ($grouped as $kode => $items)
                                    @php
                                        $first = $items->first();
                                        $biayaCount = $items->count();
                                        $jumlahTotal = $items->sum('jumlah_bayar');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $first->tanggal_bayar }}</td>
                                        <td>{{ $first->santri->nis }}
                                            @php
                                                $punyaBebas =
                                                    \App\Models\SantriBiayaBebas::where(
                                                        'santri_id',
                                                        $first->santri_id,
                                                    )->count() > 0;
                                            @endphp
                                            @if ($punyaBebas)
                                                <span class="ms-1 badge bg-info" title="Hak Bebas Biaya"><i
                                                        class="fas fa-star"></i></span>
                                            @endif
                                        </td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $first->santri->nama }}</td>
                                        <td>{{ $biayaCount }} jenis biaya</td>
                                        <td>{{ $first->periode }}</td>
                                        <td>{{ number_format($jumlahTotal, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                // Ambil seluruh biaya wajib YANG TIDAK dibebaskan untuk santri ini
                                                $biayaBebas = \App\Models\SantriBiayaBebas::where(
                                                    'santri_id',
                                                    $first->santri_id,
                                                )
                                                    ->pluck('biaya_id')
                                                    ->toArray();
                                                $totalNominalWajib = $biayas
                                                    ->where('unit', $first->santri->unit)
                                                    ->whereNotIn('id', $biayaBebas)
                                                    ->sum('jumlah');
                                                $totalBayarSantri = $pembayarans
                                                    ->where('santri_id', $first->santri_id)
                                                    ->where('periode', $first->periode)
                                                    ->sum('jumlah_bayar');
                                            @endphp
                                            @if ($totalNominalWajib == 0)
                                                <span class="badge bg-success">Lunas (Semua biaya dibebaskan)</span>
                                            @elseif ($totalBayarSantri >= $totalNominalWajib)
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Aksi bisa gunakan kode_transaksi jika perlu --}}
                                            <a href="{{ route('pembayaran.show', $first->id) }}"
                                                class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('pembayaran.edit', $first->id) }}"
                                                class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('pembayaran.destroy', $first->id) }}" method="POST"
                                                class="d-inline form-confirm" data-message="Yakin ingin hapus?">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
