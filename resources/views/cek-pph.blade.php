@extends('layouts.app-template')
@section('content')
<div class="body-pages">
    <form action="" method="GET">
        <div class="table-wrapping text-center space-y-5">
            <div class="input-box">
                <label for="">Cabang</label>
                <select name="kd_entitas" id="cabang" class="form-input">
                    @foreach ($cabang as $item)
                        <option value="{{$item->kd_cabang}}" {{old('kd_entitas', \Request::get('kd_entitas') == $item->kd_cabang ? 'selected' : '')}}>{{$item->nama_cabang}}</option>
                    @endforeach
                </select>
            </div>
            <input type="submit" class="btn btn-primary btn-icon-text no-print" value="Tampilkan">
            @if ($result)
                @php
                    $bruto = 0;
                    $kredit_pegawai = 0;
                    $kredit_koprasi = 0;
                    $iuran_koprasi = 0;
                    $iuran_ik = 0;
                    $pph_sekarang = 0;
                    $pph_seharusnya = 0;
                @endphp
                @foreach ($result as $item)
                    @php
                        $row = $item['pph'];
                        $bruto += $row->penghasilanBruto;
                        $kredit_pegawai += $row->potongan?->kredit_pegawai ? $row->potongan->kredit_pegawai : 0;
                        $kredit_koprasi += $row->potongan?->kredit_koperasi ? $row->potongan->kredit_koperasi : 0;
                        $iuran_koprasi += $row->potongan?->iuran_koperasi ? $row->potongan->iuran_koperasi : 0;
                        $iuran_ik += $row->potongan?->iuran_ik ? $row->potongan->iuran_ik : 0;
                        $pph_sekarang += $row->seharusnya->total_selisih;
                        $pph_seharusnya += $row->pph;
                        $selisih = $pph_sekarang - $pph_seharusnya;
                    @endphp
                @endforeach
                <table class="table table-bordered" id="table" style="width: 100%; border: 1px solid black;">
                    <thead style="margin-bottom: 3rem">
                        <tr>
                            <th colspan="3" style="font-weight: bold;">GRAND TOTAL</th>
                            <th>-</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($bruto, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($kredit_pegawai, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($kredit_koprasi, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($iuran_koprasi, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($iuran_ik, 0, true)}}</th>
                            <th style="font-weight: bold">-</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($pph_sekarang,0,true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($pph_seharusnya,0,true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($selisih,0,true)}}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">NIP</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">PTKP</th>
                            <th rowspan="2">Bruto</th>
                            <th colspan="4">Potongan</th>
                            <th rowspan="2">Pengali</th>
                            <th rowspan="2">PPH Sekarang</th>
                            <th rowspan="2">PPH Seharusnya</th>
                            <th rowspan="2">Selisih</th>
                        </tr>
                        <tr>
                            <th>Kredit Pegawai</th>
                            <th>Kredit Koperasi</th>
                            <th>Iuran Koperasi</th>
                            <th>Iuaran IK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $item)
                            @php
                                $row = $item['pph'];
                            @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{str_contains($row->nip, 'U') ? '-' : $row->nip}}</td>
                                <td>{{$row->nama}}</td>
                                <td>{{$row->ptkp->kode}}</td>
                                <td>{{formatRupiahExcel($row->penghasilanBruto, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->kredit_pegawai ? $row->potongan->kredit_pegawai : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->kredit_koperasi ? $row->potongan->kredit_koperasi : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->iuran_koperasi ? $row->potongan->iuran_koperasi : 0, 0, true)}}</td>
                                <td>{{formatRupiahExcel($row->potongan?->iuran_ik ? $row->potongan->iuran_ik : 0, 0, true)}}</td>
                                <td>{{($row->pengali * 100)}}%</td>
                                <td>{{formatRupiahExcel($row->seharusnya->total_selisih,0,true)}}</td>
                                <td>{{formatRupiahExcel($row->pph,0,true)}}</td>
                                <td>{{formatRupiahExcel($row->seharusnya->total_selisih - $row->pph,0,true)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="font-weight: bold;">GRAND TOTAL</th>
                            <th>-</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($bruto, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($kredit_pegawai, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($kredit_koprasi, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($iuran_koprasi, 0, true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($iuran_ik, 0, true)}}</th>
                            <th style="font-weight: bold">-</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($pph_sekarang,0,true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($pph_seharusnya,0,true)}}</th>
                            <th style="font-weight: bold">{{formatRupiahExcel($selisih,0,true)}}</th>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </form>
</div>

@endSection
