<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;


class ExportEbupotSheet3 implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('rekap-tetap.exports.ebupotSheet3');
    }

    public function title(): string
    {
        return 'Ref Daftar Objek Pajak';
    }
}
