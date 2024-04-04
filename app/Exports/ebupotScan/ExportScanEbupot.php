<?php

namespace App\Exports\ebupotScan;

use App\Exports\ExportEbupot;
use App\Exports\ExportEbupot26;
use App\Exports\ExportEbupotSheet1;
use App\Exports\ExportEbupotSheet3;
use App\Exports\ExportEbupotSheet4;
use App\Exports\ExportEbupotSheet5;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportScanEbupot implements WithMultipleSheets
{
    private $data;
    private $lastdate;
    private $penandatangan;
    private $total_data;
    private $month;
    private $year;

    public function __construct($data, $lastdate, $penandatangan, $total_data, $month, $year)
    {
        $this->data = $data;
        $this->lastdate = $lastdate;
        $this->penandatangan = $penandatangan;
        $this->total_data = $total_data;
        $this->month = $month;
        $this->year = $year;
    }

    public function sheets(): array
    {
        return [
            new ExportEbupotSheet1($this->total_data, $this->month, $this->year),
            new ExportEbupot($this->data, $this->lastdate, $this->penandatangan),
            new ExportEbupot26($this->data, $this->lastdate, $this->penandatangan),
            new ExportEbupotSheet3(),
            new ExportEbupotSheet4(),
            new ExportEbupotSheet5(),
        ];

    }
}
