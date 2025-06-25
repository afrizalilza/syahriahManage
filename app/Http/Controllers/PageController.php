<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $periode = $request->input('periode', date('Y-m'));

        // Buat label periode (e.g., "Juni 2025")
        $bulan = substr($periode, 5, 2);
        $tahun = substr($periode, 0, 4);
        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        $periodeLabel = ($namaBulan[$bulan] ?? $bulan).' '.$tahun;

        // Query dasar
        $santriQuery = \App\Models\Santri::where('status', 'aktif');
        $biayaQuery = \App\Models\Biaya::query();
        $pembayaranQuery = \App\Models\Pembayaran::query();
        $pengeluaranQuery = \App\Models\Pengeluaran::query();
        $bebasBiayaQuery = \App\Models\SantriBiayaBebas::query();

        // Terapkan filter unit jika bukan admin
        if ($user->role === 'bendahara') {
            $santriQuery->where('unit', $user->unit);
            $biayaQuery->where('unit', $user->unit);
            $pengeluaranQuery->where('unit', $user->unit);
            // Clone query untuk mendapatkan ID santri tanpa mempengaruhi query utama
            $santriIds = (clone $santriQuery)->pluck('id');
            $pembayaranQuery->whereIn('santri_id', $santriIds);
            $bebasBiayaQuery->whereIn('santri_id', $santriIds);
        }

        $santris = $santriQuery->get();
        $totalSantri = $santris->count();
        $biayas = $biayaQuery->get();

        // Pembayaran bulan ini
        $pembayaranBulanIni = (clone $pembayaranQuery)->where('periode', $periode)->sum('jumlah_bayar');

        // Saldo Syahriah Realtime
        $totalPemasukan = (clone $pembayaranQuery)->sum('jumlah_bayar');
        $totalPengeluaran = (clone $pengeluaranQuery)->sum('nominal');
        $saldoSyahriah = $totalPemasukan - $totalPengeluaran;

        // Notifikasi pembayaran terakhir
        $pembayaranTerakhir = (clone $pembayaranQuery)->with(['santri', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Notifikasi hak bebas biaya baru
        $recentBebas = (clone $bebasBiayaQuery)->with(['santri', 'biaya'])
            ->orderBy('created_at', 'desc')->take(3)->get();

        // Kalkulasi status lunas, tunggakan, dan daftar penunggak
        $lunas = 0;
        $belumLunas = 0;
        $totalTunggakan = 0;
        $santriBelumLunasList = collect();

        foreach ($santris as $santri) {
            $biayaBebas = \App\Models\SantriBiayaBebas::where('santri_id', $santri->id)->pluck('biaya_id')->toArray();
            $biayaSantri = $biayas->where('unit', $santri->unit);
            $nominalWajib = $biayaSantri->whereNotIn('id', $biayaBebas)->sum('jumlah');

            $totalBayar = \App\Models\Pembayaran::where('santri_id', $santri->id)
                ->where('periode', $periode)
                ->sum('jumlah_bayar');

            $sisa = $nominalWajib - $totalBayar;

            if ($nominalWajib == 0 || $sisa <= 0) {
                $lunas++;
            } else {
                $belumLunas++;
                $totalTunggakan += $sisa;
                $santriBelumLunasList->push([
                    'nama' => $santri->nama,
                    'nis' => $santri->nis,
                    'sisa' => $sisa,
                ]);
            }
        }
        $santriTunggakan = $belumLunas;
        $persenLunas = $totalSantri > 0 ? round(($lunas / $totalSantri) * 100) : 0;
        $tunggakanBulanIni = $totalTunggakan;
        $santriBelumLunasList = $santriBelumLunasList->sortByDesc('sisa')->take(5);

        // Data untuk chart
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulanLoop = date('Y-m', strtotime("-$i months"));

            $pemasukan = (clone $pembayaranQuery)->where('periode', $bulanLoop)->sum('jumlah_bayar');
            $pengeluaran = (clone $pengeluaranQuery)->whereYear('tanggal', date('Y', strtotime($bulanLoop)))
                ->whereMonth('tanggal', date('m', strtotime($bulanLoop)))
                ->sum('nominal');

            $chartData[date('M Y', strtotime($bulanLoop))] = [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ];
        }

        return view('home', compact(
            'totalSantri',
            'pembayaranBulanIni',
            'totalTunggakan',
            'santriTunggakan',
            'biayas',
            'pembayaranTerakhir',
            'lunas',
            'belumLunas',
            'persenLunas',
            'periode',
            'recentBebas',
            'saldoSyahriah',
            'santriBelumLunasList',
            'periodeLabel',
            'tunggakanBulanIni',
            'chartData'
        ));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
