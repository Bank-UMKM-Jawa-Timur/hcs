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
                <table class="table table-bordered" id="table" style="width: 100%; border: 1px solid black;">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">NIP</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">PTKP</th>
                            <th rowspan="2">Bruto</th>
                            <th colspan="4">Potongan</th>
                            <th rowspan="2">Pengali</th>
                            <th rowspan="2">PPH</th>
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
                                <td>{{formatRupiahExcel($row->pph,0,true)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </form>
</div>

@endSection
