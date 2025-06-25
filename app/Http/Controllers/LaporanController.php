<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Santri;
use App\Models\SantriBiayaBebas;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function bulanan(Request $request)
    {
        // --- Ambil parameter filter dari request (multi-select jenis biaya) ---
        $periode = $request->input('periode', now()->format('Y-m'));
        $status = $request->input('status', '');
        $jenis = (array) $request->input('jenis', []);
        $parts = explode('-', $periode);
        $tahun = $parts[0] ?? now()->format('Y');
        $bulan = $parts[1] ?? now()->format('m');
        $tanggalAkhirPeriode = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $biayas = Biaya::all();
        // Filter biaya berdasarkan ID, bukan nama
        $biayasFiltered = count($jenis) > 0 ? $biayas->whereIn('id', $jenis) : $biayas;

        $pembayaranQuery = Pembayaran::whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun);
        if (count($jenis) > 0) {
            // Filter pembayaran berdasarkan biaya_id
            $pembayaranQuery->whereIn('biaya_id', $jenis);
        }
        $pembayarans = $pembayaranQuery->with(['santri', 'biaya'])->get();

        // --- Filter Santri Berdasarkan Unit dari Biaya yang Dipilih ---
        $santriQuery = Santri::where('status', 'aktif')
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhirPeriode);

        if (count($jenis) > 0) {
            // Ambil unit dari biaya yang difilter
            $units = $biayasFiltered->pluck('unit')->unique();
            // Jika ada unit yang spesifik (bukan campuran), filter santri
            if ($units->isNotEmpty()) {
                $santriQuery->whereIn('unit', $units);
            }
        }

        $santriList = $santriQuery->get();
        $santriAktif = $santriList->count(); // Hitung santri aktif setelah filter

        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $rekap = collect();

        // Ambil hak bebas biaya untuk setiap santri
        $biayaBebas = SantriBiayaBebas::whereIn('santri_id', $santriList->pluck('id'))
            ->get()->groupBy('santri_id');

        foreach ($santriList as $santri) {
            $rowPembayaran = $pembayarans->where('santri_id', $santri->id);
            $byJenis = [];
            $total = 0;
            // Hitung total tagihan setelah dikurangi hak bebas biaya
            $bebasList = isset($biayaBebas[$santri->id]) ? $biayaBebas[$santri->id]->pluck('biaya_id')->toArray() : [];
            // FILTER BIAYA SESUAI UNIT SANTRI DAN BEBAS BIAYA
            $biayasFilteredSantri = $biayasFiltered->filter(function ($biaya) use ($bebasList, $santri) {
                return $biaya->unit === $santri->unit && ! in_array($biaya->id, $bebasList);
            });
            foreach ($biayasFilteredSantri as $biaya) {
                // Gunakan ID biaya sebagai key
                $byJenis[$biaya->id] = $rowPembayaran->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
                $total += $byJenis[$biaya->id];
            }
            $tagihan = $biayasFilteredSantri->sum('jumlah');
            $statusSantri = $tagihan == 0 && count($bebasList) > 0 ? 'Lunas (Bebas Biaya)' : ($tagihan == 0 ? '-' : ($total >= $tagihan ? 'Lunas' : ($total > 0 ? 'Belum Lunas' : 'Belum')));
            $tanggal = optional($rowPembayaran->sortByDesc('tanggal_bayar')->first())->tanggal_bayar;
            $rekap->push([
                'nis' => $santri->nis,
                'nama' => $santri->nama,
                'byJenis' => $byJenis,
                'total' => $total,
                'status' => $statusSantri,
                'tanggal' => $tanggal,
                'bebas_biaya' => count($bebasList) > 0,
            ]);
        }
        // Filter status rekap jika dipilih
        if ($status == 'lunas') {
            $rekap = $rekap->filter(fn ($row) => $row['status'] == 'Lunas' || $row['status'] == 'Lunas (Bebas Biaya)');
        } elseif ($status == 'belum') {
            $rekap = $rekap->filter(fn ($row) => $row['status'] != 'Lunas' && $row['status'] != 'Lunas (Bebas Biaya)');
        }
        $sudahBayar = $rekap->where('status', '!=', 'Belum')->where('status', '!=', 'Belum Lunas')->count();
        $belumBayar = $rekap->where('status', 'Belum')->count() + $rekap->where('status', 'Belum Lunas')->count();
        $persenLunas = $rekap->count() ? round(($sudahBayar / $rekap->count()) * 100) : 0;

        // Buat key unik untuk total per jenis (nama + unit)
        $totalPerJenis = [];
        foreach ($biayasFiltered as $biaya) {
            $key = $biaya->nama_biaya.' ('.$biaya->unit.')';
            $totalPerJenis[$key] = $pembayarans->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
        }

        $totalAll = $pembayarans->sum('jumlah_bayar');

        return view('laporan.bulanan', compact('periode', 'biayas', 'biayasFiltered', 'totalPemasukan', 'sudahBayar', 'belumBayar', 'persenLunas', 'rekap', 'totalPerJenis', 'totalAll', 'santriAktif'));
    }

    public function tahunan(Request $request)
    {
        $tahun = $request->input('tahun', now()->format('Y'));
        $user = auth()->user();

        // --- Filter Data Berdasarkan Unit Pengguna ---
        $biayaQuery = Biaya::query();
        $santriQuery = Santri::where('status', 'aktif');
        $pengeluaranQuery = Pengeluaran::whereYear('tanggal', $tahun);

        if ($user->role == 'bendahara') {
            $biayaQuery->where('unit', $user->unit);
            $santriQuery->where('unit', $user->unit);
            $pengeluaranQuery->where('unit', $user->unit);
        }

        $biayas = $biayaQuery->get();
        $biayaIds = $biayas->pluck('id');

        $tanggalAkhirTahun = \Carbon\Carbon::createFromDate($tahun, 12, 31);
        $santriAktif = (clone $santriQuery)->whereDate('tanggal_masuk', '<=', $tanggalAkhirTahun)->count();

        $pembayaranQuery = Pembayaran::whereYear('tanggal_bayar', $tahun)->whereIn('biaya_id', $biayaIds);
        $pembayarans = $pembayaranQuery->with(['santri', 'biaya'])->get();
        $pengeluarans = $pengeluaranQuery->get();

        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $totalPengeluaran = $pengeluarans->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // --- Agregasi Data ---
        $totalPerJenis = [];
        foreach ($biayas as $biaya) {
            $key = $biaya->nama_biaya.' ('.$biaya->unit.')';
            $totalPerJenis[$key] = $pembayarans->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
        }

        $santriLunas = $pembayarans->unique('santri_id')->count();
        $santriBelum = $santriAktif - $santriLunas;

        // --- Data Grafik Bulanan ---
        $bulanLabels = [];
        $totalPerBulan = [];
        $totalPengeluaranPerBulan = [];
        $saldoBerjalan = [];
        $saldo = 0;

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanLabels[] = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->isoFormat('MMMM');
            $pemasukanBulan = $pembayarans->filter(fn ($p) => \Carbon\Carbon::parse($p->tanggal_bayar)->month == $bulan)->sum('jumlah_bayar');
            $pengeluaranBulan = $pengeluarans->filter(fn ($p) => \Carbon\Carbon::parse($p->tanggal)->month == $bulan)->sum('nominal');
            $totalPerBulan[] = $pemasukanBulan;
            $totalPengeluaranPerBulan[] = $pengeluaranBulan;
            $saldo += $pemasukanBulan - $pengeluaranBulan;
            $saldoBerjalan[] = $saldo;
        }

        // --- Rekapitulasi Bulanan ---
        $rekapBulanan = collect();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanPembayaran = $pembayarans->filter(fn ($p) => \Carbon\Carbon::parse($p->tanggal_bayar)->month == $bulan);
            $row = ['bulan' => $bulan];
            foreach ($biayas as $biaya) {
                $key = $biaya->nama_biaya.' ('.$biaya->unit.')';
                $row[$key] = $bulanPembayaran->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
            }
            $row['total'] = $bulanPembayaran->sum('jumlah_bayar');
            $row['lunas'] = $bulanPembayaran->unique('santri_id')->count();
            $row['belum'] = $santriAktif - $row['lunas'];
            $row['persen'] = $santriAktif ? round(($row['lunas'] / $santriAktif) * 100) : 0;
            $rekapBulanan->push($row);
        }

        // --- Rekap Pengeluaran Bulanan ---
        $rekapPengeluaranBulanan = collect();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $pengeluaranBulanItems = $pengeluarans->filter(fn ($p) => \Carbon\Carbon::parse($p->tanggal)->month == $bulan);
            $rekapPengeluaranBulanan->push([
                'bulan' => $bulanLabels[$bulan - 1],
                'items' => $pengeluaranBulanItems->pluck('nama')->toArray(),
                'total' => $pengeluaranBulanItems->sum('nominal'),
            ]);
        }

        // --- Statistik Kelunasan Tahunan ---
        $santriLunasTahun = $pembayarans->unique('santri_id')->count();
        $santriBelumTahun = $santriAktif - $santriLunasTahun;
        $persenLunasTahun = $santriAktif ? round(($santriLunasTahun / $santriAktif) * 100) : 0;

        // --- Daftar Santri Belum Lunas ---
        $santriBelumList = [];
        if ($santriBelumTahun > 0) {
            $santriBayarIds = $pembayarans->pluck('santri_id')->unique();
            $santriBelum = (clone $santriQuery)->whereDate('tanggal_masuk', '<=', $tanggalAkhirTahun)
                ->whereNotIn('id', $santriBayarIds)
                ->limit(10)->get();
            $santriBelumList = $santriBelum;
        }

        $tahunList = \App\Models\Pembayaran::selectRaw('YEAR(tanggal_bayar) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();

        return view('laporan.tahunan', compact('tahun', 'tahunList', 'biayas', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'santriAktif', 'totalPerJenis', 'rekapBulanan', 'rekapPengeluaranBulanan', 'santriLunas', 'santriBelum', 'bulanLabels', 'totalPerBulan', 'totalPengeluaranPerBulan', 'santriLunasTahun', 'santriBelumTahun', 'persenLunasTahun', 'santriBelumList', 'saldoBerjalan'));
    }

    public function exportExcel(Request $request)
    {
        $periode = $request->input('periode', now()->format('Y-m'));
        $status = $request->input('status', '');
        $jenis = (array) $request->input('jenis', []);
        // Ambil data rekap sama seperti bulanan
        $data = $this->bulananData($periode, $status, $jenis);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanBulananExport($data), 'laporan_bulanan_'.$periode.'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $periode = $request->input('periode', now()->format('Y-m'));
        $status = $request->input('status', '');
        $jenis = (array) $request->input('jenis', []);
        $data = $this->bulananData($periode, $status, $jenis);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.laporan_bulanan_pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('laporan_bulanan_'.$periode.'.pdf');
    }

    public function exportTahunanExcel(Request $request)
    {
        $tahun = $request->input('tahun', now()->format('Y'));
        // Ambil data tahunan
        $biayas = Biaya::all();
        $tanggalAkhirTahun = \Carbon\Carbon::createFromDate($tahun, 12, 31);
        $santriAktif = Santri::where('status', 'aktif')
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhirTahun)
            ->count();
        $pembayaranQuery = Pembayaran::whereYear('tanggal_bayar', $tahun);
        $pembayarans = $pembayaranQuery->with(['santri', 'biaya'])->get();
        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        $rekapBulanan = collect();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $row = [
                'bulan' => $bulan,
                'total' => $pembayarans->filter(function ($p) use ($bulan) {
                    return \Carbon\Carbon::parse($p->tanggal_bayar)->month == $bulan;
                })->sum('jumlah_bayar'),
                'pengeluaran' => Pengeluaran::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->sum('nominal'),
            ];
            $rekapBulanan->push($row);
        }
        // Export menggunakan Maatwebsite Excel
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanTahunanExport($tahun, $biayas, $rekapBulanan, $totalPemasukan, $totalPengeluaran, $saldoAkhir), 'laporan_tahunan_'.$tahun.'.xlsx');
    }

    public function exportTahunanPdf(Request $request)
    {
        $tahun = $request->input('tahun', now()->format('Y'));
        // Ambil data tahunan
        $biayas = Biaya::all();
        $tanggalAkhirTahun = \Carbon\Carbon::createFromDate($tahun, 12, 31);
        $santriAktif = Santri::where('status', 'aktif')
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhirTahun)
            ->count();
        $pembayaranQuery = Pembayaran::whereYear('tanggal_bayar', $tahun);
        $pembayarans = $pembayaranQuery->with(['santri', 'biaya'])->get();
        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        $rekapBulanan = collect();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $row = [
                'bulan' => $bulan,
                'total' => $pembayarans->filter(function ($p) use ($bulan) {
                    return \Carbon\Carbon::parse($p->tanggal_bayar)->month == $bulan;
                })->sum('jumlah_bayar'),
                'pengeluaran' => Pengeluaran::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->sum('nominal'),
            ];
            $rekapBulanan->push($row);
        }
        // Export menggunakan DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.laporan_tahunan_pdf', [
            'tahun' => $tahun,
            'biayas' => $biayas,
            'rekapBulanan' => $rekapBulanan,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan_tahunan_'.$tahun.'.pdf');
    }

    // Helper agar logic rekap tidak duplikasi
    private function bulananData($periode, $status, $jenis)
    {
        // Copy logic dari bulanan() controller, return array compact(...)
        $parts = explode('-', $periode);
        $tahun = $parts[0] ?? now()->format('Y');
        $bulan = $parts[1] ?? now()->format('m');
        $tanggalAkhirPeriode = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $santriAktif = Santri::where('status', 'aktif')
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhirPeriode)
            ->count();
        $biayas = Biaya::all();
        $biayasFiltered = count($jenis) > 0 ? $biayas->whereIn('nama_biaya', $jenis) : $biayas;
        $pembayaranQuery = Pembayaran::whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun);
        if (count($jenis) > 0) {
            $pembayaranQuery->whereHas('biaya', function ($q) use ($jenis) {
                $q->whereIn('nama_biaya', $jenis);
            });
        }
        $pembayarans = $pembayaranQuery->with(['santri', 'biaya'])->get();

        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $rekap = collect();
        $santriList = Santri::where('status', 'aktif')
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhirPeriode)
            ->get();
        // Ambil hak bebas biaya untuk setiap santri
        $biayaBebas = SantriBiayaBebas::whereIn('santri_id', $santriList->pluck('id'))
            ->get()->groupBy('santri_id');
        foreach ($santriList as $santri) {
            $rowPembayaran = $pembayarans->where('santri_id', $santri->id);
            $byJenis = [];
            $total = 0;
            // Hitung total tagihan setelah dikurangi hak bebas biaya
            $bebasList = isset($biayaBebas[$santri->id]) ? $biayaBebas[$santri->id]->pluck('biaya_id')->toArray() : [];
            $biayasFilteredSantri = $biayasFiltered->filter(function ($biaya) use ($bebasList) {
                return ! in_array($biaya->id, $bebasList);
            });
            foreach ($biayasFiltered as $biaya) {
                $byJenis[$biaya->nama_biaya] = $rowPembayaran->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
                $total += $byJenis[$biaya->nama_biaya];
            }
            $tagihan = $biayasFilteredSantri->sum('jumlah');
            $statusSantri = $tagihan == 0 && count($bebasList) > 0 ? 'Lunas (Bebas Biaya)' : ($tagihan == 0 ? '-' : ($total >= $tagihan ? 'Lunas' : ($total > 0 ? 'Belum Lunas' : 'Belum')));
            $tanggal = optional($rowPembayaran->sortByDesc('tanggal_bayar')->first())->tanggal_bayar;
            $rekap->push([
                'nis' => $santri->nis,
                'nama' => $santri->nama,
                'byJenis' => $byJenis,
                'total' => $total,
                'status' => $statusSantri,
                'tanggal' => $tanggal,
                'bebas_biaya' => count($bebasList) > 0,
            ]);
        }
        // Filter status rekap jika dipilih
        if ($status == 'lunas') {
            $rekap = $rekap->filter(fn ($row) => $row['status'] == 'Lunas' || $row['status'] == 'Lunas (Bebas Biaya)');
        } elseif ($status == 'belum') {
            $rekap = $rekap->filter(fn ($row) => $row['status'] != 'Lunas' && $row['status'] != 'Lunas (Bebas Biaya)');
        }
        $sudahBayar = $rekap->where('status', '!=', 'Belum')->where('status', '!=', 'Belum Lunas')->count();
        $belumBayar = $rekap->where('status', 'Belum')->count() + $rekap->where('status', 'Belum Lunas')->count();
        $persenLunas = $rekap->count() ? round(($sudahBayar / $rekap->count()) * 100) : 0;
        $totalPerJenis = [];
        foreach ($biayasFiltered as $biaya) {
            $totalPerJenis[$biaya->nama_biaya] = $pembayarans->where('biaya_id', $biaya->id)->sum('jumlah_bayar');
        }
        $totalAll = $pembayarans->sum('jumlah_bayar');

        return compact('periode', 'biayas', 'biayasFiltered', 'totalPemasukan', 'sudahBayar', 'belumBayar', 'persenLunas', 'rekap', 'totalPerJenis', 'totalAll', 'santriAktif');
    }
}
