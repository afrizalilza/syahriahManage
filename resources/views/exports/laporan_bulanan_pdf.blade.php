@php
    use Illuminate\Support\Carbon;
@endphp
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .summary-cards {
            display: flex;
            gap: 18px;
            margin-bottom: 24px;
        }

        .card {
            background: #f0f7fa;
            border-radius: 16px;
            box-shadow: 0 4px 16px #2193b022;
            padding: 18px 24px;
            min-width: 200px;
            color: #0d294e;
        }

        .card .label {
            color: #2193b0;
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 6px;
        }

        .card .value {
            font-size: 2em;
            font-weight: bold;
        }

        .card .desc {
            font-size: 0.98em;
            color: #6b7b8a;
        }

        .badge {
            display: inline-block;
            background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
            color: #fff;
            border-radius: 8px;
            font-size: 0.95em;
            padding: 2px 12px;
            margin-top: 4px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #b5d0e6;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background: #e0ecff;
            color: #2193b0;
            font-weight: 700;
        }

        .sticky-col {
            background: #f7fbff;
        }

        .nominal {
            text-align: right;
        }

        .status-lunas {
            color: #2193b0;
            font-weight: bold;
        }

        .status-belum-lunas,
        .status-belum {
            color: #e74c3c;
            font-weight: bold;
        }

        .status-lunas-bebas-biaya {
            color: #27ae60;
            font-weight: bold;
        }

        .tanggal {
            font-size: 0.97em;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="summary-cards">
        <div class="card">
            <div class="label">Total Pemasukan</div>
            <div class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="desc">Bulan {{ Carbon::parse($periode . '-01')->translatedFormat('F Y') }}</div>
        </div>
        <div class="card">
            <div class="label">Sudah Bayar</div>
            <div class="value">{{ $sudahBayar }}</div>
            <div class="badge">{{ $sudahBayar }} Santri</div>
            <div class="desc">Jumlah santri yang sudah melakukan pembayaran</div>
        </div>
        <div class="card">
            <div class="label">Belum Bayar</div>
            <div class="value">{{ $belumBayar }}</div>
            <div class="badge">{{ $belumBayar }} Santri</div>
            <div class="desc">Santri yang belum melakukan pembayaran</div>
        </div>
        <div class="card">
            <div class="label">Persentase Lunas</div>
            <div class="value">{{ $persenLunas }}%</div>
            <div class="desc">Tingkat pembayaran lunas bulan ini</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th class="sticky-col">Nama Santri</th>
                @foreach ($biayasFiltered as $biaya)
                    <th>{{ $biaya->nama_biaya }}</th>
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
                    <td class="sticky-col">
                        {{ $row['nama'] }}
                        @if (!empty($row['bebas_biaya']) && $row['bebas_biaya'])
                            <span class="badge" title="Santri bebas biaya">Bebas Biaya</span>
                        @endif
                    </td>
                    @foreach ($biayasFiltered as $biaya)
                        <td class="nominal">
                            {{ $row['byJenis'][$biaya->nama_biaya] > 0 ? 'Rp ' . number_format($row['byJenis'][$biaya->nama_biaya], 0, ',', '.') : '-' }}
                        </td>
                    @endforeach
                    <td class="nominal">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                    <td class="status-{{ strtolower(str_replace([' ', '(', ')'], ['-', '', ''], $row['status'])) }}">
                        {{ $row['status'] }}
                    </td>
                    <td class="tanggal">{{ $row['tanggal'] ? Carbon::parse($row['tanggal'])->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
