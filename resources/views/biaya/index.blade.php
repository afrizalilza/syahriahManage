@extends('layouts.main')

@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-money-bill me-2"></i>Jenis Biaya</h2>
                    <p class="lead mb-0">Kelola jenis biaya syahriah Pondok Pesantren Nurul Asna</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Jenis Biaya</h5>
                    <button class="btn btn-primary" onclick="window.location='{{ route('biaya.create') }}'">
                        <i class="fas fa-plus me-2"></i>Tambah Jenis Biaya
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Biaya</th>
                                    <th>Nama Biaya</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($biayas as $biaya)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $biaya->id }}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $biaya->nama_biaya }}</td>
                                        <td>{{ number_format($biaya->jumlah, 0, ',', '.') }}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $biaya->keterangan }}</td>
                                        <td>
                                            <a href="{{ route('biaya.show', $biaya->id) }}"
                                                class="btn btn-sm btn-success me-1"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('biaya.edit', $biaya->id) }}"
                                                class="btn btn-sm btn-info me-1"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('biaya.destroy', $biaya->id) }}" method="POST"
                                                class="d-inline form-confirm">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data biaya.</td>
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
