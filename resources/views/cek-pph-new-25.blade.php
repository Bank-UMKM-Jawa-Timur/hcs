@extends('layouts.app-template')
@push('style')
    <style>
        .table-wrapper {
            overflow-y: scroll;
            overflow-x: scroll;
            height: fit-content;
            max-height: 66.4vh;
            padding-bottom: 20px;
            margin-top: 20px;
        }
        table {
            min-width: max-content;
            border-collapse: separate;
            border-spacing: 0px;
        }

        table thead {
            position: sticky;
            top: 0px;
            text-align: center;
            font-weight: normal;
            font-size: 18px;
            z-index: 999;
        }

        .left{
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            background-color: #fff;
            font-size: 18px;
        }

        table th, table td {
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        table td {
            text-align: left;
            font-size: 15px;
            padding-left: 20px;
        }

        .text-right {
            text-align: right !important;
        }
    </style>
@endpush
@section('content')
<div class="head mt-5">
    <div class="lg:flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">PPH</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">PPH</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">{{$nama_cabang}}</a>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping text-center space-y-5">
        <form action="" method="GET">
                @if (!auth()->user()->hasRole('cabang'))
                <div class="input-box">
                    <label for="">Cabang</label>
                    <select name="kd_entitas" id="cabang" class="form-input" required>
                        <option value="">--- Pilih Kantor ---</option>
                        @foreach ($cabang as $item)
                            <option value="{{$item->kd_cabang}}" {{old('kd_entitas', \Request::get('kd_entitas') == $item->kd_cabang ? 'selected' : '')}}>{{$item->nama_cabang}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="flex gap-5 lg:flex-row flex-col items-center w-full justify-between">
                    <div class="input-box w-full text-left">
                            <label for="">Bulan<span class="text-red-500">*</span></label>
                            <select name="bulan" id="bulan"
                                class="form-input">
                                <option value="0">-- Pilih bulan --</option>
                                <option value="1" @if(\Request::get('bulan') == 1) selected @endif>Januari</option>
                                <option value="2" @if(\Request::get('bulan') == 2) selected @endif>Februari</option>
                                <option value="3" @if(\Request::get('bulan') == 3) selected @endif>Maret</option>
                                <option value="4" @if(\Request::get('bulan') == 4) selected @endif>April</option>
                                <option value="5" @if(\Request::get('bulan') == 5) selected @endif>Mei</option>
                                <option value="6" @if(\Request::get('bulan') == 6) selected @endif>Juni</option>
                                <option value="7" @if(\Request::get('bulan') == 7) selected @endif>Juli</option>
                                <option value="8" @if(\Request::get('bulan') == 8) selected @endif>Agustus</option>
                                <option value="9" @if(\Request::get('bulan') == 9) selected @endif>September</option>
                                <option value="10" @if(\Request::get('bulan') == 10) selected @endif>Oktober</option>
                                <option value="11" @if(\Request::get('bulan') == 11) selected @endif>November</option>
                                <option value="12" @if(\Request::get('bulan') == 12) selected @endif>Desember</option>
                            </select>
                    </div>
                    <div class="input-box w-full text-left">
                        <label for="">Tahun<span class="text-red-500">*</span></label>
                        <select name="tahun" id="tahun"
                            class="form-input">
                            <option value="0">Pilih Tahun</option>
                            @php
                                $earliest = 2024;
                                $tahunSaatIni = date('Y');
                                $awal = $tahunSaatIni - 5;
                                $akhir = $tahunSaatIni + 5;
                            @endphp

                            @for ($tahun = $earliest; $tahun <= $akhir; $tahun++)
                                <option {{ Request()->tahun == $tahun || date('Y') == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                                    {{ $tahun }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary btn-icon-text no-print mt-4" value="Tampilkan">
            </form>
        @if ($result)
            @php
                // Database Lama
                $sum_bruto_db = 0;
                $sum_insentif_db = 0;
                $sum_total_db = 0;
                $sum_pph_bentukan_db = 0;
                $sum_pajak_insentif_db = 0;
                $sum_pph_db = 0;
                // End Database Lama
                // Database Baru
                $sum_bruto_new_db = 0;
                $sum_insentif_new = 0;
                $sum_total_new = 0;
                $sum_pph_bentukan_new_db = 0;
                $sum_pajak_insentif_new_db = 0;
                $sum_pph_new_db = 0;
                $sum_selisih_new_db = 0;
                // End Database Baru
                // Bruto Insentif
                $sum_bruto_baru = 0;
                $sum_insentif_baru = 0;
                $sum_total_baru = 0;
                $sum_pph_bentukan_baru = 0;
                $sum_pajak_insentif_baru = 0;
                $sum_pph_baru = 0;
                $sum_selisih_baru = 0;
                // End Bruto Insentif
                // Seharusnya
                $sum_bruto_seharusnya = 0;
                $sum_insentif_seharusnya = 0;
                $sum_total_seharusnya = 0;
                $sum_pph_bentukan_seharusnya = 0;
                $sum_pajak_insentif_seharusnya = 0;
                $sum_pph_seharusnya = 0;
                $sum_selisih_seharusnya = 0;
                // End Seharusnya
                // bruto
                $sum_bruto_pajak_insentif = 0;
                $sum_selisih_bruto_isentif = 0;
                // end bruto
            @endphp
            <div class="table-wrapper">
                <table class="tables-stripped" id="table" style="width: 100%; border: 1px solid black;">
                    <thead>
                        <tr>
                            <th colspan="4"></th>
                            <th colspan="7" style="background-color: red; color: white;">Database (Lama)</th>
                            <th colspan="8" style="background-color: blue; color: white;">Database (Baru)</th>
                            <th colspan="8" style="background-color: green; color: white;" >Bruto Insentif</th>
                            <th colspan="8" style="background-color: yellow;">Seharusnya 1-25</th>
                            {{--  <th colspan="9" style="background-color: #7FFF00">Akhir Bulan</th>  --}}
                        </tr>
                        <tr>
                            <th rowspan="2" style="background-color: #fff">No</th>
                            <th rowspan="2" style="background-color: #fff" class="left">NIP</th>
                            <th rowspan="2" style="background-color: #fff">Nama</th>
                            <th rowspan="2" style="background-color: #fff">PTKP</th>
                        </tr>
                        <tr>
                            {{--  Database Lama  --}}
                            <th style="background-color: red; color: white;">Bruto</th>
                            <th style="background-color: red; color: white;">Insentif</th>
                            <th style="background-color: red; color: white;">Total</th>
                            <th style="background-color: red; color: white;">Pengali</th>
                            <th style="background-color: red; color: white;">PPh Bentukan<br>(Total * Pengali)</th>
                            <th style="background-color: red; color: white;">Pajak Insentif<br>(Insentif * 5%)</th>
                            <th style="background-color: red; color: white;">
                                PPH
                                <br>
                                (PPH - Pajak Insentif)
                            </th>
                            {{--  <th style="background-color: red; color: white;">Terutang</th>  --}}
                            {{--  END Database Lama  --}}
                            {{--  Database Baru  --}}
                            <th style="background-color: blue; color: white;">Bruto</th>
                            <th style="background-color: blue; color: white;">Insentif</th>
                            <th style="background-color: blue; color: white;">Total</th>
                            <th style="background-color: blue; color: white;">Pengali</th>
                            <th style="background-color: blue; color: white;">PPh Bentukan<br>(Total * Pengali)</th>
                            <th style="background-color: blue; color: white;">Pajak Insentif<br>(Insentif * 5%)</th>
                            <th style="background-color: blue; color: white;">
                                PPH
                                <br>
                                (PPH - Pajak Insentif)
                            </th>
                            {{--  <th style="background-color: blue; color: white;">Terutang</th>  --}}
                            <th style="background-color: blue; color: white;">Selisih<br>(PPH(DB Baru) - PPH(DB Lama))</th>
                            {{--  END Database Baru  --}}

                            {{-- bruto insentif --}}
                            <th style="background-color: green; color: white;">Bruto</th>
                            <th style="background-color: green; color: white;">Insentif</th>
                            <th style="background-color: green; color: white;">Total</th>
                            <th style="background-color: green; color: white;">Pengali</th>
                            <th style="background-color: green; color: white;">PPh Bentukan<br>(Total * Pengali)</th>
                            <th style="background-color: green; color: white;">Pajak Insentif<br>(Insentif * 5%)</th>
                            <th style="background-color: green; color: white;">
                                PPH
                                <br>
                                (PPH - Pajak Insentif)
                            </th>
                            {{--  <th style="background-color: blue; color: white;">Terutang</th>  --}}
                            <th style="background-color: green; color: white;">Selisih<br>(PPH(Bruto Insentif) - PPH(DB Baru))</th>
                            {{-- end bruto --}}
                            {{--  Seharusnya  --}}
                            <th style="background-color: yellow;">Bruto</th>
                            <th style="background-color: yellow;">Insentif</th>
                            <th style="background-color: yellow;">Total</th>
                            <th style="background-color: yellow;">Pengali</th>
                            <th style="background-color: yellow;">PPh Bentukan<br>(Total * Pengali)</th>
                            <th style="background-color: yellow;">Pajak Insentif<br>(Insentif * 5%)</th>
                            <th style="background-color: yellow;">
                                PPH
                                <br>
                                (PPH - Pajak Insentif)
                            </th>
                            <th style="background-color: yellow;">Selisih<br>(PPH(Seharusnya) - PPH(Bruto Insentif))</th>
                            {{--  END Seharusnya  --}}
                            {{--  Akhir Bulan  --}}
                            {{--  <th style="background-color: #7FFF00">Bruto</th>
                            <th style="background-color: #7FFF00">
                                Insentif
                                <br>
                                1-25
                            </th>
                            <th style="background-color: #7FFF00">
                                Insentif
                                <br>
                                26-31
                            </th>
                            <th style="background-color: #7FFF00">Total</th>
                            <th style="background-color: #7FFF00">Pengali</th>
                            <th style="background-color: #7FFF00">
                                Pajak Insentif
                                <br>
                                1-25
                            </th>
                            <th style="background-color: #7FFF00">
                                Pajak Insentif
                                <br>
                                26-31
                            </th>
                            <th style="background-color: #7FFF00">
                                PPH
                                <br>
                                (PPH - Pajak Insentif)
                            </th>
                            <th style="background-color: #7FFF00">Selisih</th>  --}}
                            {{--  END Akhir Bulan  --}}
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{route('cek-pph.update-terutang')}}" method="post">
                            <input type="hidden" name="tahun" value="{{$tahun}}">
                            <input type="hidden" name="bulan" value="{{$bulan}}">
                            @csrf
                            @foreach ($result as $item)
                                @php
                                    $row = $item['old']['pph'];
                                    $total_seharusnya_akhir_bulan = $row->seharusnya ? $row->seharusnya->total_seharusnya : 0;
                                    $total_seharusnya_terutang_akhir_bulan = $row->seharusnya ? $row->seharusnya->terutang : 0;
                                    $selisih = $row->pph - $total_seharusnya_akhir_bulan;
                                    $selisih_pph_akhir_bulan = round(($row->penghasilanBrutoAkhirBulan - $row->total_insentif) * ($row->pengali_akhir)) - ($row->total_insentif * 0.05) - ($total_seharusnya_akhir_bulan + $total_seharusnya_terutang_akhir_bulan);

                                    // Database Lama
                                    $rowOld = $item['old']['pph'];
                                    $bruto_db = $rowOld->penghasilanBruto;
                                    $total_insentif_db = $rowOld->total_insentif_25;
                                    $pph_bentukan_db = $rowOld->seharusnya ? $rowOld->seharusnya->pph_bentukan : 0;
                                    $total_pajak_insentif_db = $rowOld->seharusnya ? $rowOld->seharusnya->total_insentif : 0;
                                    $total_db = $bruto_db + $total_insentif_db;
                                    $pengali_persen_db = $rowOld->pengali_db * 100;
                                    $pph_db = $item['old']['pph_db'];
                                    $terutang_db = $item['old']['terutang_db'];
                                    // END Database Lama
                                    // Database Baru
                                    $rowNew = $item['new']['pph'];
                                    $bruto_new_db = $rowNew->penghasilanBruto - $rowNew->total_insentif_25;
                                    $total_insentif_new_db = $rowNew->total_insentif_25;
                                    $pph_bentukan_new_db = $rowNew->seharusnya ? $rowNew->seharusnya->pph_bentukan : 0;
                                    $total_pajak_insentif_new_db = $rowNew->seharusnya ? $rowNew->seharusnya->total_insentif : 0;
                                    $total_new_db = $bruto_new_db + $total_insentif_new_db;
                                    $pengali_persen_new_db = $rowNew->pengali_baru * 100;
                                    $pph_new_db = $pph_bentukan_new_db - $total_pajak_insentif_new_db;
                                    $terutang_new_db = $item['new']['terutang_db'];
                                    $selisih_new_db = $pph_new_db - $pph_db;
                                    // END Database Baru
                                    // Bruto Insentif
                                    $rowNew = $item['new']['pph'];
                                    $bruto_baru = $rowNew->penghasilanBrutoBaru - $rowNew->total_insentif_baru;
                                    $total_insentif_baru = $rowNew->total_insentif_baru;
                                    $pph_bentukan_baru = $rowNew->pph_bentukan_baru;
                                    $total_pajak_insentif_baru = $rowNew->pajak_insentif_baru;
                                    $total_baru = $bruto_baru + $rowNew->total_insentif_baru;
                                    $pengali_persen_baru = $rowNew->pengali_baru * 100;
                                    $pph_baru = $pph_bentukan_baru - $total_pajak_insentif_baru;
                                    $terutang_baru = $item['new']['terutang_db'];
                                    $selisih_baru = $pph_baru - $pph_new_db;
                                    // END Bruto Insentif
                                    // Seharusnya
                                    $bruto_seharusnya = $rowNew->penghasilanBrutoBaru - $rowNew->total_insentif_baru;
                                    $total_insentif_seharusnya = $rowNew->total_insentif_baru;
                                    $total_pajak_insentif_seharusnya = floor($total_insentif_seharusnya * 0.05);
                                    $total_seharusnya = $bruto_seharusnya + $rowNew->total_insentif_baru;
                                    $pengali_persen_seharusnya = $rowNew->pengali_baru * 100;
                                    $pph_bentukan_seharusnya = floor($total_seharusnya * $rowNew->pengali_baru);
                                    $pph_seharusnya = $pph_bentukan_seharusnya - $total_pajak_insentif_seharusnya;
                                    $terutang_seharusnya = $rowNew->seharusnya ? $rowNew->seharusnya->terutang : 0;
                                    $selisih_seharusnya = floor($pph_seharusnya - $pph_baru);
                                    // END Seharusnya
                                    // bruto
                                    $selisih_bruto_insentif = $total_pajak_insentif_new_db - $total_pajak_insentif_seharusnya;
                                    // end bruto
                                    // Akhir Bulan
                                    $bruto_akhir = $row->penghasilanBrutoAkhirBulan;
                                    $total_insentif_akhir = $row->total_insentif;
                                    $total_insentif_25 = $row->total_insentif_25;
                                    $total_insentif_26 = $row->total_insentif_26;
                                    $pajak_insentif_25 = $row->pajak_insentif_25;
                                    $pajak_insentif_26 = $row->pajak_insentif_26;
                                    $total_pajak_insentif_akhir = $row->seharusnya ? $row->seharusnya->total_insentif : 0;
                                    $total_akhir = $bruto_akhir + $total_insentif_akhir;
                                    $pengali_persen_akhir = $row->pengali_akhir * 100;
                                    $pph_akhir = ($bruto_akhir * ($row->pengali_akhir)) - round(($row->total_insentif * 0.05));
                                    $pph_akhir = round($pph_akhir);
                                    $terutang_akhir = $row->seharusnya ? $row->seharusnya->terutang : 0;
                                    $selisih_akhir = ($pph_akhir - (($row->seharusnya ? $row->seharusnya->total_seharusnya : 0) + ($row->seharusnya ? $row->seharusnya->terutang : 0)));
                                    // END Akhir Bulan

                                    // Hitung Total
                                    // Database Lama
                                    $sum_bruto_db += $bruto_db;
                                    $sum_insentif_db += $total_insentif_db;
                                    $sum_total_db += $total_db;
                                    $sum_pph_bentukan_db += $pph_bentukan_db;
                                    $sum_pajak_insentif_db += $total_pajak_insentif_db;
                                    $sum_pph_db += $pph_db;
                                    // End Database Lama
                                    // Database Baru
                                    $sum_bruto_new_db += $bruto_new_db;
                                    $sum_insentif_new += $total_insentif_new_db;
                                    $sum_total_new += $total_new_db;
                                    $sum_pph_bentukan_new_db += $pph_bentukan_new_db;
                                    $sum_pajak_insentif_new_db += $total_pajak_insentif_new_db;
                                    $sum_pph_new_db += $pph_new_db;
                                    $sum_selisih_new_db += $selisih_new_db;
                                    // End Database Baru
                                    // Bruto Insentif
                                    $sum_bruto_baru += $bruto_baru;
                                    $sum_insentif_baru += $total_insentif_baru;
                                    $sum_total_baru += $total_baru;
                                    $sum_pph_bentukan_baru += $pph_bentukan_baru;
                                    $sum_pajak_insentif_baru += $total_pajak_insentif_baru;
                                    $sum_pph_baru += $pph_baru;
                                    $sum_selisih_baru += $selisih_baru;
                                    // End Bruto Insentif
                                    // Seharusnya
                                    $sum_bruto_seharusnya += $bruto_seharusnya;
                                    $sum_insentif_seharusnya += $total_insentif_seharusnya;
                                    $sum_total_seharusnya += $total_seharusnya;
                                    $sum_pph_bentukan_seharusnya += $pph_bentukan_seharusnya;
                                    $sum_pajak_insentif_seharusnya += $row->pajak_insentif_25;
                                    $sum_pph_seharusnya += $pph_seharusnya;
                                    $sum_selisih_seharusnya += $selisih_seharusnya;
                                    // End Seharusnya
                                    // bruto
                                    $sum_selisih_bruto_isentif += $selisih_bruto_insentif;
                                    // end bruto
                                    // End Hitung Total
                                @endphp
                                <tr style="color: @if($item['tanggal_penonaktifan']) red !important @else black @endif;">
                                    <td>{{$loop->iteration}}</td>
                                    <td class="left">{{str_contains($row->nip, 'U') ? '-' : $row->nip}}</td>
                                    <td>{{$row->nama}}</td>
                                    <td>{{$row->ptkp->kode == "TK" ? "TK/0" : $row->ptkp->kode}}</td>
                                    {{--  Database Lama  --}}
                                    <td class="text-right">{{formatRupiahExcel($bruto_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_insentif_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_db, 0, true)}}</td>
                                    <td>{{$pengali_persen_db}}%</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_bentukan_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_pajak_insentif_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_db, 0, true)}}</td>
                                    {{--  <td>{{formatRupiahExcel($terutang_db, 0, true)}}</td>  --}}
                                    {{--  END Database Lama  --}}

                                    {{--  Database Baru  --}}
                                    <td class="text-right">{{formatRupiahExcel($bruto_new_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_insentif_new_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_new_db, 0, true)}}</td>
                                    <td>{{$pengali_persen_new_db}}%</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_bentukan_new_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_pajak_insentif_new_db, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_new_db, 0, true)}}</td>
                                    {{--  <td>{{formatRupiahExcel($terutang_new_db, 0, true)}}</td>  --}}
                                    <td class="text-right">{{formatRupiahExcel($selisih_new_db, 0, true)}}</td>
                                    {{--  END Database Baru  --}}

                                    {{-- Bruto --}}
                                    <td class="text-right">{{formatRupiahExcel($bruto_baru, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_insentif_baru, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_baru, 0, true)}}</td>
                                    <td>{{$pengali_persen_baru}}%</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_bentukan_baru, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_pajak_insentif_baru, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_baru, 0, true)}}</td>
                                    {{--  <td>{{formatRupiahExcel($terutang_baru, 0, true)}}</td>  --}}
                                    <td class="text-right">{{formatRupiahExcel($selisih_baru, 0, true)}}</td>
                                    {{-- End Bruto --}}

                                    {{--  Seharusnya  --}}
                                    <td class="text-right">{{formatRupiahExcel($bruto_seharusnya, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_insentif_seharusnya, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_seharusnya, 0, true)}}</td>
                                    <td>{{$pengali_persen_seharusnya}}%</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_bentukan_seharusnya, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($total_pajak_insentif_seharusnya, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($pph_seharusnya, 0, true)}}</td>
                                    <td class="text-right">{{formatRupiahExcel($selisih_seharusnya, 0, true)}}</td>
                                    {{--  END Seharusnya  --}}

                                    {{--  Akhir Bulan  --}}
                                    {{--  <td>{{formatRupiahExcel($bruto_akhir, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($total_insentif_25, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($total_insentif_26, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($total_akhir, 0, true)}}</td>
                                    <td>{{$pengali_persen_akhir}}%</td>
                                    <td>{{formatRupiahExcel($pajak_insentif_25, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($pajak_insentif_26, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($pph_akhir, 0, true)}}</td>
                                    <td>{{formatRupiahExcel($selisih_akhir, 0, true)}}</td>  --}}
                                    {{--  END Akhir Bulan  --}}
                                </tr>
                            @endforeach
                            {{--  <input type="submit" class="btn btn-success" value="Update">  --}}
                        </form>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" style="font-weight: bold;">GRAND TOTAL</th>
                            {{--  Database Lama  --}}
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_bruto_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_insentif_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_total_db, 0, true)}}</th>
                            <th></th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_bentukan_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pajak_insentif_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_db, 0, true)}}</th>
                            {{--  End Database Lama  --}}
                            {{--  Database Baru  --}}
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_bruto_new_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_insentif_new, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_total_new, 0, true)}}</th>
                            <th></th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_bentukan_new_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pajak_insentif_new_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_new_db, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_selisih_new_db, 0, true)}}</th>
                            {{--  End Database Baru  --}}
                            {{-- Bruto --}}
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_bruto_baru, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_insentif_baru, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_total_baru, 0, true)}}</th>
                            <th></th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_bentukan_baru, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pajak_insentif_baru, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_baru, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_selisih_baru, 0, true)}}</th>
                            {{-- End Bruto --}}
                            {{--  Seharusnya  --}}
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_bruto_seharusnya, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_insentif_seharusnya, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_total_seharusnya, 0, true)}}</th>
                            <th></th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_bentukan_seharusnya, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pajak_insentif_seharusnya, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_pph_seharusnya, 0, true)}}</th>
                            <th class="text-right" style="font-weight: bold">{{formatRupiahExcel($sum_selisih_seharusnya, 0, true)}}</th>
                            {{--  End Seharusnya  --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

@endSection
