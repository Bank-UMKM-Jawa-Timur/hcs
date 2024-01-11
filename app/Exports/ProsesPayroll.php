<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProsesPayroll implements FromView, WithColumnFormatting
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
        ];
    }

    public function view(): View
    {
        return view('exports.proses-gaji.payroll', ['data' => $this->data]);
    }
}
