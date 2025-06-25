<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KasController extends Controller
{
    // Daftar kas (ambil dari tabel biaya)
    public function index(Request $request)
    {
        $query = Biaya::query();

        // Filter Nama Kas
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nama_biaya', 'like', "%$q%");
        }

        $kasList = $query->get();

        return view('kas.index', compact('kasList'));
    }

    // Detail kas (per biaya)
    public function detail(Request $request, $biaya_id)
    {
        $biaya = Biaya::findOrFail($biaya_id);
        // Filter pemasukan
        $pemasukanQuery = Pembayaran::where('biaya_id', $biaya_id);
        $pengeluaranQuery = Pengeluaran::where('biaya_id', $biaya_id);

        // Filter Periode (bulan/tahun)
        if ($request->filled('periode')) {
            $parts = explode('-', $request->periode);
            if (count($parts) == 2) {
                $pemasukanQuery->whereMonth('tanggal_bayar', $parts[1]);
                $pemasukanQuery->whereYear('tanggal_bayar', $parts[0]);
                $pengeluaranQuery->whereMonth('tanggal', $parts[1]);
                $pengeluaranQuery->whereYear('tanggal', $parts[0]);
            }
        }
        // Filter Santri (hanya untuk pemasukan)
        if ($request->filled('santri')) {
            $pemasukanQuery->whereHas('santri', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->santri}%");
            });
        }

        // Filter Pengeluaran (gabungan tgl, bulan, tahun)
        if ($request->filled('nama_pengeluaran')) {
            $pengeluaranQuery->where('nama', 'like', "%{$request->nama_pengeluaran}%");
        }
        if ($request->filled('tanggal_pengeluaran')) {
            $pengeluaranQuery->whereDate('tanggal', $request->tanggal_pengeluaran);
        }

        $pemasukan = $pemasukanQuery->orderBy('tanggal_bayar', 'desc')->get();
        $pengeluaran = $pengeluaranQuery->orderBy('tanggal', 'desc')->get();

        // Saldo kas
        $totalPemasukan = $pemasukan->sum('jumlah_bayar');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Data bulanan untuk grafik (ikut tahun dari filter periode jika ada)
        $bulanLabels = [];
        $pemasukanPerBulan = [];
        $pengeluaranPerBulan = [];
        $tahunGrafik = $request->filled('periode') ? explode('-', $request->periode)[0] : date('Y');
        for ($i = 1; $i <= 12; $i++) {
            $bulanLabels[] = date('M', mktime(0, 0, 0, $i, 1));
            $pemasukanPerBulan[] = Pembayaran::where('biaya_id', $biaya_id)
                ->whereMonth('tanggal_bayar', $i)
                ->whereYear('tanggal_bayar', $tahunGrafik)
                ->sum('jumlah_bayar');
            $pengeluaranPerBulan[] = Pengeluaran::where('biaya_id', $biaya_id)
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahunGrafik)
                ->sum('nominal');
        }

        return view('kas.detail', compact('biaya', 'pemasukan', 'pengeluaran', 'saldo', 'totalPemasukan', 'totalPengeluaran', 'bulanLabels', 'pemasukanPerBulan', 'pengeluaranPerBulan'));
    }

    // Tambah pengeluaran kas
    public function storePengeluaran(Request $request, $biaya_id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:100',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|max:2048',
        ];

        $user = auth()->user();

        $rules = [
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|max:2048',
        ];

        // Jika admin, unit wajib diisi dari form dan akan divalidasi.
        // Jika bendahara, unit akan ditambahkan secara manual nanti.
        if ($user->role === 'admin') {
            $rules['unit'] = 'required|in:putra,putri';
        }

        $validated = $request->validate($rules);
        $validated['biaya_id'] = $biaya_id;

        // Handle upload bukti
        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('bukti_pengeluaran', 'public');
        }

        // Jika pengguna adalah bendahara, unitnya diatur secara otomatis.
        // Untuk admin, unit sudah ada di dalam data $validated dari hasil validasi form.
        if ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit;
        }

        Pengeluaran::create($validated);

        return redirect()->route('kas.detail', $biaya_id)
            ->with('success', 'Pengeluaran kas berhasil ditambahkan.');
    }

    // Update pengeluaran kas
    public function updatePengeluaran(Request $request, $pengeluaran_id)
    {
        $pengeluaran = Pengeluaran::findOrFail($pengeluaran_id);
        $rules = [
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:100',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|max:2048',
        ];

        $user = auth()->user();
        if ($user->role === 'admin') {
            $rules['unit'] = 'required|in:putra,putri';
        }

        $validated = $request->validate($rules);

        // Jika ada file bukti baru, upload dan hapus bukti lama
        if ($request->hasFile('bukti')) {
            if ($pengeluaran->bukti) {
                Storage::disk('public')->delete($pengeluaran->bukti);
            }
            $validated['bukti'] = $request->file('bukti')->store('bukti_pengeluaran', 'public');
        }

        // Otomatisasi pengisian unit untuk bendahara
        if ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit;
        }

        $pengeluaran->update($validated);

        return redirect()->route('kas.detail', $pengeluaran->biaya_id)
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    // Hapus pengeluaran kas
    public function destroyPengeluaran($kas, Pengeluaran $pengeluaran)
    {
        // Hapus file bukti jika ada
        if ($pengeluaran->bukti) {
            Storage::disk('public')->delete($pengeluaran->bukti);
        }

        $pengeluaran->delete();

        return redirect()->route('kas.detail', $pengeluaran->biaya_id)
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    /**
     * Show the form for editing the specified pengeluaran.
     */
    public function editPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        return view('kas.edit_pengeluaran', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new pengeluaran.
     */
    public function createPengeluaran($kasId)
    {
        $kas = Biaya::findOrFail($kasId);

        return view('kas.create_pengeluaran', compact('kas'));
    }

    // EKSPOR EXCEL
    // public function exportExcel()
    // {
    //     $kasList = \App\Models\Biaya::all();
    //     return Excel::download(new \App\Exports\KasExport($kasList), 'kas_syahriah.xlsx');
    // }

    // EKSPOR PDF
    // public function exportPdf()
    // {
    //     $kasList = \App\Models\Biaya::all();
    //     $pdf = Pdf::loadView('exports.kas_pdf', compact('kasList'))->setPaper('a4', 'landscape');
    //     return $pdf->download('kas_syahriah.pdf');
    // }
}
