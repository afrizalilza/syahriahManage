@extends('layouts.main')
@section('title', 'Edit Pengeluaran Kas')
@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Pengeluaran Kas</div>
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
                        <form method="POST" action="{{ route('kas.pengeluaran.update', $pengeluaran->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nominal <span class="text-danger">*</span></label>
                                <input type="number" name="nominal" class="form-control"
                                    value="{{ old('nominal', $pengeluaran->nominal) }}" min="0" required>
                            </div>
                            @if (auth()->user()->role === 'admin')
                                <div class="form-group">
                                    <label for="unit">Unit</label>
                                    <select class="form-control @error('unit') is-invalid @enderror" id="unit"
                                        name="unit" required>
                                        <option value="">-- Pilih Unit --</option>
                                        <option value="putra"
                                            {{ old('unit', $pengeluaran->unit) == 'putra' ? 'selected' : '' }}>Putra
                                        </option>
                                        <option value="putri"
                                            {{ old('unit', $pengeluaran->unit) == 'putri' ? 'selected' : '' }}>Putri
                                        </option>
                                    </select>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Nama Pengeluaran <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $pengeluaran->nama) }}" required placeholder="Contoh: Beli Beras">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Bukti (foto/nota)</label>
                                <input type="file" name="bukti" class="form-control" accept="image/*">
                                @if ($pengeluaran->bukti)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $pengeluaran->bukti) }}" target="_blank"
                                            class="btn btn-outline-info btn-sm"><i class="fas fa-image"></i> Lihat Bukti
                                            Lama</a>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('kas.detail', $pengeluaran->biaya_id) }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
