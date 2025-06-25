<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Pembayaran::with(['santri', 'biaya']);
        // Filter Periode (format yyyy-mm)
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        // Filter Nama/NIS Santri
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('santri', function ($sub) use ($q) {
                $sub->where('nama', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%");
            });
        }
        // Filter Status Lunas/Belum Lunas
        if ($request->filled('status')) {
            $status = $request->status;
            // Mapping unit santri
            $santriUnits = \App\Models\Santri::pluck('unit', 'id');
            $biayasAll = \App\Models\Biaya::all();
            $pembayaranSantri = $query->get()->groupBy(function ($item) {
                return $item->santri_id.'-'.$item->periode;
            });
            $filteredIds = collect();
            foreach ($pembayaranSantri as $group) {
                $first = $group->first();
                $santri_id = $first->santri_id;
                $periode = $first->periode;
                // Hitung total biaya wajib setelah dikurangi hak bebas, hanya untuk unit santri
                $biayaBebas = \App\Models\SantriBiayaBebas::where('santri_id', $santri_id)->pluck('biaya_id')->toArray();
                $unitSantri = $santriUnits[$santri_id] ?? null;
                $totalWajib = $biayasAll->where('unit', $unitSantri)->whereNotIn('id', $biayaBebas)->sum('jumlah');
                $totalBayar = $group->sum('jumlah_bayar');
                $isLunas = ($totalWajib == 0) ? ($status == 'Lunas') : ($totalBayar >= $totalWajib);
                if (($status == 'Lunas' && $isLunas) || ($status == 'Belum Lunas' && ! $isLunas)) {
                    $filteredIds->push($first->kode_transaksi);
                }
            }
            $query->whereIn('kode_transaksi', $filteredIds);
        }
        $biayas = \App\Models\Biaya::all();
        $pembayarans = $query->get();

        return view('pembayaran.index', compact('pembayarans', 'biayas'));
    }

    public function create()
    {
        $santris = \App\Models\Santri::where('status', 'Aktif')->orderBy('nama')->get();
        $biayas = collect();
        $biayaNominals = collect();
        // Jika ada santri terpilih (old value), filter biaya sesuai hak bebas
        $selectedSantri = request()->old('santri_id');
        if ($selectedSantri) {
            $biayaBebas = \App\Models\SantriBiayaBebas::where('santri_id', $selectedSantri)->pluck('biaya_id')->toArray();
            $biayas = \App\Models\Biaya::whereNotIn('id', $biayaBebas)->orderBy('nama_biaya')->get();
        } else {
            $biayas = \App\Models\Biaya::orderBy('nama_biaya')->get();
        }
        $biayaNominals = $biayas->mapWithKeys(function ($item) {
            return [$item->id => $item->nominal ?? $item->jumlah ?? 0];
        });

        return view('pembayaran.create', compact('santris', 'biayas', 'biayaNominals'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // Validasi status santri hanya boleh "Aktif"
        $santri = \App\Models\Santri::findOrFail($input['santri_id']);
        if ($santri->status !== 'Aktif') {
            return back()->withErrors('Santri non aktif tidak dapat melakukan pembayaran.')->withInput();
        }
        // Ganti jumlah_bayar 'lainnya' dengan input manual sebelum validasi
        if (isset($input['jumlah_bayar'])) {
            foreach ($input['jumlah_bayar'] as $biayaId => $val) {
                if ($val === 'lainnya' && ! empty($input['jumlah_bayar_manual'][$biayaId])) {
                    $input['jumlah_bayar'][$biayaId] = $input['jumlah_bayar_manual'][$biayaId];
                }
            }
        }
        $validated = \Validator::make($input, [
            'santri_id' => 'required|exists:santris,id',
            'biaya_id' => 'required|array|min:1',
            'biaya_id.*' => 'exists:biayas,id',
            'periode' => 'required|string',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|array',
            'jumlah_bayar.*' => 'required|numeric|min:0',
        ])->validate();
        // Cegah duplikasi pembayaran untuk kombinasi santri, biaya, periode
        foreach ($validated['biaya_id'] as $biayaId) {
            $sudahAda = \App\Models\Pembayaran::where('santri_id', $validated['santri_id'])
                ->where('biaya_id', $biayaId)
                ->where('periode', $validated['periode'])
                ->exists();
            if ($sudahAda) {
                return back()->withErrors('Pembayaran untuk biaya dan periode tersebut sudah pernah dicatat. Tidak boleh duplikat.')->withInput();
            }
        }
        // Generate kode_transaksi unik untuk satu input multi-biaya
        $kodeTransaksi = uniqid('TRX');
        // Tentukan unit sesuai role
        $user = auth()->user();
        if ($user->role === 'admin') {
            $unit = $request->input('unit');
        } elseif ($user->role === 'bendahara') {
            $unit = $user->unit;
        } else {
            $unit = null;
        }
        $data = [];
        foreach ($validated['biaya_id'] as $biayaId) {
            // Ambil nominal biaya dari tabel biaya
            $nominalBiaya = \App\Models\Biaya::find($biayaId)->jumlah ?? 0;
            $jumlahBayar = $validated['jumlah_bayar'][$biayaId];
            // Status otomatis
            $status = ($jumlahBayar >= $nominalBiaya) ? 'Lunas' : 'Belum Lunas';
            $data[] = [
                'santri_id' => $validated['santri_id'],
                'biaya_id' => $biayaId,
                'periode' => $validated['periode'],
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'jumlah_bayar' => $jumlahBayar,
                'status' => $status,
                'kode_transaksi' => $kodeTransaksi,
                'unit' => $unit,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        \App\Models\Pembayaran::insert($data);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        $grouped = \App\Models\Pembayaran::where('kode_transaksi', $pembayaran->kode_transaksi)->get();
        $biayas = \App\Models\Biaya::all();

        return view('pembayaran.show', compact('pembayaran', 'grouped', 'biayas'));
    }

    public function edit($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        // Ambil semua pembayaran dengan kode_transaksi yang sama
        $grouped = \App\Models\Pembayaran::where('kode_transaksi', $pembayaran->kode_transaksi)->get();
        $santris = \App\Models\Santri::all();
        $biayas = \App\Models\Biaya::all();
        $biayaNominals = $biayas->mapWithKeys(function ($item) {
            return [$item->id => $item->nominal ?? $item->jumlah ?? 0];
        });
        // Untuk form: biaya_id[] & jumlah_bayar[] dari transaksi yang sama
        $selectedBiaya = $grouped->pluck('biaya_id')->toArray();
        $selectedJumlah = $grouped->mapWithKeys(function ($item) {
            return [$item->biaya_id => $item->jumlah_bayar];
        });

        return view('pembayaran.edit', compact('pembayaran', 'grouped', 'santris', 'biayas', 'biayaNominals', 'selectedBiaya', 'selectedJumlah'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        $kodeTransaksi = $pembayaran->kode_transaksi;
        $input = $request->all();
        // Validasi status santri hanya boleh "Aktif"
        $santri = \App\Models\Santri::findOrFail($input['santri_id']);
        if ($santri->status !== 'Aktif') {
            return back()->withErrors('Santri non aktif tidak dapat melakukan pembayaran.')->withInput();
        }
        // Ganti jumlah_bayar 'lainnya' dengan input manual sebelum validasi
        if (isset($input['jumlah_bayar'])) {
            foreach ($input['jumlah_bayar'] as $biayaId => $val) {
                if ($val === 'lainnya' && ! empty($input['jumlah_bayar_manual'][$biayaId])) {
                    $input['jumlah_bayar'][$biayaId] = $input['jumlah_bayar_manual'][$biayaId];
                }
            }
        }
        $validated = \Validator::make($input, [
            'santri_id' => 'required|exists:santris,id',
            'biaya_id' => 'required|array|min:1',
            'biaya_id.*' => 'exists:biayas,id',
            'periode' => 'required|string',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|array',
            'jumlah_bayar.*' => 'required|numeric|min:0',
        ])->validate();
        // Hapus semua pembayaran lama dengan kode_transaksi ini
        \App\Models\Pembayaran::where('kode_transaksi', $kodeTransaksi)->delete();
        // Insert ulang sesuai update
        // Tentukan unit sesuai role
        $user = auth()->user();
        if ($user->role === 'admin') {
            $unit = $request->input('unit');
        } elseif ($user->role === 'bendahara') {
            $unit = $user->unit;
        } else {
            $unit = null;
        }
        $data = [];
        foreach ($validated['biaya_id'] as $biayaId) {
            // Ambil nominal biaya dari tabel biaya
            $nominalBiaya = \App\Models\Biaya::find($biayaId)->jumlah ?? 0;
            $jumlahBayar = $validated['jumlah_bayar'][$biayaId];
            // Status otomatis
            $status = ($jumlahBayar >= $nominalBiaya) ? 'Lunas' : 'Belum Lunas';
            $data[] = [
                'santri_id' => $validated['santri_id'],
                'biaya_id' => $biayaId,
                'periode' => $validated['periode'],
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'jumlah_bayar' => $jumlahBayar,
                'status' => $status,
                'kode_transaksi' => $kodeTransaksi,
                'unit' => $unit,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        \App\Models\Pembayaran::insert($data);

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
