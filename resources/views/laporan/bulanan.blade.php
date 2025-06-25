@extends('layouts.main')

@section('container')
    @php use Illuminate\Support\Arr; @endphp
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header rounded">
                <div class="container py-2">
                    <h2><i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan</h2>
                    <p class="lead mb-0">Laporan pembayaran syahriah per bulan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Luxury Summary Cards Section -->
        <style>
            .luxury-summary-row {
                display: flex;
                gap: 1.7rem;
                flex-wrap: wrap;
                margin-bottom: 2.2rem;
            }

            .luxury-summary-card {
                flex: 1 1 220px;
                min-width: 220px;
                background: linear-gradient(120deg, #e0ecff 0%, #f0f7fa 100%);
                border-radius: 1.3rem;
                box-shadow: 0 6px 32px rgba(33, 147, 176, 0.13);
                padding: 1.5rem 1.3rem 1.2rem 1.3rem;
                border: none;
                position: relative;
                overflow: hidden;
                margin-bottom: 0.5rem;
                transition: transform 0.18s, box-shadow 0.18s;
            }

            .luxury-summary-card:hover {
                transform: translateY(-6px) scale(1.03);
                box-shadow: 0 12px 36px rgba(33, 147, 176, 0.18);
            }

            .luxury-summary-card .luxury-icon {
                font-size: 2.6rem;
                background: linear-gradient(120deg, #e0ecff 50%, #f0f7fa 100%);
                color: #2193b0;
                border-radius: 1.2rem;
                padding: 0.7rem 1.1rem 0.7rem 0.9rem;
                box-shadow: 0 1px 8px rgba(33, 147, 176, 0.07);
                margin-bottom: 0.7rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .luxury-summary-card .luxury-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #2193b0;
                z-index: 2;
                position: relative;
                margin-bottom: 0.3rem;
                letter-spacing: 0.5px;
            }

            .luxury-summary-card .luxury-value {
                font-size: 2.1rem;
                font-weight: bold;
                color: #0d294e;
                z-index: 2;
                position: relative;
                margin-bottom: 0.1rem;
                line-height: 1.1;
            }

            .luxury-summary-card .luxury-desc {
                font-size: 1.02rem;
                color: #6b7b8a;
                z-index: 2;
                position: relative;
                margin-bottom: 0;
            }

            .luxury-summary-card .luxury-badge {
                display: inline-block;
                background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
                color: #fff;
                font-size: 0.98rem;
                font-weight: 500;
                border-radius: 0.6rem;
                padding: 0.18rem 0.75rem;
                margin-top: 0.2rem;
                margin-bottom: 0.1rem;
                z-index: 2;
                position: relative;
                box-shadow: 0 2px 8px rgba(33, 147, 176, 0.10);
            }

            @media (max-width: 991px) {
                .luxury-summary-row {
                    gap: 1rem;
                }

                .luxury-summary-card {
                    min-width: 170px;
                    padding: 1.1rem 0.7rem 1rem 0.9rem;
                }

                .luxury-summary-card .luxury-title {
                    font-size: 1rem;
                }

                .luxury-summary-card .luxury-value {
                    font-size: 1.3rem;
                }
            }

            @media (max-width: 600px) {
                .luxury-summary-row {
                    flex-direction: column;
                    gap: 0.8rem;
                }

                .luxury-summary-card {
                    min-width: 0;
                    width: 100%;
                }

                .luxury-summary-card .luxury-icon {
                    font-size: 2rem;
                    padding: 0.5rem 0.7rem 0.5rem 0.7rem;
                }
            }
        </style>
        <div class="luxury-summary-row">
            <div class="luxury-summary-card">
                <span class="luxury-icon"><i class="fas fa-wallet"></i></span>
                <div class="luxury-title">Total Pemasukan</div>
                <div class="luxury-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                <div class="luxury-desc">
                    @if (request('jenis'))
                        {{ implode(', ', (array) request('jenis')) }}<br>
                    @endif
                    Bulan {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
                </div>
            </div>
            <div class="luxury-summary-card">
                <span class="luxury-icon"><i class="fas fa-user-check"></i></span>
                <div class="luxury-title">Sudah Bayar</div>
                <div class="luxury-value">{{ $sudahBayar }}</div>
                <div class="luxury-badge">{{ $rekap->count() }} Santri</div>
                <div class="luxury-desc">Jumlah santri yang sudah melakukan pembayaran</div>
            </div>
            <div class="luxury-summary-card">
                <span class="luxury-icon"><i class="fas fa-user-times"></i></span>
                <div class="luxury-title">Belum Bayar</div>
                <div class="luxury-value">{{ $belumBayar }}</div>
                <div class="luxury-badge">{{ $rekap->count() }} Santri</div>
                <div class="luxury-desc">Santri yang belum melakukan pembayaran</div>
            </div>
            <div class="luxury-summary-card">
                <span class="luxury-icon"><i class="fas fa-percentage"></i></span>
                <div class="luxury-title">Persentase Lunas</div>
                <div class="luxury-value">{{ $persenLunas }}%</div>
                <div class="luxury-desc">Tingkat pembayaran lunas bulan ini</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Laporan Pembayaran Bulan
                            {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- FILTER FORM START -->
                        <form method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <label class="form-label">Periode</label>
                                    <input type="month" name="periode" class="form-control"
                                        value="{{ request('periode', $periode) }}">
                                </div>
                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">Semua</option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas
                                        </option>
                                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum
                                            Lunas</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <label class="form-label">Jenis Biaya</label>
                                    <select class="form-select js-jenis-biaya" name="jenis[]" multiple>
                                        @foreach ($biayas->sortBy('nama_biaya') as $biaya)
                                            <option value="{{ $biaya->id }}"
                                                {{ collect(request('jenis'))->contains($biaya->id) ? 'selected' : '' }}>
                                                {{ $biaya->nama_biaya }} ({{ $biaya->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('laporan.bulanan.export-excel', request()->all()) }}"
                                        class="btn btn-success me-2">
                                        <i class="fas fa-file-excel me-2"></i>Export Excel
                                    </a>
                                    <a href="{{ route('laporan.bulanan.export-pdf', request()->all()) }}"
                                        class="btn btn-danger">
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                        <!-- FILTER FORM END -->

                        <!-- Modern Rekap Table Section -->
                        <style>
                            .modern-table-card {
                                background: linear-gradient(120deg, #f0f7fa 60%, #e0ecff 100%);
                                border-radius: 1.1rem;
                                box-shadow: 0 3px 18px rgba(33, 147, 176, 0.07);
                                padding: 1.5rem 1.2rem 1.2rem 1.2rem;
                                margin-bottom: 2rem;
                                border: none;
                            }

                            .modern-table-card .table-header {
                                background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
                                color: #fff;
                                border-radius: 0.8rem 0.8rem 0 0;
                                font-size: 1.13rem;
                                font-weight: bold;
                                padding: 0.8rem 1rem;
                                margin-bottom: 0.7rem;
                                display: flex;
                                align-items: center;
                                gap: 0.6rem;
                                letter-spacing: 0.5px;
                            }

                            .modern-table-card .table {
                                background: transparent;
                                border-radius: 0.8rem;
                                overflow: hidden;
                            }

                            .modern-table-card .table th {
                                text-align: center;
                                vertical-align: middle;
                                font-size: 1.08rem;
                                background: #2193b0;
                                color: #fff;
                                font-weight: 600;
                                border: none;
                                border-radius: 0 !important;
                                box-shadow: none !important;
                            }

                            .modern-table-card .table td {
                                vertical-align: middle;
                                font-size: 1.01rem;
                                background: transparent;
                                border-bottom: 1px solid #e3eaf2;
                                padding: 0.7rem 0.7rem;
                                border-radius: 0 !important;
                                box-shadow: none !important;
                                transition: background 0.2s;
                            }

                            .modern-table-card .table tbody tr:nth-child(odd) td {
                                background: #f8fafc;
                            }

                            .modern-table-card .table tbody tr:nth-child(even) td {
                                background: #f3f6fa;
                            }

                            .modern-table-card .table td.nominal {
                                text-align: right;
                                font-variant-numeric: tabular-nums;
                                font-weight: 600;
                                color: #0d294e;
                                background: transparent;
                                border-radius: 0;
                                min-width: 110px;
                                letter-spacing: 0.5px;
                                padding: 0.7rem 0.9rem;
                                font-size: 1.08rem;
                                border-right: 1px solid #e3eaf2;
                            }

                            .modern-table-card .table td.nominal:last-child {
                                border-right: none;
                            }

                            .modern-table-card .table tr:last-child td {
                                border-bottom: none;
                            }

                            .modern-table-card .table th.sticky-col,
                            .modern-table-card .table td.sticky-col {
                                position: sticky;
                                left: 0;
                                /* background: #2193b0; */
                                z-index: 2;
                            }

                            @media (max-width: 768px) {
                                .modern-table-card {
                                    padding: 1.1rem 0.5rem 0.7rem 0.5rem;
                                }

                                .modern-table-card .table th,
                                .modern-table-card .table td {
                                    padding: 0.5rem 0.5rem;
                                    font-size: 0.97rem;
                                }

                                .modern-table-card .table td.nominal {
                                    min-width: 80px;
                                    padding: 0.5rem 0.5rem;
                                    font-size: 0.97rem;
                                }
                            }
                        </style>
                        <style>
                            .btn-reset-filter {
                                background: #f8f9fa;
                                color: #e74c3c;
                                border: 1px solid #e74c3c;
                                border-radius: 0.5rem;
                                font-weight: 500;
                                padding: 0.38rem 0.95rem 0.38rem 0.9rem;
                                margin-left: 0.6rem;
                                transition: background 0.15s, color 0.15s, border 0.15s;
                                text-decoration: none;
                                display: inline-flex;
                                align-items: center;
                                gap: 0.4rem;
                                font-size: 1rem;
                            }

                            .btn-reset-filter:hover {
                                background: #e74c3c;
                                color: #fff;
                                border-color: #e74c3c;
                                text-decoration: none;
                            }
                        </style>
                        <div class="modern-table-card">
                            <div class="table-header">
                                <i class="fas fa-table"></i> Rekap Pembayaran Santri Bulan
                                {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
                                @if (request('jenis'))
                                    <span class="badge bg-info text-dark ms-2">Filter: Jenis Biaya =
                                        {{ implode(', ', (array) request('jenis')) }}</span>
                                @endif
                            </div>
                            @if ($rekap->isEmpty())
                                <div class="alert alert-warning text-center">Data pembayaran bulan ini kosong.</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th class="sticky-col">Nama Santri</th>
                                            @foreach ($biayasFiltered as $biaya)
                                                <th class="jenis-biaya">{{ $biaya->nama_biaya }} ({{ $biaya->unit }})
                                                </th>
                                            @endforeach
                                            <th class="nominal">Total</th>
                                            <th>Status</th>
                                            <th class="tanggal">Tanggal Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($rekap as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row['nis'] }}</td>
                                                <td class="nama sticky-col text-truncate" style="max-width:120px;">
                                                    {{ $row['nama'] }}
                                                    @if (!empty($row['bebas_biaya']) && $row['bebas_biaya'])
                                                        <span class="badge bg-success ms-2" title="Santri bebas biaya"><i
                                                                class="fas fa-gift"></i> Bebas Biaya</span>
                                                    @endif
                                                </td>
                                                @foreach ($biayasFiltered as $biaya)
                                                    <td class="nominal">
                                                        {{ isset($row['byJenis'][$biaya->id]) && $row['byJenis'][$biaya->id] > 0 ? 'Rp ' . number_format($row['byJenis'][$biaya->id], 0, ',', '.') : '-' }}
                                                    </td>
                                                @endforeach
                                                <td class="nominal">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                                <td class="status-{{ strtolower(str_replace(' ', '-', $row['status'])) }}">
                                                    {{ str_replace('Lunas (Bebas Biaya)', 'Lunas (Bebas Biaya)', $row['status']) }}
                                                </td>
                                                <td class="tanggal">
                                                    {{ $row['tanggal'] ? \Carbon\Carbon::parse($row['tanggal'])->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
@media (max-width: 576px) {
    .modern-table-card .table-responsive {
        font-size: 0.95rem;
    }
    .modern-table-card .table td,
    .modern-table-card .table th {
        vertical-align: middle;
        white-space: nowrap;
    }
}
</style>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-jenis-biaya').select2({
                placeholder: 'Pilih Jenis Biaya',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
