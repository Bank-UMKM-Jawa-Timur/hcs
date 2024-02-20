<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportEbupotSheet1 implements FromView, WithTitle
{
    private $total_data;
    private $month;
    private $year;

    public function __construct($total_data, $month, $year)
    {
        $this->total_data = $total_data;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        //
    }

    public function view(): View
    {
        return view('rekap-tetap.exports.ebupotSheet1', [
            'total_data' => $this->total_data,
            'bulan' => $this->month,
            'tahun' => $this->year
        ]);
    }

    // Implementasi metode WithTitle untuk menentukan judul sheet
    public function title(): string
    {
        return 'Rekap';
    }
}
