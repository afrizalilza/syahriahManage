@extends('layouts.main')

@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-users me-2"></i>Data Santri</h2>
                    <p class="lead mb-0">Kelola data santri Pondok Pesantren Nurul Asna</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Santri</h5>
                    <button class="btn btn-primary" onclick="window.location='{{ route('santri.create') }}'">
                        <i class="fas fa-plus me-2"></i>Tambah Santri
                    </button>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('santri.index') }}" class="mb-3">
                        <div class="row g-2">
    <div class="col-12 col-md-4 mb-2 mb-md-0">
        <input type="text" class="form-control" name="q"
            placeholder="Cari nama/NIS/alamat/status..." value="{{ request('q') }}">
    </div>
    <div class="col-12 col-md-2 mb-2 mb-md-0">
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Cari</button>
    </div>
    <div class="col-12 col-md-2">
        <a href="{{ route('santri.index') }}" class="btn btn-secondary w-100"><i
                                        class="fas fa-sync-alt me-2"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($santris as $santri)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $santri->nis }}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $santri->nama }}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $santri->alamat }}</td>
                                        <td>{{ $santri->jenis_kelamin }}</td>
                                        <td>{{ $santri->tanggal_masuk }}</td>
                                        <td>{{ $santri->status }}</td>
                                        <td>
                                            <a href="{{ route('santri.show', $santri->id) }}"
                                                class="btn btn-sm btn-success me-1"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('santri.edit', $santri->id) }}"
                                                class="btn btn-sm btn-info me-1"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('santri.destroy', $santri->id) }}" method="POST"
                                                class="d-inline form-confirm">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data santri.</td>
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
}
</style>
@endsection
