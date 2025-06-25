<!DOCTYPE html>
<html>

<head>
    <title>Laporan Tahunan Syahriah Tahun {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #318FB5;
            color: #fff;
        }

        tfoot td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center;">Laporan Tahunan Syahriah Tahun {{ $tahun }}</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pemasukan</th>
                <th>Total Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapBulanan as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromDate($tahun, $row['bulan'], 1)->isoFormat('MMMM') }}</td>
                    <td>Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total Tahun</td>
                <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Saldo Akhir Tahun</td>
                <td>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
