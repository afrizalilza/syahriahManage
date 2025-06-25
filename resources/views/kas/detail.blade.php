@extends('layouts.main')
@section('title', 'Detail Kas')
@section('container')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="page-header rounded">
                    <div class="container py-2">
                        <h2><i class="fas fa-wallet me-2"></i>Detail Kas: <span
                                class="text-primary">{{ $biaya->nama_biaya }}</span></h2>
                        <p class="lead mb-0">Rekap pemasukan (otomatis dari pembayaran), pengeluaran, dan saldo kas secara
                            real time.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-body text-center">
                        <div class="fw-bold text-muted">Saldo Kas</div>
                        <div class="fs-3 text-success fw-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-body text-center">
                        <div class="fw-bold text-muted">Total Pemasukan</div>
                        <div class="fs-5 text-primary fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-body text-center">
                        <div class="fw-bold text-muted">Total Pengeluaran</div>
                        <div class="fs-5 text-danger fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Grafik Kas Bulanan --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header bg-blue-medium text-white fw-bold"><i class="fas fa-chart-bar me-2"></i> Grafik
                        Kas Bulanan ({{ date('Y') }})</div>
                    <div class="card-body">
                        <canvas id="kasChart" style="height:220px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <!-- Filter Pemasukan -->
            <div class="col-md-6">
                <form method="GET" action="" class="mb-3 bg-white p-3 rounded shadow-sm border">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Periode (Bulan/Tahun)</label>
                            <input type="month" name="periode" class="form-control"
                                value="{{ request('periode', date('Y-m')) }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Santri</label>
                            <input type="text" name="santri" class="form-control" value="{{ request('santri') }}"
                                placeholder="Nama santri...">
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center px-3">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('kas.detail', $biaya->id) }}"
                            class="btn btn-secondary btn-sm d-flex align-items-center px-3">
                            <i class="fas fa-sync-alt me-1"></i>Reset
                        </a>
                    </div>
                </form>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-blue-medium text-white d-flex align-items-center">
                        <span class="me-2 rounded-circle d-flex align-items-center justify-content-center"
                            style="background:#e3fcec;width:32px;height:32px;">
                            <i class="fas fa-arrow-down text-success"></i>
                        </span>
                        <span class="fw-bold">Pemasukan Kas (Otomatis dari Pembayaran)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-blue">
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Santri</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemasukan as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d/m/Y') }}</td>
                                            <td>{{ $row->santri->nama ?? '-' }}</td>
                                            <td><span class="badge bg-success fw-normal">Rp
                                                    {{ number_format($row->jumlah_bayar, 0, ',', '.') }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada pemasukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Pengeluaran -->
            <div class="col-md-6">
                <form method="GET" action="" class="mb-3 bg-white p-3 rounded shadow-sm border">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Nama Pengeluaran</label>
                            <input type="text" name="nama_pengeluaran" class="form-control"
                                value="{{ request('nama_pengeluaran') }}" placeholder="Nama pengeluaran...">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tanggal/Bulan/Tahun</label>
                            <input type="date" name="tanggal_pengeluaran" class="form-control"
                                value="{{ request('tanggal_pengeluaran') }}">
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center px-3">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('kas.detail', $biaya->id) }}"
                            class="btn btn-secondary btn-sm d-flex align-items-center px-3">
                            <i class="fas fa-sync-alt me-1"></i>Reset
                        </a>
                    </div>
                </form>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-blue-medium text-white d-flex align-items-center justify-content-between">
                        <span class="d-flex align-items-center">
                            <span class="me-2 rounded-circle d-flex align-items-center justify-content-center"
                                style="background:#ffe9e9;width:32px;height:32px;">
                                <i class="fas fa-arrow-up text-danger"></i>
                            </span>
                            <span class="fw-bold">Pengeluaran Kas</span>
                        </span>
                        <a href="{{ route('kas.pengeluaran.create', $biaya->id) }}"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="fas fa-plus"></i>
                            <span>Tambah</span>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-blue">
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        @if (auth()->user()->role === 'admin')
                                            <th>Unit</th>
                                        @endif
                                        <th style="width:72px;">Bukti</th>
                                        <th style="width:80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengeluaran as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                            <td>{{ $row->nama }}</td>
                                            <td><span class="badge bg-danger fw-normal">Rp
                                                    {{ number_format($row->nominal, 0, ',', '.') }}</span></td>
                                            <td>{{ $row->keterangan }}</td>
                                            @if (auth()->user()->role === 'admin')
                                                <td>
                                                    @if ($row->unit)
                                                        <span
                                                            class="badge bg-{{ $row->unit == 'putra' ? 'primary' : 'pink' }}">{{ ucfirst($row->unit) }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if ($row->bukti)
                                                    <a href="{{ asset('storage/' . $row->bukti) }}" target="_blank"
                                                        class="btn btn-info btn-sm p-2 d-flex align-items-center justify-content-center"
                                                        title="Lihat Bukti">
                                                        <i class="fas fa-image"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('kas.pengeluaran.edit', $row->id) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('kas.pengeluaran.destroy', [$biaya->id, $row->id]) }}"
                                                        class="d-inline form-confirm"
                                                        data-message="Yakin hapus pengeluaran ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada pengeluaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pengeluaran (dynamic content) -->
        <div class="modal fade" id="modalEditPengeluaran" tabindex="-1" aria-labelledby="modalEditPengeluaranLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content shadow-lg border-0 rounded-4 needs-validation" method="POST"
                    enctype="multipart/form-data" id="formEditPengeluaran" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pengeluaran_id" id="edit-pengeluaran-id">
                    <div class="modal-header bg-warning text-white rounded-top-4">
                        <h5 class="modal-title fw-semibold" id="modalEditPengeluaranLabel"><i
                                class="fas fa-edit me-2"></i>Edit Pengeluaran Kas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light-subtle rounded-bottom-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control rounded-pill" name="tanggal" id="edit-tanggal"
                                    required>
                                <div class="invalid-feedback">Tanggal wajib diisi.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nominal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control rounded-pill" name="nominal" id="edit-nominal"
                                    required min="0" placeholder="Rp">
                                <div class="invalid-feedback">Nominal wajib diisi.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nama Pengeluaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-pill" name="nama" id="edit-nama"
                                    required placeholder="Contoh: Beli Beras">
                                <div class="invalid-feedback">Nama pengeluaran wajib diisi.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control rounded-3" name="keterangan" id="edit-keterangan" rows="2"
                                    placeholder="Opsional"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Upload Bukti (foto/nota)</label>
                                <input type="file" class="form-control rounded-pill" name="bukti" accept="image/*"
                                    id="edit-bukti" onchange="previewEditBukti(event)">
                                <div class="mt-2" id="edit-bukti-preview"></div>
                                <div class="mt-1">
                                    <span id="current-bukti-area"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light-subtle rounded-bottom-4 border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal"><i
                                class="fas fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4"><i
                                class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Script -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const bulanLabels = @json($bulanLabels);
            const pemasukanData = @json($pemasukanPerBulan);
            const pengeluaranData = @json($pengeluaranPerBulan);
            const ctx = document.getElementById('kasChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                            label: 'Pemasukan',
                            backgroundColor: '#2193b0',
                            data: pemasukanData,
                        },
                        {
                            label: 'Pengeluaran',
                            backgroundColor: '#e74c3c',
                            data: pengeluaranData,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
        <script>
            // Preview bukti upload
            function previewBukti(event) {
                const input = event.target;
                const preview = document.getElementById('bukti-preview');
                preview.innerHTML = '';
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML =
                            `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width:150px;">`;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            // Preview bukti upload (edit)
            function previewEditBukti(event) {
                const input = event.target;
                const preview = document.getElementById('edit-bukti-preview');
                preview.innerHTML = '';
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML =
                            `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width:150px;">`;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            // Edit pengeluaran: isi modal
            $('.btn-edit-pengeluaran').on('click', function() {
                const id = $(this).data('id'); // id pengeluaran
                $('#edit-pengeluaran-id').val(id); // SET hidden input
                const tanggal = $(this).data('tanggal');
                const nama = $(this).data('nama');
                const nominal = $(this).data('nominal');
                const keterangan = $(this).data('keterangan');
                const bukti = $(this).data('bukti');
                // PASTIKAN: action form edit SELALU benar
                var urlUpdate = `{{ route('kas.pengeluaran.update', ['pengeluaran' => '___ID___']) }}`;
                $('#formEditPengeluaran').attr('action', urlUpdate.replace('___ID___', id));
                $('#edit-tanggal').val(tanggal);
                $('#edit-nama').val(nama);
                $('#edit-nominal').val(nominal);
                $('#edit-keterangan').val(keterangan);
                $('#edit-bukti').val('');
                $('#edit-bukti-preview').html('');
                if (bukti) {
                    $('#current-bukti-area').html(`<a href='{{ asset('storage') }}/` + bukti +
                        `' target='_blank' class='btn btn-outline-info btn-sm'><i class='fas fa-image'></i> Lihat Bukti Lama</a>`
                    );
                } else {
                    $('#current-bukti-area').html('<span class="text-muted">Tidak ada bukti lama</span>');
                }
            });
            // PATCH: Paksa action form edit SELALU benar saat submit (ambil dari hidden input)
            $('#formEditPengeluaran').on('submit', function(e) {
                var id = $('#edit-pengeluaran-id').val();
                var urlUpdate = `{{ route('kas.pengeluaran.update', ['pengeluaran' => '___ID___']) }}`;
                $(this).attr('action', urlUpdate.replace('___ID___', id));
            });
            // Validasi form bootstrap
            (function() {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        </script>
    @endsection
