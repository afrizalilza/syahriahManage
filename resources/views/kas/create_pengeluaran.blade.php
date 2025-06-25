@extends('layouts.main')
@section('title', 'Tambah Pengeluaran Kas')
@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Pengeluaran: {{ $kas->nama_biaya }} (Unit {{ ucfirst($kas->unit) }})
                    </div>
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
                        <form method="POST" action="{{ route('kas.pengeluaran.store', $kas->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nominal <span class="text-danger">*</span></label>
                                <input type="number" name="nominal" class="form-control" value="{{ old('nominal') }}"
                                    min="0" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Pengeluaran <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                    required placeholder="Contoh: Beli Beras">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Bukti (foto/nota)</label>
                                <input type="file" name="bukti" class="form-control" accept="image/*">
                            </div>
                            {{-- Unit untuk admin diisi otomatis dari kas yang dipilih --}}
                            @if (auth()->user()->role === 'admin')
                                <input type="hidden" name="unit" value="{{ $kas->unit }}">
                            @endif
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kas.detail', $kas->id) }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
