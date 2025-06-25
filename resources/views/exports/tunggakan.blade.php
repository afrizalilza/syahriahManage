<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Santri</th>
            <th>Periode</th>
            <th>Jumlah Tunggakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tunggakans->where('jumlah_tunggakan', '>', 0) as $i => $tunggakan)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $tunggakan['santri']->nis ?? '-' }}</td>
                <td>{{ $tunggakan['santri']->nama ?? '-' }}</td>
                <td>{{ $tunggakan['periode'] }}</td>
                <td>{{ $tunggakan['jumlah_tunggakan'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total Santri</th>
            <td colspan="2">{{ $tunggakans->where('jumlah_tunggakan', '>', 0)->count() }}</td>
        </tr>
        <tr>
            <th colspan="3">Total Tunggakan</th>
            <td colspan="2">Rp
                {{ number_format($tunggakans->where('jumlah_tunggakan', '>', 0)->sum('jumlah_tunggakan'), 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th colspan="3">Rata-rata Tunggakan</th>
            <td colspan="2">Rp
                {{ $tunggakans->where('jumlah_tunggakan', '>', 0)->count() > 0 ? number_format(round($tunggakans->where('jumlah_tunggakan', '>', 0)->sum('jumlah_tunggakan') / $tunggakans->where('jumlah_tunggakan', '>', 0)->count()), 0, ',', '.') : 0 }}
            </td>
        </tr>
    </tfoot>
</table>
