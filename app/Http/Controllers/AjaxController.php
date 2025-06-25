<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Pembayaran;
use App\Models\SantriBiayaBebas;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getAvailableBiayaForSantri(Request $request)
    {
        $santriId = $request->santri_id;
        $biayaBebas = SantriBiayaBebas::where('santri_id', $santriId)->pluck('biaya_id')->toArray();
        $unit = null;
        if ($santriId) {
            $santri = \App\Models\Santri::find($santriId);
            $unit = $santri ? $santri->unit : null;
        }
        $query = Biaya::whereNotIn('id', $biayaBebas);
        if ($unit) {
            $query = $query->where('unit', $unit);
        }
        $biayas = $query->orderBy('nama_biaya')->get();

        return response()->json($biayas);
    }

    public function getAvailableBiayaForPembayaran(Request $request)
    {
        $santriId = $request->santri_id;
        $periode = $request->periode;
        // Biaya yang sudah lunas atau sudah dibebaskan
        $biayaBebas = SantriBiayaBebas::where('santri_id', $santriId)->pluck('biaya_id')->toArray();
        $biayaSudahLunas = Pembayaran::where('santri_id', $santriId)
            ->where('periode', $periode)
            ->pluck('biaya_id')->toArray();
        $exclude = array_unique(array_merge($biayaBebas, $biayaSudahLunas));
        $unit = null;
        if ($santriId) {
            $santri = \App\Models\Santri::find($santriId);
            $unit = $santri ? $santri->unit : null;
        }
        $query = Biaya::whereNotIn('id', $exclude);
        if ($unit) {
            $query = $query->where('unit', $unit);
        }
        $biayas = $query->orderBy('nama_biaya')->get();

        return response()->json($biayas);
    }
}
