@extends('layouts.app-template')
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
        @if (!auth()->user()->hasRole('cabang'))
            <form action="" method="GET">
                <div class="input-box">
                    <label for="">Cabang</label>
                    <select name="kd_entitas" id="cabang" class="form-input">
                        @foreach ($cabang as $item)
                            <option value="{{$item->kd_cabang}}" {{old('kd_entitas', \Request::get('kd_entitas') == $item->kd_cabang ? 'selected' : '')}}>{{$item->nama_cabang}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" class="btn btn-primary btn-icon-text no-print mt-4" value="Tampilkan">
            </form>
        @endif
        @if ($result)
            @php
                $bruto = 0;
                $kredit_pegawai = 0;
                $kredit_koprasi = 0;
                $iuran_koprasi = 0;
                $iuran_ik = 0;
                $pph_sekarang = 0;
                $pph_seharusnya = 0;
                $terutang_total = 0;
                $terutang_header = 0;
                $bruto_akhir_header = 0;
                $pph_akhir_header = 0;
                $hitungan_header = 0;
                $selisihAkhirBulan = 0;
                $brutoNonInsentif = 0;
                $total_last_selisih = 0;
            @endphp
            @foreach ($result as $item)
                @php
                    $row = $item['pph'];
                    $brutoNonInsentif += $row->penghasilanBrutoAkhirBulan - $row->total_insentif;
                    $bruto += $row->penghasilanBruto;
                    $bruto_akhir_header += $row->penghasilanBrutoAkhirBulan;
                    $pph_akhir_header += $row->pph_akhir_bulan;
                    $pph_sekarang += $row->seharusnya->total_seharusnya;
                    $pph_seharusnya += $row->pph;
                    $selisih_total = $pph_seharusnya - $pph_sekarang;
                    $selisih_header = $pph_seharusnya - $pph_sekarang;
                    $new_terutang_header = 0;
                    $current_terutang_header = $row->seharusnya->terutang;
                    $terutang_total += $row->seharusnya->terutang;
                    if ($current_terutang_header == 0 && $selisih_header != 0) {
                        $new_terutang_header = $selisih_header;
                    }
                    $total_last_selisih = (($row->penghasilanBrutoAkhirBulan - $row->total_insentif) * ($row->pengali_akhir)) - ($row->total_insentif * 0.05)
                    @endphp
            @endforeach
            <table class="tables-stripped" id="table" style="width: 100%; border: 1px solid black;">
                <thead>
                    <tr>
                        <th colspan="4"></th>
                        <th colspan="7">Database</th>
                        <th colspan="7">Seharusnya</th>
                        <th colspan="7">Akhir Bulan</th>
                    </tr>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">NIP</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">PTKP</th>
                    </tr>
                    <tr>
                        {{--  Database  --}}
                        <th>Bruto</th>
                        <th>Insentif</th>
                        <th>Total</th>
                        <th>Pengali</th>
                        <th>Pajak Insentif</th>
                        <th>
                            PPH
                            <br>
                            (PPH - Pajak Insentif)
                        </th>
                        <th>Terutang</th>
                        {{--  END Database  --}}
                        {{--  Seharusnya  --}}
                        <th>Bruto</th>
                        <th>Insentif</th>
                        <th>Total</th>
                        <th>Pengali</th>
                        <th>Pajak Insentif</th>
                        <th>
                            PPH
                            <br>
                            (PPH - Pajak Insentif)
                        </th>
                        <th>Selisih</th>
                        {{--  END Seharusnya  --}}
                        {{--  Seharusnya  --}}
                        <th>Bruto</th>
                        <th>Insentif</th>
                        <th>Total</th>
                        <th>Pengali</th>
                        <th>Pajak Insentif</th>
                        <th>
                            PPH
                            <br>
                            (PPH - Pajak Insentif)
                        </th>
                        <th>Selisih</th>
                        {{--  END Seharusnya  --}}
                    </tr>
                </thead>
                <tbody>
                    <form action="{{route('cek-pph.update-terutang')}}" method="post">
                        <input type="hidden" name="tahun" value="{{$tahun}}">
                        <input type="hidden" name="bulan" value="{{$bulan}}">
                        @csrf
                        @foreach ($result as $item)
                            @php
                                $row = $item['pph'];
                                $selisih = $row->pph - $row->seharusnya->total_seharusnya;
                                $selisih_pph_akhir_bulan = (($row->penghasilanBrutoAkhirBulan - $row->total_insentif) * ($row->pengali_akhir)) - ($row->total_insentif * 0.05) - ($row->seharusnya->total_seharusnya + $row->seharusnya->terutang);

                                // Database
                                $bruto_db = $row->penghasilanBruto;
                                $total_insentif_db = $row->total_insentif;
                                $total_pajak_insentif_db = $row->seharusnya->total_insentif;
                                $total_db = $bruto_db + $total_insentif_db;
                                $pengali_persen_db = $row->pengali * 100;
                                $pph_db = $item['pph_db'];
                                $terutang_db = $item['terutang_db'];
                                // END Database
                                // Seharusnya
                                $bruto_seharusnya = $row->penghasilanBruto;
                                $total_insentif_seharusnya = $row->total_insentif;
                                $total_pajak_insentif_seharusnya = $row->seharusnya->total_insentif;
                                $total_seharusnya = $bruto_seharusnya + $total_insentif_seharusnya;
                                $pengali_persen_seharusnya = $row->pengali * 100;
                                $pph_seharusnya = $row->pph;
                                $terutang_seharusnya = $row->seharusnya->terutang;
                                $selisih_seharusnya = $pph_db - $pph_seharusnya;
                                // END Seharusnya
                                // Akhir Bulan
                                $bruto_akhir = $row->penghasilanBrutoAkhirBulan;
                                $total_insentif_akhir = $row->total_insentif;
                                $total_pajak_insentif_akhir = $row->seharusnya->total_insentif;
                                $total_akhir = $bruto_akhir + $total_insentif_akhir;
                                $pengali_persen_akhir = $row->pengali_akhir * 100;
                                $pph_akhir = ($bruto_akhir * ($row->pengali_akhir)) - ($row->total_insentif * 0.05);
                                $terutang_akhir = $row->seharusnya->terutang;
                                $selisih_akhir = ($pph_akhir - ($row->seharusnya->total_seharusnya + $row->seharusnya->terutang));
                                // END Akhir Bulan
                            @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{str_contains($row->nip, 'U') ? '-' : $row->nip}}</td>
                                <td>{{$row->nama}}</td>
                                <td>{{$row->ptkp->kode}}</td>
                                {{--  Database  --}}
                                <td>{{formatRupiahExcel($bruto_db, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_insentif_db, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_db, 0, true)}}</td>
                                <td>{{$pengali_persen_db}}%</td>
                                <td>{{formatRupiahExcel($total_pajak_insentif_db, 0, true)}}</td>
                                <td>{{formatRupiahExcel($pph_db, 0, true)}}</td>
                                <td>{{formatRupiahExcel($terutang_db, 0, true)}}</td>
                                {{--  END Database  --}}
                                {{--  Seharusnya  --}}
                                <td>{{formatRupiahExcel($bruto_seharusnya, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_insentif_seharusnya, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_seharusnya, 0, true)}}</td>
                                <td>{{$pengali_persen_seharusnya}}%</td>
                                <td>{{formatRupiahExcel($total_pajak_insentif_seharusnya, 0, true)}}</td>
                                <td>{{formatRupiahExcel($pph_seharusnya, 0, true)}}</td>
                                <td>{{formatRupiahExcel($selisih_seharusnya, 0, true)}}</td>
                                {{--  END Seharusnya  --}}
                                {{--  Akhir Bulan  --}}
                                <td>{{formatRupiahExcel($bruto_akhir, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_insentif_akhir, 0, true)}}</td>
                                <td>{{formatRupiahExcel($total_akhir, 0, true)}}</td>
                                <td>{{$pengali_persen_akhir}}%</td>
                                <td>{{formatRupiahExcel($total_pajak_insentif_akhir, 0, true)}}</td>
                                <td>{{formatRupiahExcel($pph_akhir, 0, true)}}</td>
                                <td>{{formatRupiahExcel($selisih_akhir, 0, true)}}</td>
                                {{--  END Akhir Bulan  --}}
                            </tr>
                        @endforeach
                        <input type="submit" class="btn btn-success" value="Update">
                    </form>
                </tbody>
                {{--  <tfoot>
                    <tr>
                        <th colspan="4" style="font-weight: bold;">GRAND TOTAL</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto_akhir_header, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($brutoNonInsentif, 0, true)}}</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_sekarang,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_seharusnya,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($selisih_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($terutang_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_akhir_header,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($total_last_selisih,0,true)}}</th>
                    </tr>
                </tfoot>  --}}
            </table>
        @endif
    </div>
</div>

@endSection
