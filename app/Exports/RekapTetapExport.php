<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RekapTetapExport implements FromView, WithColumnFormatting
{
    private $data;
    private $grandtotal;
    private $FORMAT_NUMBER;

    public function __construct($data, $grandtotal)
    {
        $this->FORMAT_NUMBER = '_(* #,##_);_(* (#,##);_(* "-"_);_(@_)';
        $this->data = $data;
        $this->grandtotal = $grandtotal;
    }

    public function columnFormats(): array
    {
        return [
            'C' => $this->FORMAT_NUMBER,
            'D' => $this->FORMAT_NUMBER,
            'E' => $this->FORMAT_NUMBER,
            'F' => $this->FORMAT_NUMBER,
            'G' => $this->FORMAT_NUMBER,
            'H' => $this->FORMAT_NUMBER,
            'I' => $this->FORMAT_NUMBER,
            'J' => $this->FORMAT_NUMBER,
            'K' => $this->FORMAT_NUMBER,
            'L' => $this->FORMAT_NUMBER,
            'M' => $this->FORMAT_NUMBER,
            'N' => $this->FORMAT_NUMBER,
            'O' => $this->FORMAT_NUMBER,
            'P' => $this->FORMAT_NUMBER,
            'Q' => $this->FORMAT_NUMBER
        ];
    }

    public function view(): View
    {
        return view('rekap-tetap.table.table', ['data' => $this->data, 'grandTotal' => $this->grandtotal]);
    }
}
