<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RekapTetapExport implements FromView, WithColumnFormatting
{
    private $data;
    private $FORMAT_NUMBER;

    public function __construct($data)
    {
        $this->FORMAT_NUMBER = '_(* #,##_);_(* (#,##);_(* "-"_);_(@_)';
        $this->data = $data;
    }

    public function columnFormats(): array
    {
        return [
            'H' => $this->FORMAT_NUMBER,
            'I' => $this->FORMAT_NUMBER,
            'J' => $this->FORMAT_NUMBER,
            'K' => $this->FORMAT_NUMBER,
            'L' => $this->FORMAT_NUMBER,
            'M' => $this->FORMAT_NUMBER,
            'N' => $this->FORMAT_NUMBER,
            'O' => $this->FORMAT_NUMBER,
            'P' => $this->FORMAT_NUMBER,
            'Q' => $this->FORMAT_NUMBER,
            'R' => $this->FORMAT_NUMBER,
            'S' => $this->FORMAT_NUMBER,
        ];
    }

    public function view(): View
    {
        return view('rekap-tetap.exports.export', ['data' => $this->data]);
    }
}
