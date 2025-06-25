<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\SantriBiayaBebas;
use Illuminate\Http\Request;

class SantriBiayaBebasController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = SantriBiayaBebas::with(['santri', 'biaya']);

        if ($user->role === 'bendahara') {
            $query->whereHas('santri', function ($q) use ($user) {
                $q->where('unit', $user->unit);
            });
        }

        $items = $query->get();

        return view('santri_biaya_bebas.index', compact('items'));
    }

    public function create()
    {
        $santris = Santri::orderBy('nama')->get();
        $selectedSantri = request()->old('santri_id');
        if ($selectedSantri) {
            $santri = Santri::find($selectedSantri);
            $unit = $santri ? $santri->unit : null;
            $biayas = Biaya::where('unit', $unit)->orderBy('nama_biaya')->get();
        } else {
            $biayas = Biaya::orderBy('nama_biaya')->get();
        }

        return view('santri_biaya_bebas.create', compact('santris', 'biayas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'biaya_id' => 'required|array|min:1',
            'biaya_id.*' => 'exists:biayas,id',
            'keterangan' => 'nullable',
        ]);
        foreach ($request->biaya_id as $biayaId) {
            SantriBiayaBebas::firstOrCreate([
                'santri_id' => $request->santri_id,
                'biaya_id' => $biayaId,
            ], [
                'keterangan' => $request->keterangan,
            ]);
        }

        return redirect()->route('santri_biaya_bebas.index')->with('success', 'Hak bebas biaya berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = SantriBiayaBebas::findOrFail($id);
        $santris = Santri::orderBy('nama')->get();
        $santri = Santri::find($item->santri_id);
        $unit = $santri ? $santri->unit : null;
        $biayas = Biaya::where('unit', $unit)->orderBy('nama_biaya')->get();

        return view('santri_biaya_bebas.edit', compact('item', 'santris', 'biayas'));
    }

    public function update(Request $request, $id)
    {
        $item = SantriBiayaBebas::findOrFail($id);
        $request->validate([
            'biaya_id' => 'required|exists:biayas,id',
            'keterangan' => 'nullable',
        ]);
        // Validasi: Cek jika sudah ada pembayaran untuk santri & biaya lama (di SEMUA periode)
        $pembayaranAda = \App\Models\Pembayaran::where('santri_id', $item->santri_id)
            ->where('biaya_id', $item->biaya_id)
            ->exists();
        // Validasi: Cek juga jika sudah ada pembayaran untuk santri & biaya BARU (jika user ganti ke biaya lain)
        $pembayaranBaruAda = \App\Models\Pembayaran::where('santri_id', $item->santri_id)
            ->where('biaya_id', $request->biaya_id)
            ->exists();
        if (($pembayaranAda && $item->biaya_id != $request->biaya_id) || $pembayaranBaruAda) {
            return back()->withErrors('Tidak bisa mengubah hak bebas biaya karena sudah ada pembayaran untuk biaya terkait.')->withInput();
        }
        $item->biaya_id = $request->biaya_id;
        $item->keterangan = $request->keterangan;
        $item->save();

        return redirect()->route('santri_biaya_bebas.index')->with('success', 'Hak bebas biaya berhasil diupdate!');
    }

    public function destroy($id)
    {
        $item = SantriBiayaBebas::findOrFail($id);
        $item->delete();

        return redirect()->route('santri_biaya_bebas.index')->with('success', 'Hak bebas biaya berhasil dihapus!');
    }
}
