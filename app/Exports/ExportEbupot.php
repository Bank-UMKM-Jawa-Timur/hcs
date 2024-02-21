<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportEbupot implements FromView, WithTitle
{
    private $data;
    private $lastdate;
    private $penandatangan;

    public function __construct($data, $lastdate, $penandatangan)
    {
        $this->data = $data;
        $this->lastdate = $lastdate;
        $this->penandatangan = $penandatangan;
    }

    public function collection()
    {
        //
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function view(): View
    {
        return view('rekap-tetap.exports.ebupot', [
            'data' => $this->data,
            'lastdate' => $this->lastdate,
            'penandatangan' => $this->penandatangan
        ]);
    }

    // Implementasi metode WithTitle untuk menentukan judul sheet
    public function title(): string
    {
        return '21';
    }
}