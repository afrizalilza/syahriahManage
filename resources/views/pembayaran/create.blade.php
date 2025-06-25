@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Data Pembayaran</div>
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
                        <form action="{{ route('pembayaran.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="santri_id" class="form-label">Santri</label>
                                <select class="form-control" id="santri_id" name="santri_id" required
                                    onchange="loadAvailableBiaya()">
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach ($santris as $santri)
                                        <option value="{{ $santri->id }}"
                                            {{ old('santri_id') == $santri->id ? 'selected' : '' }}>{{ $santri->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="periode" class="form-label">Periode</label>
                                <input type="month" class="form-control" id="periode" name="periode"
                                    value="{{ old('periode') }}" required onchange="loadAvailableBiaya()">
                            </div>
                            <div class="mb-3">
                                <label for="biaya_id" class="form-label">Biaya</label>
                                <select class="form-control" id="biaya_id" name="biaya_id[]" multiple required>
                                    <!-- Akan diisi via AJAX -->
                                </select>
                                <small class="text-muted">Tekan dan tahan tombol <b>Ctrl</b> (Windows) atau <b>Cmd</b> (Mac)
                                    untuk memilih lebih dari satu jenis biaya.</small>
                            </div>
                            <div id="jumlah-bayar-wrapper"></div>
                            <div class="mb-3">
                                <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar"
                                    value="{{ old('tanggal_bayar') }}" required>
                            </div>
                            @if (auth()->user()->role == 'admin')
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select name="unit" id="unit" class="form-select" required>
                                        <option value="">-- Pilih Unit --</option>
                                        <option value="putra" {{ old('unit') == 'putra' ? 'selected' : '' }}>Putra</option>
                                        <option value="putri" {{ old('unit') == 'putri' ? 'selected' : '' }}>Putri</option>
                                    </select>
                                </div>
                            @endif
                            {{-- Hapus input status karena status sudah otomatis di-backend --}}
                            {{-- <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Lunas" {{ old('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Sebagian" {{ old('status') == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select> --}}
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function loadAvailableBiaya() {
                const santriId = document.getElementById('santri_id').value;
                const periode = document.getElementById('periode').value;
                const biayaSelect = document.getElementById('biaya_id');
                if (santriId && periode) {
                    biayaSelect.innerHTML = '<option>Memuat data...</option>';
                    fetch(`/ajax/available-biaya?santri_id=${santriId}&periode=${periode}`)
                        .then(resp => resp.json())
                        .then(data => {
                            biayaSelect.innerHTML = '';
                            if (data.length === 0) {
                                biayaSelect.innerHTML = '<option value="">Semua biaya sudah lunas/dibebaskan</option>';
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
            }

            function renderJumlahBayar() {
                const biayaSelect = document.getElementById('biaya_id');
                const wrapper = document.getElementById('jumlah-bayar-wrapper');
                wrapper.innerHTML = '';
                const selected = Array.from(biayaSelect.selectedOptions).map(opt => opt.value);
                if (selected.length === 0) return;
                // Data nominal biaya dari blade
                const biayaNominals = @json($biayaNominals);
                const biayaNames = @json($biayas->pluck('nama_biaya', 'id'));
                const oldJumlah = @json(old('jumlah_bayar'));
                selected.forEach(biayaId => {
                    const nominal = biayaNominals[biayaId] || 0;
                    const nama = biayaNames[biayaId] || '';
                    let selectedVal = nominal;
                    if (oldJumlah && oldJumlah[biayaId]) selectedVal = oldJumlah[biayaId];
                    const div = document.createElement('div');
                    div.className = 'mb-3 jumlah-bayar-item';
                    div.setAttribute('data-biaya-id', biayaId);
                    div.innerHTML = `
            <label class="form-label">Jumlah Bayar untuk ${nama}</label>
            <select class="form-control jumlah-bayar-select" name="jumlah_bayar[${biayaId}]" required>
                <option value="${nominal}" ${selectedVal == nominal ? 'selected' : ''}>${nominal.toLocaleString('id-ID')}</option>
                <option value="lainnya" ${selectedVal != nominal ? 'selected' : ''}>Lainnya...</option>
            </select>
            <input type="number" class="form-control mt-2 jumlah-bayar-manual ${selectedVal != nominal ? '' : 'd-none'}" name="jumlah_bayar_manual[${biayaId}]" placeholder="Masukkan jumlah bayar manual" value="${selectedVal != nominal ? selectedVal : ''}" ${selectedVal != nominal ? 'required' : ''}>
        `;
                    wrapper.appendChild(div);
                });
                // Add event listeners
                wrapper.querySelectorAll('.jumlah-bayar-select').forEach(function(select) {
                    select.addEventListener('change', function() {
                        const parent = this.closest('.jumlah-bayar-item');
                        const manualInput = parent.querySelector('.jumlah-bayar-manual');
                        if (this.value === 'lainnya') {
                            manualInput.classList.remove('d-none');
                            manualInput.required = true;
                        } else {
                            manualInput.classList.add('d-none');
                            manualInput.required = false;
                        }
                    });
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('santri_id').addEventListener('change', function() {
                    loadAvailableBiaya();
                    setTimeout(renderJumlahBayar, 300); // Tunggu biaya terisi via AJAX
                });
                document.getElementById('periode').addEventListener('change', function() {
                    loadAvailableBiaya();
                    setTimeout(renderJumlahBayar, 300);
                });
                document.getElementById('biaya_id').addEventListener('change', renderJumlahBayar);
                // Trigger awal jika sudah ada data lama
                loadAvailableBiaya();
                setTimeout(renderJumlahBayar, 400);
            });
        </script>
    @endpush
@endsection
