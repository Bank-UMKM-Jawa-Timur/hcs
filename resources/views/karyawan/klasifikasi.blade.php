@extends('layouts.template')

@php
$request = isset($request) ? $request : null;
function formatRupiah($value){
if($value == null) return '-';
return number_format($value, 0, '.', ',');
}
@endphp

@section('content')
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        width: 90%;
    }

    table.dataTable td {
        font-size: 10px;
    }
</style>

<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title @active('klasifikasi')">Export Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a
                    href="{{ route('karyawan.index') }}">Karyawan</a> > Eksport Karyawan</p>
        </div>
    </div>
</div>

<div class="card-body ml-3 mr-3">
    <form action="{{ route('klasifikasi-data') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Kategori {{ old('kategori') }}</label>
                    <select name="kategori" class="form-control" id="kategori">
                        <option value="-">--- Pilih Kategori ---</option>
                        <option @selected($request?->kategori == 1) value="1">Keseluruhan</option>
                        <option @selected($request?->kategori == 2) value="2">Divisi</option>
                        <option @selected($request?->kategori == 3) value="3">Sub Divisi</option>
                        <option @selected($request?->kategori == 4) value="4">Bagian</option>
                        <option @selected($request?->kategori == 5) value="5">Kantor</option>
                        <option @selected($request?->kategori == 6) value="6">Gaji</option>
                        <option @selected($request?->kategori == 7) value="7">Pendidikan</option>
                        <option @selected($request?->kategori == 8) value="8">Umur</option>
                        <option @selected($request?->kategori == 9) value="9">Jabatan</option>
                        <option @selected($request?->kategori == 10) value="10">Golongan</option>
                        <option @selected($request?->kategori == 11) value="11">Status</option>
                        <option @selected($request?->kategori == 12) value="12">Jenjang Pendidikan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="kantor_col" class="col-md-4">
            </div>

            <div id="cabang_col" class="col-md-4">
            </div>

            <div id="divisi_col" class="col-md-4">
            </div>

            <div id="subDivisi_col" class="col-md-4">
            </div>

            <div id="bagian_col" class="col-md-4">
            </div>

            <div id="jabatan_col" class="col-md-4">
            </div>

            <div id="panggol_col" class="col-md-4">
            </div>

            <div id="status_col" class="col-md-4">
            </div>

            <div id="pendidikan_col" class="col-md-4">
            </div>

            <div class="col-md-12">
                <button class="btn btn-info" type="submit">Tampilkan</button>
            </div>
        </div>
    </form>
</div>

<div class="card ml-3 mr-3 mb-3 mt-3 shadow">
    <div class="col-md-12">
        @if ($status != null)
        @if ($status == 1)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Penyesuaian</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Keluarga</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Telepon</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Perumahan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Pelaksana</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kemahalan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                        <td class="text-right">
                            {{ $item->gj_pokok ? formatRupiah($item->gj_pokok) : 0 }}
                        </td>
                        <td class="text-right">
                            {{ $item->gj_penyesuaian ? formatRupiah($item->gj_penyesuaian) : 0 }}
                        </td>
                        @foreach ($item->all_tunjangan as $tunjangan)
                        <td class="text-right">
                            {{ $tunjangan > 0 ? formatRupiah($tunjangan) : 0 }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            {{ $item->gaji_total ? formatRupiah($item->gaji_total) : 0 }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="15" class="text-center" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">Total Gaji</th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 10px; min-width: 90px;">
                            <span class="total_gaji">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_penyesuaian">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_keluarga">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_teller">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_telepon">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_jabatan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_perumahan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_pelaksana">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kemahalan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kesejahteraan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_total">0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @elseif ($status == 2)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Penyesuaian</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Keluarga</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Telepon</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Perumahan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Pelaksana</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kemahalan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                        <td class="text-right">
                            {{ $item->gj_pokok ? formatRupiah($item->gj_pokok) : 0 }}
                        </td>
                        <td class="text-right">
                            {{ $item->gj_penyesuaian ? formatRupiah($item->gj_penyesuaian) : 0 }}
                        </td>
                        @foreach ($item->all_tunjangan as $tunjangan)
                        <td class="text-right">
                            {{ $tunjangan > 0 ? formatRupiah($tunjangan) : 0 }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            {{ $item->gaji_total ? formatRupiah($item->gaji_total) : 0 }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="15" class="text-center" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">Total Gaji</th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 10px; min-width: 90px;">
                            <span class="total_gaji">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_penyesuaian">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_keluarga">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_teller">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_telepon">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_jabatan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_perumahan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_pelaksana">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kemahalan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kesejahteraan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_total">0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @elseif ($status == 3)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Penyesuaian</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Keluarga</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Telepon</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Perumahan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Pelaksana</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kemahalan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                        <td class="text-right">
                            {{ $item->gj_pokok ? formatRupiah($item->gj_pokok) : 0 }}
                        </td>
                        <td class="text-right">
                            {{ $item->gj_penyesuaian ? formatRupiah($item->gj_penyesuaian) : 0 }}
                        </td>
                        @foreach ($item->all_tunjangan as $tunjangan)
                        <td class="text-right">
                            {{ $tunjangan > 0 ? formatRupiah($tunjangan) : 0 }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            {{ $item->gaji_total ? formatRupiah($item->gaji_total) : 0 }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="15" class="text-center" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">Total Gaji</th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 10px; min-width: 90px;">
                            <span class="total_gaji">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_penyesuaian">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_keluarga">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_teller">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_telepon">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_jabatan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_perumahan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_pelaksana">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kemahalan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kesejahteraan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_total">0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @elseif ($status == 4)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Penyesuaian</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Keluarga</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Telepon</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Perumahan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Pelaksana</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kemahalan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                        <td class="text-right">
                            {{ $item->gj_pokok ? formatRupiah($item->gj_pokok) : 0 }}
                        </td>
                        <td class="text-right">
                            {{ $item->gj_penyesuaian ? formatRupiah($item->gj_penyesuaian) : 0 }}
                        </td>
                        @foreach ($item->all_tunjangan as $tunjangan)
                        <td class="text-right">
                            {{ $tunjangan > 0 ? formatRupiah($tunjangan) : 0 }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            {{ $item->gaji_total ? formatRupiah($item->gaji_total) : 0 }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="13" class="text-center" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">Total Gaji</th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 10px; min-width: 90px;">
                            <span class="total_gaji">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_penyesuaian">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_keluarga">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_teller">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_telepon">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_jabatan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_perumahan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_pelaksana">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kemahalan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kesejahteraan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_total">0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @elseif ($status == 5)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Penyesuaian</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Keluarga</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Telepon</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Perumahan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Pelaksana</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kemahalan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            T.Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            G.Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                        <td class="text-right">
                            {{ $item->gj_pokok ? formatRupiah($item->gj_pokok) : 0 }}
                        </td>
                        <td class="text-right">
                            {{ $item->gj_penyesuaian ? formatRupiah($item->gj_penyesuaian) : 0 }}
                        </td>
                        @foreach ($item->all_tunjangan as $tunjangan)
                        <td class="text-right">
                            {{ $tunjangan > 0 ? formatRupiah($tunjangan) : 0 }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            {{ $item->gaji_total ? formatRupiah($item->gaji_total) : 0 }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="14" class="text-center" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">Total Gaji</th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 10px; min-width: 90px;">
                            <span class="total_gaji">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_penyesuaian">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_keluarga">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_teller">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_telepon">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_jabatan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_perumahan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_pelaksana">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kemahalan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_tunjangan_kesejahteraan">0</span>
                        </th>
                        <th class="text-right" style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            <span class="total_gaji_total">0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @elseif ($status == 6)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Gaji<br> Pokok</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Keluarga
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Listrik &
                            Air</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Teller</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Perumahan
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br>
                            Kesejahteraan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Kemahalan
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Tun<br> Pelaksana
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Gaji<br> Penyesuaian
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px;">Total<br> Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    @php
                    $tKeluarga = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Keluarga'))->first();

                    $tListrik = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Telpon, Air dan
                    Listrik'))->first();

                    $tJabatan = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Jabatan'))->first();

                    $tTeller = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Teller'))->first();

                    $tPerumahan = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Perumahan'))->first();

                    $tKesejahteraan = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan ==
                    'Kesejahteraan'))->first();

                    $tKemahalan = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Kemahalan'))->first();

                    $tPelaksana = ($item->tunjangan->filter(fn($t) => $t->nama_tunjangan == 'Pelaksana'))->first();
                    @endphp
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        @php
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($item->tanggal_pengangkat);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ formatRupiah($item->gj_pokok) }}</td>
                        <td>{{ formatRupiah($tKeluarga?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tListrik?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tJabatan?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tTeller?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tPerumahan?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tKesejahteraan?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tKemahalan?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($tPelaksana?->pivot->nominal) }}</td>
                        <td>{{ formatRupiah($item->gj_penyesuaian) }}</td>
                        @php
                        $totalGaji = (($item->gj_pokok) + ($tKeluarga?->pivot->nominal) + ($tListrik?->pivot->nominal) +
                        ($tJabatan?->pivot->nominal) + ($tTeller?->pivot->nominal) + ($tPerumahan?->pivot->nominal) +
                        ($tKesejahteraan?->pivot->nominal) + ($tKemahalan?->pivot->nominal) +
                        ($tPelaksana?->pivot->nominal) + ($item->gj_penyesuaian));
                        @endphp
                        <td>{{ formatRupiah($totalGaji) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($status == 7)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; ">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($status == 8)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">No
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Range Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            IKJP</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            Tetap</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            Kontrak Perpanjangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @php
                    $IKJP = $item->filter(fn($k)=> $k->status_karyawan == 'IKJP');
                    $Tetap = $item->filter(fn($k)=> $k->status_karyawan == 'Tetap');
                    $KP = $item->filter(fn($k)=> $k->status_karyawan == 'Kontrak Perpanjangan');
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $umur[$loop->index]->u_awal}} - {{ $umur[$loop->index]->u_akhir}}</td>
                        <td>{{ count($IKJP) }}</td>
                        <td>{{ count($Tetap) }}</td>
                        <td>{{ count($KP) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: center; font-size: 11px;">Sub Total</td>
                        <td id="total_IKJP" style="text-align: center; font-size: 11px;">-</td>
                        <td id="total_Tetap" style="text-align: center; font-size: 11px;">-</td>
                        <td id="total_KP" style="text-align: center; font-size: 11px;">-</td>
                    </tr>
                    <tr style="font-weight: bolder">
                        <td colspan="2" style="text-align: center; font-size: 11px;">Total</td>
                        <td id="total" colspan="3" style="text-align: center; font-size: 11px;">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @elseif ($status == 9)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; ">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($status == 10)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; ">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @if ($item->tanggal_penonaktifan === null)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($status == 11)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Nama</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                            Jabatan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                            Kantor</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Lahir</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                            Umur</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Status</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; ">SK<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Tanggal<br> Angkat</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                            Masa<br> Kerja</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                            Pendidikan<br> Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    <tr>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ jabatanLengkap($item) ?? '-' }}</td>
                        @php
                        $nama_cabang = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();
                        @endphp
                        <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                        <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                        @php
                        $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($umur);
                        $umurSkrg = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tgl_lahir != null) ? $umurSkrg : '-' }}</td>
                        @php
                        if ($item->jk == 'Laki-laki') {
                        $jk = 'L';
                        } else {
                        $jk = 'P';
                        }

                        @endphp
                        <td>{{ $jk }}</td>
                        @php
                        if ($item->status == 'Kawin' || $item->status == 'K') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'K';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'K';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TK';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TK';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Tidak Diketahui') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'TD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'TD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai Mati') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CM';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CM';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Cerai') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'CR';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'CR';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Janda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'JD';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'JD';
                        $anak = 0;
                        }
                        } elseif ($item->status == 'Duda') {
                        if ($item->keluarga?->jml_anak != null) {
                        $status = 'DA';
                        $anak = $item->keluarga?->jml_anak;
                        } else {
                        $status = 'DA';
                        $anak = 0;
                        }
                        } else {
                        $status = '-';
                        $anak = '-';
                        }
                        @endphp
                        <td>{{ $status }}/{{ $anak }}</td>
                        <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                        <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                            : '-' }}</td>
                        @php
                        $mulaKerja = Carbon\Carbon::create($item->tanggal_pengangkat);
                        $waktuSekarang = Carbon\Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y.%m');
                        @endphp
                        <td>{{ ($item->tanggal_pengangkat != null) ? $masaKerja : '-' }}</td>
                        <td>{{ $item->pendidikan ?? '-' }}</td>
                        <td>{{ $item->pendidikan_major ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($status == 12)
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="table_export"
                style="width: 100%; word-break: break-all;">
                <thead>
                    <tr>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">No
                        </th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                            Jenjang Pendidikan</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            IKJP</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            Tetap</th>
                        <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 80px;">
                            Kontrak Perpanjangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $item)
                    @php
                    $IKJP = $item->filter(fn($k)=> $k->status_karyawan == 'IKJP');
                    $Tetap = $item->filter(fn($k)=> $k->status_karyawan == 'Tetap');
                    $KP = $item->filter(fn($k)=> $k->status_karyawan == 'Kontrak Perpanjangan');
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pendidikan[$loop->index] }}</td>
                        <td>{{ count($IKJP) }}</td>
                        <td>{{ count($Tetap) }}</td>
                        <td>{{ count($KP) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: center; font-size: 11px;">Sub Total</td>
                        <td id="total_IKJP" style="text-align: center; font-size: 11px;">-</td>
                        <td id="total_Tetap" style="text-align: center; font-size: 11px;">-</td>
                        <td id="total_KP" style="text-align: center; font-size: 11px;">-</td>
                    </tr>
                    <tr style="font-weight: bolder">
                        <td colspan="2" style="text-align: center; font-size: 11px;">Total</td>
                        <td id="total" colspan="3" style="text-align: center; font-size: 11px;">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script>
    var totalGajiSpan = $('.total_gaji');
    var totalGajiPenyesuaianSpan = $('.total_gaji_penyesuaian');
    var totalTKeluargaSpan = $('.total_tunjangan_keluarga');
    var totalTTellerSpan = $('.total_tunjangan_teller');
    var totalTTeleponSpan = $('.total_tunjangan_telepon');
    var totalTJabatanSpan = $('.total_tunjangan_jabatan');
    var totalTPerumahanSpan = $('.total_tunjangan_perumahan');
    var totalTPelaksanaSpan = $('.total_tunjangan_pelaksana');
    var totalTKemahalanSpan = $('.total_tunjangan_kemahalan');
    var totalTKesejahteraanSpan = $('.total_tunjangan_kesejahteraan');
    var totalGajiTotalSpan = $('.total_gaji_total');
    $(document).ready(function() {
        hitungTotalGaji();
        var total_gaji_pokok = 0;
        var total_gaji_penyesuaian = 0;
        var total_tunjangan_keluarga = 0;
        var total_tunjangan_teller = 0;
        var total_tunjangan_telepon = 0;
        var total_tunjangan_jabatan = 0;
        var total_tunjangan_perumahan = 0;
        var total_tunjangan_pelaksana = 0;
        var total_tunjangan_kemahalan = 0;
        var total_tunjangan_kesejahteraan = 0;
        var total_gaji_total = 0;
        var table = $('#table_export').DataTable();

        // Initial sum calculation
        table.rows().every(function() {
            var rowData = this.data();
            // console.log(row);
            var amount = parseFloat(rowData[15].replaceAll(',',''));
            var amountPenyesuaian = parseFloat(rowData[16].replaceAll(',',''));
            var amountTKeluarga = parseFloat(rowData[17].replaceAll(',',''));
            var amountTTeller = parseFloat(rowData[18].replaceAll(',',''));
            var amountTTelepon = parseFloat(rowData[19].replaceAll(',',''));
            var amountTJabatan = parseFloat(rowData[20].replaceAll(',',''));
            var amountTPerumahan = parseFloat(rowData[21].replaceAll(',',''));
            var amountTPelaksana = parseFloat(rowData[22].replaceAll(',',''));
            var amountTKemahalan = parseFloat(rowData[23].replaceAll(',',''));
            var amountTKesejahteraan = parseFloat(rowData[24].replaceAll(',',''));
            var amountGajiTotal= parseFloat(rowData[25].replaceAll(',',''));
            total_gaji_pokok += amount;
            total_gaji_penyesuaian += amountPenyesuaian;
            total_tunjangan_keluarga += amountTKeluarga;
            total_tunjangan_teller += amountTTeller;
            total_tunjangan_telepon += amountTTelepon;
            total_tunjangan_jabatan += amountTJabatan;
            total_tunjangan_perumahan += amountTPerumahan;
            total_tunjangan_pelaksana += amountTPelaksana;
            total_tunjangan_kemahalan += amountTKemahalan;
            total_tunjangan_kesejahteraan += amountTKesejahteraan;
            total_gaji_total += amountGajiTotal;
        });

        // Update sum on pagination click
        $('#table_export').on('page.dt', function() {
            total_gaji_pokok = 0;
            total_gaji_penyesuaian = 0;
            total_tunjangan_keluarga = 0;
            total_tunjangan_teller = 0;
            total_tunjangan_telepon = 0;
            total_tunjangan_jabatan = 0;
            total_tunjangan_perumahan = 0;
            total_tunjangan_pelaksana = 0;
            total_tunjangan_kemahalan = 0;
            total_tunjangan_kesejahteraan = 0;
            total_gaji_total = 0;
            table.rows({ page: 'current' }).every(function() {
                var rowData = this.data();
                var amount = parseFloat(rowData[15].replaceAll(',',''));
                var amountPenyesuaian = parseFloat(rowData[16].replaceAll(',',''));
                var amountTKeluarga = parseFloat(rowData[17].replaceAll(',',''));
                var amountTTeller = parseFloat(rowData[18].replaceAll(',',''));
                var amountTTelepon = parseFloat(rowData[19].replaceAll(',',''));
                var amountTJabatan = parseFloat(rowData[20].replaceAll(',',''));
                var amountTPerumahan = parseFloat(rowData[21].replaceAll(',',''));
                var amountTPelaksana = parseFloat(rowData[22].replaceAll(',',''));
                var amountTKemahalan = parseFloat(rowData[23].replaceAll(',',''));
                var amountTKesejahteraan = parseFloat(rowData[24].replaceAll(',',''));
                var amountGajiTotal = parseFloat(rowData[25].replaceAll(',',''));
                total_gaji_pokok += amount;
                total_gaji_penyesuaian += amountPenyesuaian;
                total_tunjangan_keluarga += amountTKeluarga;
                total_tunjangan_teller += amountTTeller;
                total_tunjangan_telepon += amountTTelepon;
                total_tunjangan_jabatan += amountTJabatan;
                total_tunjangan_perumahan += amountTPerumahan;
                total_tunjangan_pelaksana += amountTPelaksana;
                total_tunjangan_kemahalan += amountTKemahalan;
                total_tunjangan_kesejahteraan += amountTKesejahteraan;
                total_gaji_total += amountGajiTotal;
            });

            totalGajiSpan.html(formatRupiahKoma(total_gaji_pokok.toString(),0))
            totalGajiPenyesuaianSpan.html(formatRupiahKoma(total_gaji_penyesuaian.toString(),0))
            totalTKeluargaSpan.html(formatRupiahKoma(total_tunjangan_keluarga.toString(),0))
            totalTTellerSpan.html(formatRupiahKoma(total_tunjangan_teller.toString(),0))
            totalTTeleponSpan.html(formatRupiahKoma(total_tunjangan_telepon.toString(),0))
            totalTJabatanSpan.html(formatRupiahKoma(total_tunjangan_jabatan.toString(),0))
            totalTPerumahanSpan.html(formatRupiahKoma(total_tunjangan_perumahan.toString(),0))
            totalTPelaksanaSpan.html(formatRupiahKoma(total_tunjangan_pelaksana.toString(),0))
            totalTKemahalanSpan.html(formatRupiahKoma(total_tunjangan_kemahalan.toString(),0))
            totalTKesejahteraanSpan.html(formatRupiahKoma(total_tunjangan_kesejahteraan.toString(),0))
            totalGajiTotalSpan.html(formatRupiahKoma(total_gaji_total.toString(),0))
        });
    })
    function hitungTotalGaji() {
        var totalGajiPokok = 0;
        var tGaji = $('#table_export').DataTable().column(15, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalGajiPokok += parseInt(gaji);
        }
        totalGajiSpan.html(formatRupiahKoma(totalGajiPokok.toString(),0))

        var totalGajiPenyesuaian = 0;
        var tGaji = $('#table_export').DataTable().column(16, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalGajiPenyesuaian += parseInt(gaji);
        }
        totalGajiPenyesuaianSpan.html(formatRupiahKoma(totalGajiPenyesuaian.toString(),0))

        var totalTKeluarga = 0;
        var tGaji = $('#table_export').DataTable().column(17, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTKeluarga += parseInt(gaji);
        }
        totalTKeluargaSpan.html(formatRupiahKoma(totalTKeluarga.toString(),0))

        var totalTTeller = 0;
        var tGaji = $('#table_export').DataTable().column(18, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTTeller += parseInt(gaji);
        }
        totalTTellerSpan.html(formatRupiahKoma(totalTTeller.toString(),0))

        var totalTTelepon = 0;
        var tGaji = $('#table_export').DataTable().column(19, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTTelepon += parseInt(gaji);
        }
        totalTTeleponSpan.html(formatRupiahKoma(totalTTelepon.toString(),0))

        var totalTJabatan = 0;
        var tGaji = $('#table_export').DataTable().column(20, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTJabatan += parseInt(gaji);
        }
        totalTJabatanSpan.html(formatRupiahKoma(totalTJabatan.toString(),0))

        var totalTPerumahan = 0;
        var tGaji = $('#table_export').DataTable().column(21, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTPerumahan += parseInt(gaji);
        }
        totalTPerumahanSpan.html(formatRupiahKoma(totalTPerumahan.toString(),0))

        var totalTPelaksana = 0;
        var tGaji = $('#table_export').DataTable().column(22, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTPelaksana += parseInt(gaji);
        }
        totalTPelaksanaSpan.html(formatRupiahKoma(totalTPelaksana.toString(),0))

        var totalTKemahalan = 0;
        var tGaji = $('#table_export').DataTable().column(23, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTKemahalan += parseInt(gaji);
        }
        totalTKemahalanSpan.html(formatRupiahKoma(totalTKemahalan.toString(),0))

        var totalTKesejahteraan = 0;
        var tGaji = $('#table_export').DataTable().column(24, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalTKesejahteraan += parseInt(gaji);
        }
        totalTKesejahteraanSpan.html(formatRupiahKoma(totalTKesejahteraan.toString(),0))

        var totalGajiTotal = 0;
        var tGaji = $('#table_export').DataTable().column(25, { page: 'current'} ).data()
        for (let i = 0; i < tGaji.length; i++) {
            const gaji = tGaji[i].replaceAll(',','');
            totalGajiTotal += parseInt(gaji);
        }
        totalGajiTotalSpan.html(formatRupiahKoma(totalGajiTotal.toString(),0))
    }
    // Pengambilan Kategori
    var k = document.getElementById("kategori");
    var category = k.options[k.selectedIndex].text;

    $("#table_export").DataTable({
            dom : "Bfrtip",
            pageLength: 25,
            ordering: false,
            scrollX: true,
            drawCallback: function () {
                var ikjp = $('#table_export').DataTable().column(2).data().sum();
                var tetap = $('#table_export').DataTable().column(3).data().sum();
                var kp = $('#table_export').DataTable().column(4).data().sum();
                $('#total_IKJP').html(ikjp);
                $('#total_Tetap').html(tetap);
                $('#total_KP').html(kp);

                $('#total').html((kp + tetap + ikjp));
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    title : function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValuePendidikan = $('#pendidikan').find('option:selected').text();
                        var selectedValueJabatan = $('#jabatan').find('option:selected').text();
                        var selectedValuePanggol = $('#panggol').find('option:selected').text();
                        var selectedValueStatus = $('#status').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan" || selectedValueCategory === "Umur" || selectedValueCategory === "Jenjang Pendidikan") {
                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category;
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor" || selectedValueCategory === "Gaji"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueCabang+'';
                                    }
                                }

                            }else if(selectedValueCategory === "Pendidikan"){
                                // Kategori Pendidikan 
                                if (selectedValuePendidikan === null || selectedValuePendidikan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePendidikan+'';
                                }
                            }else if(selectedValueCategory === "Jabatan"){
                                // // Kategori jabatan 
                                if (selectedValueJabatan === null || selectedValueJabatan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueJabatan+'';
                                }
                            }else if(selectedValueCategory === "Golongan"){
                                // // Kategori Golongan 
                                if (selectedValuePanggol === null || selectedValuePanggol === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePanggol+'';
                                }
                            }else{
                                // // Kategori Status 
                                if (selectedValueStatus === null || selectedValueStatus === undefined ) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueStatus+'';
                                }
                            }
                        }
                    },
                    filename : function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValuePendidikan = $('#pendidikan').find('option:selected').text();
                        var selectedValueJabatan = $('#jabatan').find('option:selected').text();
                        var selectedValuePanggol = $('#panggol').find('option:selected').text();
                        var selectedValueStatus = $('#status').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan" || selectedValueCategory === "Umur" || selectedValueCategory === "Jenjang Pendidikan") {
                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category;
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor" || selectedValueCategory === "Gaji"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueCabang+'';
                                    }
                                }

                            }else if(selectedValueCategory === "Pendidikan"){
                                // Kategori Pendidikan 
                                if (selectedValuePendidikan === null || selectedValuePendidikan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePendidikan+'';
                                }
                            }else if(selectedValueCategory === "Jabatan"){
                                // // Kategori jabatan 
                                if (selectedValueJabatan === null || selectedValueJabatan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueJabatan+'';
                                }
                            }else if(selectedValueCategory === "Golongan"){
                                // // Kategori Golongan 
                                if (selectedValuePanggol === null || selectedValuePanggol === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePanggol+'';
                                }
                            }else{
                                // // Kategori Status 
                                if (selectedValueStatus === null || selectedValueStatus === undefined ) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueStatus+'';
                                }
                            }
                        }
                    },
                    message: 'Klasifikasi Data Karyawan\n ',
                    text:'Excel',
                    header: true,
                    footer: true,
                    exportOptions: {
                        orthogonal: 'sort'
                    },
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r^="C"]', sheet).attr( 's', '50' );
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'BANK UMKM Jawa Timur\n Klasifikasi Data Karyawan ',
                    filename : function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValuePendidikan = $('#pendidikan').find('option:selected').text();
                        var selectedValueJabatan = $('#jabatan').find('option:selected').text();
                        var selectedValuePanggol = $('#panggol').find('option:selected').text();
                        var selectedValueStatus = $('#status').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan" || selectedValueCategory === "Umur" || selectedValueCategory === "Jenjang Pendidikan") {
                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category;
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }

                                }
                            }else if(selectedValueCategory === "Kantor" || selectedValueCategory === "Gaji"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueCabang+'';
                                    }

                                }

                            }else if(selectedValueCategory === "Pendidikan"){
                                // Kategori Pendidikan 
                                if (selectedValuePendidikan === null || selectedValuePendidikan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePendidikan+'';
                                }
                            }else if(selectedValueCategory === "Jabatan"){
                                // // Kategori jabatan 
                                if (selectedValueJabatan === null || selectedValueJabatan === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueJabatan+'';
                                }
                            }else if(selectedValueCategory === "Golongan"){
                                // // Kategori Golongan 
                                if (selectedValuePanggol === null || selectedValuePanggol === undefined) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePanggol+'';
                                }
                            }else{
                                // // Kategori Status 
                                if (selectedValueStatus === null || selectedValueStatus === undefined ) {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    return 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueStatus+'';
                                }
                            }
                        }
                    },
                    text:'PDF',
                    footer: true,
                    paperSize: 'A4',
                    orientation: 'landscape',
                    pageSize : 'A2',
                    customize: function (doc) {
                        var now = new Date();
                        var jsDate = now.getDate()+' / '+(now.getMonth()+1)+' / '+now.getFullYear();

                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValuePendidikan = $('#pendidikan').find('option:selected').text();
                        var selectedValueJabatan = $('#jabatan').find('option:selected').text();
                        var selectedValuePanggol = $('#panggol').find('option:selected').text();
                        var selectedValueStatus = $('#status').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan" || selectedValueCategory === "Umur" || selectedValueCategory === "Jenjang Pendidikan") {
                            doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor" || selectedValueCategory === "Gaji"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueCabang+'';
                                    }
                                }

                            }else if(selectedValueCategory === "Pendidikan"){
                                // Kategori Pendidikan 
                                if (selectedValuePendidikan === null || selectedValuePendidikan === undefined) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePendidikan+'';
                                }
                            }else if(selectedValueCategory === "Jabatan"){
                                // // Kategori jabatan 
                                if (selectedValueJabatan === null || selectedValueJabatan === undefined) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueJabatan+'';
                                }
                            }else if(selectedValueCategory === "Golongan"){
                                // // Kategori Golongan 
                                if (selectedValuePanggol === null || selectedValuePanggol === undefined) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePanggol+'';
                                }
                            }else{
                                // // Kategori Status 
                                if (selectedValueStatus === null || selectedValueStatus === undefined ) {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    doc.content[0].text = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueStatus+'';
                                }
                            }
                        }

                        doc.styles.tableHeader.fontSize = 10;
                        doc.defaultStyle.fontSize = 9;
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';

                        doc.content[1].margin = [0, 0, 0, 0];
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                        doc['footer']=(function(page, pages) {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: ['Created on: ', { text: jsDate.toString() }]
                                    },
                                    {
                                        alignment: 'right',
                                        text: ['Page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
                                    }
                                ],
                                margin: 20
                            }
                        });

                    }
                },
                {
                    extend: 'print',
                    title: '',
                    text:'print',
                    footer: true,
                    paperSize: 'A4',
                    customize: function (win) {
                        var last = null;
                        var current = null;
                        var bod = [];

                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValuePendidikan = $('#pendidikan').find('option:selected').text();
                        var selectedValueJabatan = $('#jabatan').find('option:selected').text();
                        var selectedValuePanggol = $('#panggol').find('option:selected').text();
                        var selectedValueStatus = $('#status').find('option:selected').text();

                        var header = document.createElement('h1');
                        if (selectedValueCategory === "Keseluruhan" || selectedValueCategory === "Umur" || selectedValueCategory === "Jenjang Pendidikan") {
                            header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori divisi '+selectedValueDivisi+'_'+selectedValueSubDivisi+'';
                                        }else{
                                            header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori divisi '+selectedValueDivisi+'_'+selectedValueSubDivisi+', bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor" || selectedValueCategory === "Gaji"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueCabang+'';
                                    }
                                }

                            }else if(selectedValueCategory === "Pendidikan"){
                                // Kategori Pendidikan 
                                if (selectedValuePendidikan === null || selectedValuePendidikan === undefined) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePendidikan+'';
                                }
                            }else if(selectedValueCategory === "Jabatan"){
                                // // Kategori jabatan 
                                if (selectedValueJabatan === null || selectedValueJabatan === undefined) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueJabatan+'';
                                }
                            }else if(selectedValueCategory === "Golongan"){
                                // // Kategori Golongan 
                                if (selectedValuePanggol === null || selectedValuePanggol === undefined) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValuePanggol+'';
                                }
                            }else{
                                // // Kategori Status 
                                if (selectedValueStatus === null || selectedValueStatus === undefined ) {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori_' + category + '';
                                } else {
                                    header.textContent = 'BANK UMKM Jawa Timur Klasifikasi Data Karyawan kategori ' + category + '_'+selectedValueStatus+'';
                                }
                            }
                        }
                        win.document.body.insertBefore(header, win.document.body.firstChild);

                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');

                        style.type = 'text/css';
                        style.media = 'print';

                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(win.document.createTextNode(css));
                        }

                        head.appendChild(style);

                        $(win.document.body).find('h1')
                            .css('text-align', 'center')
                            .css( 'font-size', '16pt' )
                            .css('margin-top', '20px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10pt')
                            .css('width', '1000px')
                            .css('border', '#bbbbbb solid 1px');
                        $(win.document.body).find('tr:nth-child(odd) th').each(function(index){
                            $(this).css('text-align','center');
                        });
                    }
                }
            ], 
            columnDefs: [{
                targets:[1],
                render: function(data, type, row, meta){
                    if(type === 'sort'){
                        //data = ' ' + data ;
                        return "\u200C" + data ; 
                    }
                    
                    return data ;   
                    
                }
            }]
        });

        $(".buttons-excel").attr("class","btn btn-success mb-2");
        $(".buttons-pdf").attr("class","btn btn-success mb-2");
        $(".buttons-print").attr("class","btn btn-success mb-2");

        $('#kategori').change(function(e) {
            const value = $(this).val();
            $('#kantor_col').empty();
            $('#cabang_col').empty();
            $('#divisi_col').empty();
            $('#subDivisi_col').empty();
            $('#bagian_col').empty();
            $('#jabatan_col').empty();
            $('#panggol_col').empty();
            $('#status_col').empty();
            $('#pendidikan_col').empty();

            if (value == 2) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 3) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 4) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').removeAttr("disabled", "disabled");
                $("#bagian_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 5) {
                generateOffice();

                $('#kantor').removeAttr("disabled", "disabled");
                $("#kantor_col").show();

                $('#cabang').removeAttr("disabled", "disabled");
                $("#cabang_col").show();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 6) {
                generateOfficeGaji();

                $('#kantor').removeAttr("disabled", "disabled");
                $("#kantor_col").show();

                $('#cabang').removeAttr("disabled", "disabled");
                $("#cabang_col").show();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 7) {
                generatePendidikan();

                $('#pendidikan').removeAttr("disabled", "disabled");
                $("#pendidikan_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();
            } else if (value == 9) {
                generateJabatan();

                $('#jabatan').removeAttr("disabled", "disabled");
                $("#jabatan_col").show();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 10) {
                generatePanggol();

                $('#panggol').removeAttr("disabled", "disabled");
                $("#panggol_col").show();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 11) {
                generateStatus();

                $('#status').removeAttr("disabled", "disabled");
                $("#status_col").show();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else {
                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            }
        });

        function generateDivision() {
            const division = '{{ $request?->divisi }}';
            $.ajax({
                type: 'GET',
                url: "{{ route('get_divisi') }}",
                dataType: 'JSON',
                success: (res) => {
                    $('#divisi_col').empty();
                    $('#divisi_col').append(`
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="divisi" class="form-control">
                                <option value="">--- Pilih Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $.each(res, (i, item) => {
                        const kd_divisi = item.kd_divisi;
                        $('#divisi').append(`<option ${division == kd_divisi ? 'selected' : ''} value="${kd_divisi}">${item.kd_divisi} - ${item.nama_divisi}</option>`);

                        if ($('#divisi').val() == kd_divisi) {
                            dataDivisi = item.nama_divisi;
                        }
                    });

                    
                    $('#subDivisi_col').empty();
                    $('#subDivisi_col').append(`
                    <div class="form-group">
                        <label for="subDivisi">Sub Divisi</label>
                        <select name="subDivisi" id="subDivisi" class="form-control">
                            <option value="">--- Pilih Sub Divisi ---</option>
                            </select>
                            </div>
                            `);
                            
                    $('#divisi').change(function(e) {
                        var divisi = $(this).val();

                        if (divisi) {
                            const subDivision = '{{ $request?->subDivisi }}';

                            $.ajax({
                                type: 'GET',
                                url: "{{ route('get_subdivisi') }}?divisiID="+divisi,
                                dataType: 'JSON',
                                success: (res) => {
                                    $('#subDivisi').empty();
                                    $('#subDivisi').append('<option value="">--- Pilih Sub Divisi ---</option>');

                                    $.each(res, (i, item) => {
                                        const kd_subDivisi = item.kd_subdiv;
                                        $('#subDivisi').append(`<option ${subDivision == kd_subDivisi ? 'selected' : ''} value="${kd_subDivisi}">${item.kd_subdiv} - ${item.nama_subdivisi}</option>`);
                                        getdivisi = item.kd_subdiv + ' - ' + item.nama_subdivisi;
                                    });

                                    $('#bagian_col').empty();
                                    $('#bagian_col').append(`
                                        <div class="form-group">
                                            <label for="bagian">Bagian</label>
                                            <select name="bagian" id="bagian" class="form-control">
                                                <option value="">--- Pilih Bagian ---</option>
                                            </select>
                                        </div>
                                    `);

                                    $("#subDivisi").change(function(){
                                        const bagian = '{{ $request?->bagian}}';
                                        $.ajax({
                                            type: "GET",
                                            url: "{{ route('getBagian') }}?kd_entitas="+$(this).val(),
                                            datatype: "JSON",
                                            success: function(res){
                                                $('#bagian').empty();
                                                $('#bagian').append('<option value="">--- Pilih Bagian ---</option>');

                                                $.each(res, (i, item) => {
                                                    const kd_bagian = item.kd_bagian;
                                                    $('#bagian').append(`<option ${bagian == kd_bagian ? 'selected' : ''} value="${kd_bagian}">${item.kd_bagian} - ${item.nama_bagian}</option>`);
                                                });
                                            }
                                        })
                                    });
                                    $('#subDivisi').trigger('change');
                                }
                            });
                        }
                    });

                    $('#divisi').trigger('change');
                }

            });
        }

        function generateOffice() {
            const office = '{{ $request?->kantor }}';
            $('#kantor_col').append(`
                <div class="form-group">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-control" id="kantor">
                        <option value="-">--- Pilih Kantor ---</option>
                        <option ${ office == "Pusat" ? 'selected' : '' } value="Pusat">Pusat</option>
                        <option ${ office == "Cabang" ? 'selected' : '' } value="Cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function(e) {
                $('#cabang_col').empty();
                if($(this).val() != "Cabang") return;
                generateSubOffice();
            });

            function generateSubOffice() {
                $('#cabang_col').empty();
                const subOffice = '{{ $request?->cabang }}';

                $.ajax({
                    type: 'GET',
                    url: "{{ route('get_cabang') }}",
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
                                    <option value="">--- Pilih Cabang ---</option>
                                </select>
                            </div>
                        `);

                        $.each(res[0], (i, item) => {
                            const kd_cabang = item.kd_cabang;
                            $('#cabang').append(`<option ${subOffice == kd_cabang ? 'selected' : ''} value="${kd_cabang}">${item.kd_cabang} - ${item.nama_cabang}</option>`);
                        });
                    }
                });
            }
        }

        function generateOfficeGaji() {
            const office = '{{ $request?->kantor }}';
            $('#kantor_col').append(`
                <div class="form-group">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-control" id="kantor">
                        <option value="-">--- Pilih Kantor ---</option>
                        <option ${ office == "Keseluruhan" ? 'selected' : '' } value="Keseluruhan">Keseluruhan</option>
                        <option ${ office == "Pusat" ? 'selected' : '' } value="Pusat">Pusat</option>
                        <option ${ office == "Cabang" ? 'selected' : '' } value="Cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function(e) {
                $('#cabang_col').empty();
                if($(this).val() != "Cabang") return;
                generateSubOffice();
            });

            function generateSubOffice() {
                $('#cabang_col').empty();
                const subOffice = '{{ $request?->cabang }}';

                $.ajax({
                    type: 'GET',
                    url: "{{ route('get_cabang') }}",
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
                                    <option value="">--- Pilih Cabang ---</option>
                                </select>
                            </div>
                        `);

                        $.each(res[0], (i, item) => {
                            const kd_cabang = item.kd_cabang;
                            $('#cabang').append(`<option ${subOffice == kd_cabang ? 'selected' : ''} value="${kd_cabang}">${item.kd_cabang} - ${item.nama_cabang}</option>`);
                        });
                    }
                });
            }
        }

        function generateJabatan() {
            const jabatan = '{{ $request?->jabatan }}';
            $('#jabatan_col').append(`
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control">
                        <option value="-">--- Pilih Jabatan ---</option>
                        @foreach ($jabatan as $item)
                            <option ${ jabatan == "{{ $item->kd_jabatan }}" ? 'selected' : '' } value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generatePanggol() {
            const panggol= '{{ $request?->panggol }}';
            $('#panggol_col').append(`
                <div class="form-group">
                    <label for="panggol">Jabatan</label>
                    <select name="panggol" id="panggol" class="form-control">
                        <option value="-">--- Pilih Jabatan ---</option>
                        @foreach ($panggol as $item)
                            <option ${ panggol == "{{ $item->golongan }}" ? 'selected' : '' } value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generateStatus() {
            const status= '{{ $request?->status }}';
            $('#status_col').append(`
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="-">--- Pilih Status ---</option>
                        @foreach (\App\Enum\StatusKaryawan::cases() as $item)
                            <option ${ status == "{{ $item }}" ? 'selected' : '' } value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generatePendidikan() {
            const pendidikan= '{{ $request?->pendidikan }}';
            $('#pendidikan_col').append(`
                <div class="form-group">
                    <label for="pendidikan">Pendidikan</label>
                    <select name="pendidikan" id="pendidikan" class="form-control">
                        <option value="-">--- Pilih Pendidikan ---</option>
                        @foreach (\App\Enum\PendidikanKaryawan::cases() as $item)
                            <option ${ pendidikan == "{{ $item }}" ? 'selected' : '' } value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        $('#kategori').trigger('change');
        $('#kantor').trigger('change');
</script>
@endsection