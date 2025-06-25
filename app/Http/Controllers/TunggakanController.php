<?php

namespace App\Http\Controllers;

use App\Exports\TunggakanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TunggakanController extends Controller
{
    /**
     * Ambil data tunggakan dengan filter sesuai request
     */
    private function getTunggakanData(Request $request)
    {
        $q = $request->input('q');
        $query = \App\Models\Santri::where('status', 'Aktif')->whereDate('tanggal_masuk', '<=', now());
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%'.$q.'%')
                    ->orWhere('nis', 'like', '%'.$q.'%');
            });
        }
        $santris = $query->get();
        $biayas = \App\Models\Biaya::all();
        $periode = $request->input('periode');
        // Ambil daftar biaya yang dibebaskan untuk masing-masing santri
        $biayaBebas = \App\Models\SantriBiayaBebas::whereIn('santri_id', $santris->pluck('id'))->get()->groupBy('santri_id');
        $tunggakans = collect();
        foreach ($santris as $santri) {
            $totalTunggakan = 0;
            $rincian = [];
            // Filter biaya sesuai unit santri
            $biayasByUnit = $biayas->where('unit', $santri->unit);
            foreach ($biayasByUnit as $biaya) {
                // Skip biaya jika santri punya hak bebas biaya ini
                if (isset($biayaBebas[$santri->id]) && $biayaBebas[$santri->id]->where('biaya_id', $biaya->id)->count() > 0) {
                    continue;
                }
                $totalBayar = \App\Models\Pembayaran::where('santri_id', $santri->id)
                    ->where('biaya_id', $biaya->id)
                    ->where('periode', $periode)
                    ->sum('jumlah_bayar');
                $nominalWajib = $biaya->jumlah;
                $sisa = $nominalWajib - $totalBayar;
                if ($sisa > 0) {
                    $totalTunggakan += $sisa;
                    $rincian[] = [
                        'biaya' => $biaya,
                        'sisa' => $sisa,
                    ];
                }
            }
            // Jika semua biaya santri sudah dibebaskan, status = Lunas
            if (count($rincian) == 0) {
                $tunggakans->push([
                    'santri' => $santri,
                    'periode' => $periode,
                    'jumlah_tunggakan' => 0,
                    'rincian' => [],
                    'status' => 'Lunas (Semua biaya dibebaskan)',
                ]);
            } elseif ($totalTunggakan > 0) {
                $tunggakans->push([
                    'santri' => $santri,
                    'periode' => $periode,
                    'jumlah_tunggakan' => $totalTunggakan,
                    'rincian' => $rincian,
                    'status' => 'Belum Lunas',
                ]);
            } else {
                $tunggakans->push([
                    'santri' => $santri,
                    'periode' => $periode,
                    'jumlah_tunggakan' => 0,
                    'rincian' => [],
                    'status' => 'Lunas',
                ]);
            }
        }

        return $tunggakans;
    }

    public function index(Request $request)
    {
        $periodes = \App\Models\Pembayaran::select('periode')->distinct()->pluck('periode');
        $periode = $request->input('periode', $periodes->sortDesc()->first());
        $request->merge(['periode' => $periode]);
        $tunggakans = $this->getTunggakanData($request);
        // Filter hanya santri yang benar-benar masih punya tunggakan
        $filteredTunggakans = $tunggakans->where('jumlah_tunggakan', '>', 0);
        $totalTunggakan = $filteredTunggakans->sum('jumlah_tunggakan');
        $totalSantri = $filteredTunggakans->count();
        $rataRataTunggakan = $totalSantri > 0 ? round($totalTunggakan / $totalSantri) : 0;

        return view('tunggakan.index', compact('tunggakans', 'totalTunggakan', 'totalSantri', 'rataRataTunggakan', 'periodes', 'periode'));
    }

    public function show(Request $request)
    {
        $santriId = $request->query('santri_id');
        $periode = $request->query('periode');
        $santri = \App\Models\Santri::findOrFail($santriId);
        $biayas = \App\Models\Biaya::all();
        $biayaBebas = \App\Models\SantriBiayaBebas::where('santri_id', $santriId)->pluck('biaya_id')->toArray();
        $biayasByUnit = $biayas->where('unit', $santri->unit)->whereNotIn('id', $biayaBebas);
        $rincian = [];
        $totalTunggakan = 0;
        $jatuhTempo = null;
        // Aturan baru: jatuh tempo = tanggal 10 bulan berjalan (periode)
        if ($periode && preg_match('/^(\\d{4})-(\\d{2})$/', $periode, $m)) {
            $tahun = (int) $m[1];
            $bulan = (int) $m[2];
            $jatuhTempo = sprintf('%04d-%02d-10', $tahun, $bulan);
        }
        foreach ($biayasByUnit as $biaya) {
            $totalBayar = \App\Models\Pembayaran::where('santri_id', $santriId)
                ->where('biaya_id', $biaya->id)
                ->where('periode', $periode)
                ->sum('jumlah_bayar');
            $nominalWajib = $biaya->jumlah;
            $sisa = $nominalWajib - $totalBayar;
            if ($sisa > 0) {
                $rincian[] = [
                    'biaya' => $biaya,
                    'sisa' => $sisa,
                    'jatuh_tempo' => $jatuhTempo,
                ];
                $totalTunggakan += $sisa;
            }
        }

        return view('tunggakan.show', compact('santri', 'periode', 'rincian', 'totalTunggakan', 'jatuhTempo'));
    }

    public function exportExcel(Request $request)
    {
        $periodes = \App\Models\Pembayaran::select('periode')->distinct()->pluck('periode');
        $periode = $request->input('periode', $periodes->sortDesc()->first());
        $request->merge(['periode' => $periode]);
        $tunggakans = $this->getTunggakanData($request);

        return Excel::download(new TunggakanExport($tunggakans, $periode), 'tunggakan_'.$periode.'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $periodes = \App\Models\Pembayaran::select('periode')->distinct()->pluck('periode');
        $periode = $request->input('periode', $periodes->sortDesc()->first());
        $request->merge(['periode' => $periode]);
        $tunggakans = $this->getTunggakanData($request);
        $pdf = Pdf::loadView('exports.tunggakan_pdf', [
            'tunggakans' => $tunggakans,
            'periode' => $periode,
        ]);

        return $pdf->download('tunggakan_'.$periode.'.pdf');
    }
}
