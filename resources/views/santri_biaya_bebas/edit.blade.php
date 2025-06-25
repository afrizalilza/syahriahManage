@extends('layouts.main')
@section('container')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Hak Bebas Biaya Santri</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('santri_biaya_bebas.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Santri</label>
                            <select name="santri_id" class="form-control" required disabled>
                                <option value="">-- Pilih Santri --</option>
                                @foreach ($santris as $santri)
                                    <option value="{{ $santri->id }}"
                                        {{ $santri->id == $item->santri_id ? 'selected' : '' }}>{{ $santri->nama }}
                                        ({{ $santri->nis }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Santri tidak dapat diubah.</small>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Biaya</label>
                            <select name="biaya_id" class="form-control" required>
                                @foreach ($biayas as $biaya)
                                    <option value="{{ $biaya->id }}"
                                        {{ $biaya->id == $item->biaya_id ? 'selected' : '' }}>{{ $biaya->nama_biaya }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan (opsional)</label>
                            <input type="text" name="keterangan" class="form-control" value="{{ $item->keterangan }}">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary me-2"><i class="fas fa-save"></i> Update</button>
                            <a href="{{ route('santri_biaya_bebas.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
