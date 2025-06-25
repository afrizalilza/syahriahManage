<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export PDF Tunggakan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #222;
            padding: 4px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h2>Rekapitulasi Tunggakan Periode {{ $periode }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Jumlah Tunggakan</th>
                <th>Rincian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tunggakans->where('jumlah_tunggakan', '>', 0) as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['santri']->nama }}</td>
                    <td>{{ $item['santri']->nis }}</td>
                    <td>Rp {{ number_format($item['jumlah_tunggakan'], 0, ',', '.') }}</td>
                    <td>
                        @foreach ($item['rincian'] as $rincian)
                            {{ $rincian['biaya']->nama }}: Rp {{ number_format($rincian['sisa'], 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table style="width: 50%;">
        <tr>
            <th>Total Santri</th>
            <td>{{ $tunggakans->where('jumlah_tunggakan', '>', 0)->count() }}</td>
        </tr>
        <tr>
            <th>Total Tunggakan</th>
            <td>Rp
                {{ number_format($tunggakans->where('jumlah_tunggakan', '>', 0)->sum('jumlah_tunggakan'), 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th>Rata-rata Tunggakan</th>
            <td>Rp
                {{ $tunggakans->where('jumlah_tunggakan', '>', 0)->count() > 0 ? number_format(round($tunggakans->where('jumlah_tunggakan', '>', 0)->sum('jumlah_tunggakan') / $tunggakans->where('jumlah_tunggakan', '>', 0)->count()), 0, ',', '.') : 0 }}
            </td>
        </tr>
    </table>
</body>

</html>
