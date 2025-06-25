@extends('layouts.main')

@section('container')
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded bg-light p-3 mb-2">
                <h2 class="mb-1"><i class="fas fa-calendar me-2"></i>Laporan Tahunan</h2>
                <p class="lead mb-0">Laporan pembayaran syahriah, pengeluaran, dan statistik keuangan per tahun</p>
            </div>
        </div>
    </div>
    {{-- SUMMARY CARDS --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-wallet fa-2x mb-2"></i>
                    <h6 class="card-title">Total Pemasukan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    <small>Tahun {{ $tahun }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                    <h6 class="card-title">Total Pengeluaran</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <small>Tahun {{ $tahun }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-coins fa-2x mb-2"></i>
                    <h6 class="card-title">Saldo Akhir Tahun</h6>
                    <h3 class="mb-0">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                    <small>Pemasukan - Pengeluaran</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                    <h6 class="card-title">Santri Lunas Tahunan</h6>
                    <h3 class="mb-0">{{ $persenLunasTahun }}%</h3>
                    <small>{{ $santriLunasTahun }} lunas / {{ $santriAktif }} aktif</small>
                </div>
            </div>
        </div>
    </div>
    {{-- FILTER --}}
    <div class="row mb-4">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="mb-0">Filter Laporan</h5>
                    <div>
                        <a class="btn btn-success me-2"
                            href="{{ route('laporan.tahunan.export-excel', ['tahun' => $tahun]) }}">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                        <a class="btn btn-danger" href="{{ route('laporan.tahunan.export-pdf', ['tahun' => $tahun]) }}"
                            target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="row g-3 align-items-end">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <label class="form-label">Tahun</label>
                            <input type="number" class="form-control" name="tahun" min="2000" max="2100"
                                placeholder="Tahun" value="{{ request('tahun', $tahun) }}">
                        </div>
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-sync-alt me-2"></i>Update Laporan
                            </button>
                            @if (request()->has('tahun'))
                                <a href="{{ url()->current() }}" class="btn btn-link text-danger" title="Reset Filter">
                                    <i class="fas fa-times-circle fa-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- GRAFIK & PIE CHART --}}
    <div class="row mb-4" style="min-height:520px;">
        <div class="col-md-8 d-flex">
            <div class="card h-100 w-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Grafik Pemasukan, Pengeluaran & Saldo Berjalan Tahun {{ $tahun }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:500px; width:100%;">
                        <canvas id="lineChart" height="480" style="width:100% !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card h-100 w-100 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Statistik Kelunasan Tahunan</h5>
                </div>
                <div class="card-body d-flex flex-column h-100 justify-content-center align-items-center">
                    <div class="mb-2">Statistik Kelunasan Tahunan</div>
                    <canvas id="pieChart" width="140" height="140"></canvas>
                    <div class="small text-muted mt-3">{{ $santriLunasTahun }} lunas, {{ $santriBelumTahun }} belum lunas
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- TABEL REKAP --}}
    <div class="row mb-4 g-3 align-items-stretch" style="align-items: stretch;">
        <div class="col-md-6 d-flex mb-4 mb-md-0">
            <div class="card shadow-sm h-100 w-100 d-flex flex-column" id="card-pengeluaran">
                <div class="card-header bg-danger text-white d-flex align-items-center">
                    <i class="fas fa-money-bill-wave fa-lg me-2"></i>
                    <h5 class="mb-0">Rekap Pengeluaran per Bulan</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between flex-grow-1"
                    style="height:100%; min-height:1px;">
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table table-hover">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Item Pengeluaran</th>
                                    <th>Total Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekapPengeluaranBulanan as $row)
                                    <tr>
                                        <td>{{ $row['bulan'] }}</td>
                                        <td>
                                            @if (isset($row['items']) && is_array($row['items']) && count($row['items']))
                                                <ul class="mb-0 ps-3">
                                                    @foreach ($row['items'] as $nama)
                                                        <li>{{ $nama }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total Pengeluaran</strong></td>
                                    <td><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex">
            <div class="card shadow-sm h-100 w-100 d-flex flex-column" id="card-pembayaran">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar fa-lg me-2"></i>
                    <h5 class="mb-0">Rekap Pembayaran per Bulan</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between flex-grow-1"
                    style="height:100%; min-height:1px;">
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Bulan</th>
                                    @foreach ($biayas as $biaya)
                                        <th class="text-truncate" style="max-width:120px;">{{ $biaya->nama_biaya }} ({{ $biaya->unit }})</th>
                                    @endforeach
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekapBulanan as $row)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::create()->month($row['bulan'])->isoFormat('MMMM') }}</td>
                                        @foreach ($biayas as $biaya)
                                            @php $key = $biaya->nama_biaya . ' (' . $biaya->unit . ')'; @endphp
                                            <td class="text-end">Rp {{ number_format($row[$key] ?? 0, 0, ',', '.') }}</td>
                                        @endforeach
                                        <td class="text-end"><strong>Rp
                                                {{ number_format($row['total'], 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td><strong>Total</strong></td>
                                    @foreach ($biayas as $biaya)
                                        @php $key = $biaya->nama_biaya . ' (' . $biaya->unit . ')'; @endphp
                                        <td class="text-end"><strong>Rp
                                                {{ number_format($totalPerJenis[$key] ?? 0, 0, ',', '.') }}</strong></td>
                                    @endforeach
                                    <td class="text-end"><strong>Rp
                                            {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            // Samakan tinggi kedua card
            var left = document.getElementById('card-pengeluaran');
            var right = document.getElementById('card-pembayaran');
            if (left && right) {
                var maxHeight = Math.max(left.offsetHeight, right.offsetHeight);
                left.style.height = right.style.height = maxHeight + 'px';
            }
        });
    </script>
    <style>
        #card-pengeluaran,
        #card-pembayaran {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .row.mb-4.g-3.align-items-stretch {
            align-items: stretch !important;
        }

        /* Perbesar jarak antar baris hanya untuk tabel pengeluaran */
        #card-pengeluaran table tr td,
        #card-pengeluaran table tr th {
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
            vertical-align: middle !important;
        }

        #card-pengeluaran table thead th {
            background-color: #dc3545 !important;
            color: #fff !important;
            font-weight: bold;
        }
    </style>
<style>
@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.95rem;
    }
    .table td, .table th {
        vertical-align: middle;
        white-space: nowrap;
    }
}
</style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script>
        // Daftarkan plugin datalabels secara global
        Chart.register(ChartDataLabels);

        document.addEventListener('DOMContentLoaded', function() {

            // Data dari Controller
            const bulanLabels = @json($bulanLabels ?? []);
            const totalPerBulan = @json($totalPerBulan ?? []);
            const totalPengeluaranPerBulan = @json($totalPengeluaranPerBulan ?? []);
            const saldoBerjalan = @json($saldoBerjalan ?? []);
            const santriLunasTahun = @json($santriLunasTahun ?? 0);
            const santriBelumTahun = @json($santriBelumTahun ?? 0);

            // Grafik Pemasukan, Pengeluaran, dan Saldo
            try {
                const ctxLine = document.getElementById('lineChart');
                if (ctxLine) {
                    new Chart(ctxLine, {
                        type: 'line',
                        data: {
                            labels: bulanLabels,
                            datasets: [{
                                    label: 'Pemasukan',
                                    data: totalPerBulan,
                                    borderColor: 'rgb(75, 192, 192)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    tension: 0.1,
                                    fill: true,
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: totalPengeluaranPerBulan,
                                    borderColor: 'rgb(255, 99, 132)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    tension: 0.1,
                                    fill: true,
                                },
                                {
                                    label: 'Saldo Berjalan',
                                    data: saldoBerjalan,
                                    borderColor: 'rgb(54, 162, 235)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    tension: 0.1,
                                    fill: false, // Saldo biasanya tidak di-fill
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Grafik Keuangan Bulanan'
                                }
                            }
                        }
                    });
                }
            } catch (e) {
                console.error("Gagal membuat Line Chart:", e);
            }

            // Pie Chart Statistik Kelunasan
            try {
                const ctxPie = document.getElementById('pieChart');
                if (ctxPie) {
                    // Hanya gambar grafik jika ada data
                    if (santriLunasTahun > 0 || santriBelumTahun > 0) {
                        new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: ['Lunas', 'Belum Lunas'],
                                datasets: [{
                                    label: 'Status Kelunasan',
                                    data: [santriLunasTahun, santriBelumTahun],
                                    backgroundColor: [
                                        'rgb(75, 192, 192)',
                                        'rgb(255, 99, 132)'
                                    ],
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Statistik Kelunasan Tahunan'
                                    },
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            let sum = 0;
                                            let dataArr = ctx.chart.data.datasets[0].data;
                                            dataArr.map(data => {
                                                sum += data;
                                            });
                                            let percentage = (value * 100 / sum).toFixed(2) + "%";
                                            return percentage;
                                        },
                                        color: '#fff',
                                    }
                                }
                            }
                        });
                    } else {
                        // Tampilkan pesan jika tidak ada data
                        const context = ctxPie.getContext('2d');
                        context.textAlign = 'center';
                        context.textBaseline = 'middle';
                        context.font = "16px Arial";
                        context.fillText("Tidak ada data untuk ditampilkan", ctxPie.width / 2, ctxPie.height / 2);
                    }
                }
            } catch (e) {
                console.error("Gagal membuat Pie Chart:", e);
            }
        });
    </script>
@endpush
