@extends('layouts.main')
@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Hak Bebas Biaya</div>
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
                        <form action="{{ route('santri_biaya_bebas.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="santri_id" class="form-label">Santri</label>
                                <select name="santri_id" class="form-control" id="santri_id" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach ($santris as $santri)
                                        <option value="{{ $santri->id }}"
                                            {{ old('santri_id') == $santri->id ? 'selected' : '' }}>{{ $santri->nama }}
                                            ({{ $santri->nis }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="biaya_id" class="form-label">Jenis Biaya</label>
                                <select name="biaya_id[]" class="form-control" id="biaya_id" multiple required
                                    size="5">
                                    @foreach ($biayas as $biaya)
                                        <option value="{{ $biaya->id }}"
                                            {{ collect(old('biaya_id'))->contains($biaya->id) ? 'selected' : '' }}>
                                            {{ $biaya->nama_biaya }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Tekan dan tahan tombol <b>Ctrl</b> (Windows) atau <b>Cmd</b> (Mac)
                                    untuk memilih lebih dari satu jenis biaya.</small>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('santri_biaya_bebas.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const santriSelect = document.getElementById('santri_id');
                const biayaSelect = document.getElementById('biaya_id');
                santriSelect.addEventListener('change', function() {
                    const santriId = this.value;
                    biayaSelect.innerHTML = '<option>Memuat data...</option>';
                    if (santriId) {
                        fetch(`/ajax/biaya-by-santri?santri_id=${santriId}`)
                            .then(resp => resp.json())
                            .then(data => {
                                biayaSelect.innerHTML = '';
                                if (data.length === 0) {
                                    biayaSelect.innerHTML =
                                        '<option value="">Semua biaya sudah dibebaskan</option>';
                                } else {
                                    data.forEach(biaya => {
                                        const opt = document.createElement('option');
                                        opt.value = biaya.id;
                                        opt.textContent = biaya.nama_biaya;
                                        biayaSelect.appendChild(opt);
                                    });
                                }
                            });
                    } else {
                        biayaSelect.innerHTML = '<option value="">-- Pilih Biaya --</option>';
                    }
                });
            });
        </script>
    @endpush
@endsection
