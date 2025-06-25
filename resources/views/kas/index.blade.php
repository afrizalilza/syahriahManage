@extends('layouts.main')

@section('title', 'Kas Syahriah')

@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-wallet me-2"></i>Kas Syahriah</h2>
                    <p class="lead mb-0">Kelola kas syahriah per jenis biaya dan rekap saldo otomatis</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Kas Syahriah</h5>
                </div>
                <div class="card-body">
                    {{-- Filter dihapus sesuai permintaan --}}
                    <div class="alert alert-info py-2 small mb-3">
                        Daftar kas otomatis mengikuti data <b>Jenis Biaya</b>. Untuk menambah kas, silakan tambah Jenis
                        Biaya baru.
                    </div>
                    {{-- Tombol ekspor Excel & PDF dihapus sesuai permintaan --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:36px;">#</th>
                                    <th>Nama Kas</th>
                                    <th>Unit</th>
                                    <th>Nominal Biaya</th>
                                    <th>Saldo</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kasList as $kas)
                                    @php
                                        // Hitung pemasukan dan pengeluaran untuk saldo
                                        $totalPemasukan = \App\Models\Pembayaran::where('biaya_id', $kas->id)->sum(
                                            'jumlah_bayar',
                                        );
                                        $totalPengeluaran = \App\Models\Pengeluaran::where('biaya_id', $kas->id)->sum(
                                            'nominal',
                                        );
                                        $saldo = $totalPemasukan - $totalPengeluaran;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kas->nama_biaya }}</td>
                                        <td><span
                                                class="badge bg-{{ $kas->unit == 'putra' ? 'primary' : 'success' }}">{{ Str::ucfirst($kas->unit) }}</span>
                                        </td>
                                        <td>Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</td>
                                        <td><span class="badge bg-primary">Rp
                                                {{ number_format($saldo, 0, ',', '.') }}</span></td>
                                        <td>
                                            <a href="{{ route('kas.detail', $kas->id) }}" class="btn btn-info btn-sm"><i
                                                    class="fas fa-eye"></i> Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada kas (jenis biaya)</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
