@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Data Biaya</div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('biaya.update', $biaya->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama_biaya" class="form-label">Nama Biaya</label>
                                <input type="text" class="form-control" id="nama_biaya" name="nama_biaya"
                                    value="{{ old('nama_biaya', $biaya->nama_biaya) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    value="{{ old('jumlah', $biaya->jumlah) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan">{{ old('keterangan', $biaya->keterangan) }}</textarea>
                            </div>
                            @if (auth()->user()->role == 'admin')
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-control" id="unit" name="unit" required>
                                        <option value="">-- Pilih Unit --</option>
                                        <option value="putra" {{ old('unit', $biaya->unit) == 'putra' ? 'selected' : '' }}>
                                            Putra</option>
                                        <option value="putri" {{ old('unit', $biaya->unit) == 'putri' ? 'selected' : '' }}>
                                            Putri</option>
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('biaya.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
