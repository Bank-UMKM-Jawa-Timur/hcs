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
            @endphp
            @foreach ($result as $item)
                @php
                    $row = $item['pph'];
                    $bruto += $row->penghasilanBruto;
                    $bruto_akhir_header += $row->penghasilanBrutoAkhirBulan;
                    $pph_akhir_header += $row->pph_akhir_bulan;
                    $kredit_pegawai += $row->potongan?->kredit_pegawai ? $row->potongan->kredit_pegawai : 0;
                    $kredit_koprasi += $row->potongan?->kredit_koperasi ? $row->potongan->kredit_koperasi : 0;
                    $iuran_koprasi += $row->potongan?->iuran_koperasi ? $row->potongan->iuran_koperasi : 0;
                    $iuran_ik += $row->potongan?->iuran_ik ? $row->potongan->iuran_ik : 0;
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
                    @endphp
            @endforeach
            <table class="tables-stripped" id="table" style="width: 100%; border: 1px solid black;">
                <thead style="margin-bottom: 3rem">
                    <tr>
                        <th colspan="4" style="font-weight: bold;">GRAND TOTAL</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto_akhir_header, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($kredit_pegawai, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($kredit_koprasi, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($iuran_koprasi, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($iuran_ik, 0, true)}}</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_sekarang,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_seharusnya,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($selisih_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($terutang_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_akhir_header,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_akhir_header - ($pph_sekarang + $terutang_total),0,true)}}</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">NIP</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">PTKP</th>
                        <th>A</th>
                        <th>B</th>
                        <th rowspan="2">Bruto Tanpa Insentif</th>
                        <th colspan="4">Potongan</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                        <th>G</th>
                        <th>H</th>
                        <th>I</th>
                        <th>J</th>
                    </tr>
                    <tr>
                        <th>Bruto</th>
                        <th>Bruto Akhir Bulan</th>
                        <th>Kredit Pegawai</th>
                        <th>Kredit Koperasi</th>
                        <th>Iuran Koperasi</th>
                        <th>Iuaran IK</th>
                        <th>Pengali</th>
                        <th>Pengali Akhir Bulan</th>
                        <th>PPH Sekarang</th>
                        <th>
                            PPH Seharusnya <br>
                            ( A * C )
                        </th>
                        <th>
                            Selisih <br>
                            ( E - F )
                        </th>
                        <th>Terutang</th>
                        <th>
                            PPH Akhir Bulan <br>
                            ( B * D )
                        </th>
                        <th>
                            Selisih <br>
                            ( I - (E + H) )
                        </th>
                    </tr>
                </thead>
                {{--
                    PPH sekarang = table pph_yang_dilunasi / row->seharusnya->total_seharusnya
                    pph seharusnya = perhitungan / $pph
                    selisih = seharusnya - sekarang
                 --}}
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
                            @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{str_contains($row->nip, 'U') ? '-' : $row->nip}}</td>
                                <td>{{$row->nama}}</td>
                                <td>{{$row->ptkp->kode}}</td>
                                <td>{{formatRupiahExcel($row->penghasilanBruto, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->penghasilanBrutoAkhirBulan, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->penghasilanBrutoAkhirBulan - $row->total_insentif, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->kredit_pegawai ? $row->potongan->kredit_pegawai : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->kredit_koperasi ? $row->potongan->kredit_koperasi : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->iuran_koperasi ? $row->potongan->iuran_koperasi : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->iuran_ik ? $row->potongan->iuran_ik : 0, 0, true)}}</td>
                                <td>{{($row->pengali * 100)}}%</td>
                                <td>{{($row->pengali_akhir * 100)}}%</td>
                                <td>{{formatRupiahExcel($row->seharusnya->total_seharusnya,0,true)}}</td>
                                <td>{{formatRupiahExcel($row->pph,0,true)}}</td>
                                <td>{{formatRupiahExcel($selisih,0,true)}}</td>
                                <td>{{formatRupiahExcel($row->seharusnya->terutang,0,true)}}</td>
                                <td>{{formatRupiahExcel((($row->penghasilanBrutoAkhirBulan - $row->total_insentif) * ($row->pengali_akhir)) - ($row->total_insentif * 0.05) ,0,true)}}</td>
                                <td>{{formatRupiahExcel($selisih_pph_akhir_bulan,0,true)}}</td>
                                <input type="hidden" name="nip[]" value="{{$row->nip}}">
                                @php
                                    $new_terutang = 0;
                                    $old_terutang = 0;
                                    $current_terutang = $row->seharusnya->terutang;
                                    if ($selisih != 0) {
                                        $new_terutang = $selisih;
                                    }
                                @endphp
                                <input type="hidden" name="terutang[]" value="{{$new_terutang}}">
                            </tr>
                        @endforeach
                        <input type="submit" class="btn btn-success" value="Update">
                    </form>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="font-weight: bold;">GRAND TOTAL</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($bruto_akhir_header, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($kredit_pegawai, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($kredit_koprasi, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($iuran_koprasi, 0, true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($iuran_ik, 0, true)}}</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">-</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_sekarang,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_seharusnya,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($selisih_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($terutang_total,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_akhir_header,0,true)}}</th>
                        <th style="font-weight: bold">{{formatRupiahExcel($pph_akhir_header - ($pph_sekarang + $terutang_total),0,true)}}</th>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>

@endSection
