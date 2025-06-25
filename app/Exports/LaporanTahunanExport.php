<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanTahunanExport implements FromView
{
    protected $tahun;

    protected $biayas;

    protected $rekapBulanan;

    protected $totalPemasukan;

    protected $totalPengeluaran;

    protected $saldoAkhir;

    public function __construct($tahun, $biayas, $rekapBulanan, $totalPemasukan, $totalPengeluaran, $saldoAkhir)
    {
        $this->tahun = $tahun;
        $this->biayas = $biayas;
        $this->rekapBulanan = $rekapBulanan;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->saldoAkhir = $saldoAkhir;
    }

    public function view(): View
    {
        return view('exports.laporan_tahunan_excel', [
            'tahun' => $this->tahun,
            'biayas' => $this->biayas,
            'rekapBulanan' => $this->rekapBulanan,
            'totalPemasukan' => $this->totalPemasukan,
            'totalPengeluaran' => $this->totalPengeluaran,
            'saldoAkhir' => $this->saldoAkhir,
        ]);
    }
}
