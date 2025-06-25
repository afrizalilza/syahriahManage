@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit/Pelunasan Pembayaran</div>
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
                        <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="santri_id" class="form-label">Santri</label>
                                <select class="form-control" id="santri_id" name="santri_id" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach ($santris as $santri)
                                        <option value="{{ $santri->id }}"
                                            {{ old('santri_id', $pembayaran->santri_id) == $santri->id ? 'selected' : '' }}>
                                            {{ $santri->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="biaya_id" class="form-label">Biaya</label>
                                <select class="form-control" id="biaya_id" name="biaya_id[]" multiple required>
                                    <!-- Akan diisi via AJAX -->
                                </select>
                                <small class="text-muted">Tekan dan tahan tombol <b>Ctrl</b> (Windows) atau <b>Cmd</b> (Mac)
                                    untuk memilih lebih dari satu jenis biaya.</small>
                            </div>
                            <div id="jumlah-bayar-wrapper">
                                @if (old('biaya_id', $selectedBiaya ?? []))
                                    @foreach (old('biaya_id', $selectedBiaya ?? []) as $biayaId)
                                        <div class="mb-3 jumlah-bayar-item" data-biaya-id="{{ $biayaId }}">
                                            <label class="form-label">Jumlah Bayar untuk
                                                {{ $biayas->find($biayaId)->nama_biaya ?? '' }}</label>
                                            <select class="form-control" name="jumlah_bayar[{{ $biayaId }}]" required>
                                                <option value="{{ $biayaNominals[$biayaId] ?? 0 }}"
                                                    {{ old('jumlah_bayar.' . $biayaId, $selectedJumlah[$biayaId] ?? null) == ($biayaNominals[$biayaId] ?? 0) ? 'selected' : '' }}>
                                                    {{ number_format($biayaNominals[$biayaId] ?? 0, 0, ',', '.') }}
                                                </option>
                                                <option value="lainnya"
                                                    {{ isset($selectedJumlah[$biayaId]) && $selectedJumlah[$biayaId] != ($biayaNominals[$biayaId] ?? 0) ? 'selected' : '' }}>
                                                    Lainnya...</option>
                                            </select>
                                            <input type="number"
                                                class="form-control mt-2 jumlah-bayar-manual {{ isset($selectedJumlah[$biayaId]) && $selectedJumlah[$biayaId] != ($biayaNominals[$biayaId] ?? 0) ? '' : 'd-none' }}"
                                                name="jumlah_bayar_manual[{{ $biayaId }}]"
                                                value="{{ isset($selectedJumlah[$biayaId]) && $selectedJumlah[$biayaId] != ($biayaNominals[$biayaId] ?? 0) ? $selectedJumlah[$biayaId] : '' }}"
                                                placeholder="Masukkan jumlah bayar manual">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="periode" class="form-label">Periode</label>
                                <input type="month" class="form-control" id="periode" name="periode"
                                    value="{{ old('periode', $pembayaran->periode) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar"
                                    value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar) }}" required>
                            </div>
                            {{-- Hapus input status karena status sudah otomatis di-backend --}}
                            {{-- <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Lunas" {{ old('status', $pembayaran->status) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Sebagian" {{ old('status', $pembayaran->status) == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                            <option value="Pending" {{ old('status', $pembayaran->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select> --}}
                            @if (auth()->user()->role == 'admin')
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-control" id="unit" name="unit" required>
                                        <option value="">-- Pilih Unit --</option>
                                        <option value="putra"
                                            {{ old('unit', $pembayaran->unit) == 'putra' ? 'selected' : '' }}>Putra
                                        </option>
                                        <option value="putri"
                                            {{ old('unit', $pembayaran->unit) == 'putri' ? 'selected' : '' }}>Putri
                                        </option>
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const biayaSelect = document.getElementById('biaya_id');
                                    const wrapper = document.getElementById('jumlah-bayar-wrapper');
                                    const biayaNominals = @json($biayaNominals);
                                    const biayaNames = @json($biayas->pluck('nama_biaya', 'id'));

                                    function renderJumlahBayar() {
                                        wrapper.innerHTML = '';
                                        Array.from(biayaSelect.selectedOptions).forEach(option => {
                                            const biayaId = option.value;
                                            const biayaName = biayaNames[biayaId] || '';
                                            const nominal = biayaNominals[biayaId] || 0;
                                            let selected = '';
                                            let manualVal = '';
                                            if (@json(old('jumlah_bayar')) && @json(old('jumlah_bayar'))[biayaId]) {
                                                selected = @json(old('jumlah_bayar'))[biayaId];
                                            } else if (@json($selectedJumlah)[biayaId]) {
                                                selected = @json($selectedJumlah)[biayaId];
                                            }
                                            if (selected !== '' && selected != nominal) {
                                                manualVal = selected;
                                            }
                                            const div = document.createElement('div');
                                            div.className = 'mb-3 jumlah-bayar-item';
                                            div.setAttribute('data-biaya-id', biayaId);
                                            div.innerHTML = `<label class=\"form-label\">Jumlah Bayar untuk ${biayaName}</label>` +
                                                `<select class=\"form-control jumlah-bayar-select\" name=\"jumlah_bayar[${biayaId}]\" required>` +
                                                `<option value=\"${nominal}\" ${(selected == nominal) ? 'selected' : ''}>${nominal.toLocaleString('id-ID')}</option>` +
                                                `<option value=\"lainnya\" ${(manualVal !== '') ? 'selected' : ''}>Lainnya...</option>` +
                                                `</select>` +
                                                `<input type=\"number\" class=\"form-control mt-2 jumlah-bayar-manual ${(manualVal !== '') ? '' : 'd-none'}\" name=\"jumlah_bayar_manual[${biayaId}]\" value=\"${manualVal}\" placeholder=\"Masukkan jumlah bayar manual\">`;
                                            wrapper.appendChild(div);
                                        });
                                        addDropdownListeners();
                                    }

                                    function addDropdownListeners() {
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
                                    biayaSelect.addEventListener('change', renderJumlahBayar);
                                    renderJumlahBayar();
                                });
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadAvailableBiayaEdit() {
            const santriId = document.getElementById('santri_id').value;
            const periode = document.getElementById('periode').value;
            const biayaSelect = document.getElementById('biaya_id');
            const selectedBiaya = @json(old('biaya_id', $selectedBiaya ?? []));
            if (santriId && periode) {
                biayaSelect.innerHTML = '<option>Memuat data...</option>';
                fetch(`/ajax/available-biaya?santri_id=${santriId}&periode=${periode}`)
                    .then(resp => resp.json())
                    .then(data => {
                        biayaSelect.innerHTML = '';
                        // Gabungkan biaya yang sudah dipilih di transaksi ini agar tetap bisa diedit
                        let allBiaya = data.concat(@json($biayas->whereIn('id', $selectedBiaya ?? [])->values()));
                        // Hapus duplikat id
                        const unique = {};
                        allBiaya.forEach(b => {
                            unique[b.id] = b;
                        });
                        allBiaya = Object.values(unique);
                        if (allBiaya.length === 0) {
                            biayaSelect.innerHTML = '<option value="">Semua biaya sudah lunas/dibebaskan</option>';
                        } else {
                            allBiaya.forEach(biaya => {
                                const opt = document.createElement('option');
                                opt.value = biaya.id;
                                opt.textContent = biaya.nama_biaya;
                                if (selectedBiaya.includes(biaya.id)) opt.selected = true;
                                biayaSelect.appendChild(opt);
                            });
                        }
                    });
            } else {
                biayaSelect.innerHTML = '<option value="">-- Pilih Biaya --</option>';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('santri_id').addEventListener('change', loadAvailableBiayaEdit);
            document.getElementById('periode').addEventListener('change', loadAvailableBiayaEdit);
            // Trigger awal jika sudah ada data lama
            loadAvailableBiayaEdit();
        });
    </script>
@endpush
