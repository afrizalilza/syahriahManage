@php
    use Illuminate\Support\Carbon;
@endphp
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 12px;
        }

        th {
            background: #eaeaea;
            color: #222;
            font-weight: bold;
        }

        td.nominal {
            text-align: right;
        }

        td.tanggal {
            text-align: center;
        }

        body {
            font-family: Arial, Calibri, sans-serif;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center; margin-bottom:2px;">LAPORAN PEMBAYARAN SYAHRIYAH BULANAN</h2>
    <div style="text-align:center; margin-bottom:16px; font-size:1em; font-weight:bold;">
        Periode: {{ Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:80px;">NIS</th>
                <th style="width:180px;">Nama Santri</th>
                @foreach ($biayasFiltered as $biaya)
                    <th style="width:90px;">{{ $biaya->nama_biaya }}</th>
                @endforeach
                <th style="width:90px;">Total</th>
                <th style="width:80px;">Status</th>
                <th style="width:100px;">Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $jmlSudah = 0;
                $jmlBelum = 0;
            @endphp
            @foreach ($rekap as $row)
                @php
                    $isBelumBayar = empty($row['total']) || $row['total'] == 0;
                    $isIstimewa = !empty($row['bebas_biaya']) && $row['bebas_biaya'];
                    if ($isBelumBayar) {
                        $jmlBelum++;
                    } else {
                        $jmlSudah++;
                    }
                @endphp
                <tr @if ($isBelumBayar && !$isIstimewa) style="background:#fff3cd;" @endif>
                    <td>{{ $i++ }}</td>
                    <td>{{ $row['nis'] }}</td>
                    <td>
                        {{ $row['nama'] }}
                        @if ($isIstimewa)
                            <span style="font-weight:bold;"> (Hak Istimewa)</span>
                        @endif
                    </td>
                    @foreach ($biayasFiltered as $biaya)
                        <td class="nominal">
                            {{ $row['byJenis'][$biaya->nama_biaya] > 0 ? 'Rp ' . number_format($row['byJenis'][$biaya->nama_biaya], 0, ',', '.') : '-' }}
                        </td>
                    @endforeach
                    <td class="nominal">
                        {{ $row['total'] > 0 ? 'Rp ' . number_format($row['total'], 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $row['status'] }}</td>
                    <td class="tanggal">{{ $row['tanggal'] ? Carbon::parse($row['tanggal'])->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="border-collapse:collapse;width:50%;margin-top:12px;">
        <tr>
            <th style="background:#f0f7fa;font-weight:600;color:#333;border:1px solid #b5d0e6;">Total Pemasukan</th>
            <td style="font-weight:bold;color:#009900;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th style="background:#eaeaea;">Sudah Bayar</th>
            <td>{{ $jmlSudah }} Santri</td>
        </tr>
        <tr>
            <th style="background:#fff3cd;">Belum Bayar</th>
            <td>{{ $jmlBelum }} Santri</td>
        </tr>
        <tr>
            <th style="background:#eaeaea;">Persentase Lunas</th>
            <td>{{ $rekap && count($rekap) > 0 ? round(($jmlSudah / count($rekap)) * 100) : 0 }}%</td>
        </tr>
    </table>
    <div style="margin-top:14px;font-size:12px;">
        <span style="background:#fff3cd;border:1px solid #ffc107;padding:2px 8px;">Baris kuning = Santri Belum
            Bayar</span>
    </div>
</body>

</html>
