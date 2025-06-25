@extends('layouts.main')
@section('container')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-certificate me-2"></i>Hak Bebas Biaya</h2>
                    <p class="lead mb-0">Kelola hak bebas biaya santri Pondok Pesantren Nurul Asna</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Hak Bebas Biaya</h5>
                    <a href="{{ route('santri_biaya_bebas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Hak Bebas
                    </a>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>Jenis Biaya</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><span class="badge bg-info">{{ $item->santri->nis ?? '-' }}</span></td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $item->santri->nama ?? '-' }}</td>
                                        <td><span class="badge bg-primary text-truncate" style="max-width:120px;display:inline-block;">{{ $item->biaya->nama_biaya ?? '-' }}</span></td>
                                        <td class="text-truncate" style="max-width:120px;">{{ $item->keterangan }}</td>
                                        <td>
                                            <a href="{{ route('santri_biaya_bebas.edit', $item->id) }}"
                                                class="btn btn-info btn-sm me-1" title="Edit"><i
                                                    class="fas fa-edit"></i></a>
                                            <form action="{{ route('santri_biaya_bebas.destroy', $item->id) }}"
                                                method="POST" class="d-inline form-confirm" data-message="Yakin hapus?">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Hapus"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data hak bebas biaya.</td>
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
