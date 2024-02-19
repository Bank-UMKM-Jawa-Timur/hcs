<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEbupot implements FromView, WithColumnFormatting
{
    private $data;
    private $lastdate;
    private $penandatangan;
    private $FORMAT_NUMBER;

    public function __construct($data, $lastdate, $penandatangan)
    {
        $this->FORMAT_NUMBER = '_(* #,##_);_(* (#,##);_(* "-"_);_(@_)';
        $this->data = $data;
        $this->lastdate = $lastdate;
        $this->penandatangan = $penandatangan;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function columnFormats(): array
    {
        return [
            'D' => $this->FORMAT_NUMBER,
            'J' => $this->FORMAT_NUMBER,
        ];
    }
    public function collection()
    {
        //
    }
    public function view(): View
    {
        return view('rekap-tetap.exports.ebupot', ['data' => $this->data, 'lastdate' => $this->lastdate, 'penandatangan' => $this->penandatangan]);
    }
}
