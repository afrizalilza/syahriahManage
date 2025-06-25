<table border="0" cellpadding="8" cellspacing="0" style="width:100%;">
    <tr>
        <td colspan="3" style="font-size:15em; font-weight:bold; text-align:center; padding-bottom:12px;">
            Laporan Tahunan Syahriah Tahun {{ $tahun }}
        </td>
    </tr>
</table>
<table border="1" cellpadding="6" cellspacing="0" style="width:100%;">
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
            <td><strong>Total Tahun</strong></td>
            <td><strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
            <td><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Saldo Akhir Tahun</strong></td>
            <td><strong>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>

{{-- Catatan: Untuk menampilkan grafik/diagram di Excel secara otomatis, perlu custom export menggunakan PhpSpreadsheet, bukan sekadar export view. Anda bisa membuat grafik manual di Excel setelah export, atau minta fitur otomatisasi lanjutan. --}}
