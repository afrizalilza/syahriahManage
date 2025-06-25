<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TunggakanExport implements FromView
{
    protected $tunggakans;

    protected $periode;

    public function __construct($tunggakans, $periode)
    {
        $this->tunggakans = $tunggakans;
        $this->periode = $periode;
    }

    public function view(): View
    {
        return view('exports.tunggakan', [
            'tunggakans' => $this->tunggakans,
            'periode' => $this->periode,
        ]);
    }
}
