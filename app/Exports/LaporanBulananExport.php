<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanBulananExport implements FromView, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.laporan_bulanan_excel', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        // Styling header judul dan summary card
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFill()->setFillType('solid')->getStartColor()->setRGB('2193b0');
        $sheet->mergeCells('A1:'.$sheet->getHighestColumn().'1');
        // Periode
        $sheet->getStyle('A2')->getFont()->setBold(false);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('left');
        // Kolom header
        $headerRow = 4;
        $sheet->getStyle('A'.$headerRow.':'.$sheet->getHighestColumn().$headerRow)
            ->getFont()->setBold(true);
        $sheet->getStyle('A'.$headerRow.':'.$sheet->getHighestColumn().$headerRow)
            ->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.$headerRow.':'.$sheet->getHighestColumn().$headerRow)
            ->getFill()->setFillType('solid')->getStartColor()->setRGB('eaeaea');
        // Border untuk seluruh tabel UTAMA saja
        $lastRow = $sheet->getHighestRow();
        $colEnd = $sheet->getHighestColumn();
        // Cari baris terakhir dari tabel utama (baris data santri)
        $summaryStartRow = $lastRow + 2; // ini nanti akan diubah setelah summary dipisah
        // Asumsikan baris summary selalu setelah tabel utama dan ada 1 baris kosong
        // Hitung jumlah kolom utama
        $tableColCount = count($sheet->toArray()[3]); // header di baris 4 (index 3)
        $colEndTable = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($tableColCount);
        // Border hanya untuk tabel utama
        $sheet->getStyle("A1:{$colEndTable}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle('thin');
        // --- Pisahkan summary (rekap) di bawah tabel santri ---
        $summaryStartRow = $lastRow + 2;
        $mergeCol = 2;
        $colA = 'A';
        $colB = 'B';
        $sheet->mergeCells("{$colA}{$summaryStartRow}:{$colB}{$summaryStartRow}");
        $sheet->mergeCells("{$colA}".($summaryStartRow + 1).":{$colB}".($summaryStartRow + 1));
        $sheet->mergeCells("{$colA}".($summaryStartRow + 2).":{$colB}".($summaryStartRow + 2));
        $sheet->mergeCells("{$colA}".($summaryStartRow + 3).":{$colB}".($summaryStartRow + 3));
        $sheet->getStyle("{$colA}{$summaryStartRow}:{$colB}".($summaryStartRow + 3))->getAlignment()->setHorizontal('left');
        $sheet->getStyle("{$colA}{$summaryStartRow}")->getFont()->setBold(true);
        $sheet->getStyle("{$colA}{$summaryStartRow}")->getFill()->setFillType('solid')->getStartColor()->setRGB('e3effa');
        $sheet->getStyle("{$colB}{$summaryStartRow}")->getFont()->setBold(true)->getColor()->setRGB('009900');
        $sheet->getStyle("{$colA}".($summaryStartRow + 2))->getFont()->getColor()->setRGB('ff9900');
        // Hilangkan border pada kolom selain A dan B di bagian summary
        $maxCol = $sheet->getHighestColumn();
        if ($maxCol > 'B') {
            foreach (range('C', $maxCol) as $col) {
                for ($row = $summaryStartRow; $row <= $summaryStartRow + 3; $row++) {
                    $sheet->getStyle("{$col}{$row}")->getBorders()->getAllBorders()->setBorderStyle('none');
                }
            }
        }

        return [];
    }
}
