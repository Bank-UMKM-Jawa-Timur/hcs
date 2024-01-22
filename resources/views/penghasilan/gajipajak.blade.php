@extends('layouts.app-template')
@include('vendor.select2')
@section('content')

<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Pajak Penghasilan</h2>
            <div class="breadcrumb">
             <a href="#" class="text-sm text-gray-500">Penghasilan</a>
             <i class="ti ti-circle-filled text-theme-primary"></i>
             <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Pajak Penghsilan</a>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="card">
        @can('penghasilan - pajak penghasilan')
        <form action="{{ route('get-penghasilan') }}" method="post" class="mt-3">
            @csrf
        <div class="grid lg:grid-cols-3 items-center gap-5 md:grid-cols-2 grid-cols-1">
            <div class="input-box">
                <label for="id_label_single">
                   Karyawan
                </label>
                <select class="select-2"  name="nip" id="nip" >
                    @if ($karyawan)
                        <option value="{{$karyawan?->nip}}">{{$karyawan?->nip}} - {{$karyawan?->nama_karyawan}}</option>
                    @else
                        <option selected>-- Pilih Nama Karyawan--</option>
                    @endif
                </select>
            </div>
            <div class="input-box">
                <label for="id_label_single">
                   Mode Lihat Data
                </label>
                <select class="form-input" name="mode" >
                    <option selected>-- Pilih Mode Lihat Data --</option>
                    <option value="1" {{ ($request->mode == 1) ? 'selected' : '' }}>Bukti Pembayaran Gaji Pajak</option>
                    <option value="2" {{ ($request->mode == 2) ? 'selected' : '' }}>Detail Gaji Pajak</option>
                </select>
            </div>
            @php
                $already_selected_value = date('y');
                $earliest_year = 2024;
            @endphp
            <div class="input-box">
                <label for="id_label_single">
                   Tahun
                </label>
                <select class="form-input"  name="tahun">
                    @php
                    $earliest = 2024;
                    $tahunSaatIni = date('Y');
                    $awal = $tahunSaatIni - 5;
                    $akhir = $tahunSaatIni + 5;
                    $tahunInput = $tahun;
                @endphp
                    <option selected>-- Pilih Tahun--</option>
                    @for ($tahunInput = $earliest; $tahunInput <= $akhir; $tahunInput++)
                        <option {{ Request()->tahun == $tahunInput ? 'selected' : '' }} value="{{ $tahunInput }}">
                            {{ $tahunInput }}</option>
                    @endfor
                </select>
            </div>
           <div class="">
            <a href="penghasilan/gajipajak">
                <button class="btn btn-primary" type="submit"><i class="ti ti-filter"></i>Tampilkan</button>
            </a>
           </div>
        </div>
        </form>
        @endcan
    </div>
    
    <div class="card mt-5 space-y-5">
        @php
            $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
            $total_ket = 0;
            $status = 'TK';
            if ($karyawan->status_ptkp) {
                $status = $karyawan->status_ptkp;
            } 
            else {
                if ($karyawan->status == 'K' || $karyawan->status == 'Kawin') {
                    $anak = DB::table('mst_karyawan')
                        ->where('keluarga.nip', $karyawan->nip)
                        ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                        ->whereIn('enum', ['Suami', 'Istri'])
                        ->orderByDesc('keluarga.id')
                        ->first('jml_anak');
                    if ($anak != null && $anak->jml_anak > 3) {
                        $status = 'K/3';
                    } else if ($anak != null) {
                        $status = 'K/'.$anak->jml_anak;
                    } else {
                        $status = 'K/0';
                    }
                }
            }
            function formatNpwp($npwp) {
                $ret = substr($npwp,0,2)."."
                .substr($npwp,2,3)."."
                .substr($npwp,5,3)."."
                .substr($npwp,8,1)."-"
                .substr($npwp,9,3)."."
                .substr($npwp,12,3);
                return $ret;
            }
            $status_pegawai = $karyawan->ket;
            $ptkp = \App\Helpers\HitungPPH::getPTKP($karyawan);

            for ($i=0; $i < 12; $i++) {
                if ((array_sum($gj[$i]) + $jamsostek[$i] + array_sum($penghasilan[$i]) != 0)) {
                    $total_ket += 1;
                }
            }

            function rupiah($angka){
                $hasil_rupiah = number_format($angka, 0, ",", ".");
                return $hasil_rupiah > 0 ? $hasil_rupiah : '-';
            }
        @endphp
        @if ($mode == 1)
            <div class="max-w-lg mx-auto pt-5 pb-5">
                <h3 class="text-center font-bold text-lg">BUKTI PEMBAYARAN GAJI PAJAK {{ $tahun }}</h3>
                <h3 class="text-center font-bold text-lg">{{ $karyawan->nama_karyawan }}</h3>
            </div>
            <div class="w-full flex justify-end">
                <input type="button" class="btn btn-primary" name="print" value="Print" onClick="printReport()">
            </div>
            <div class="h-[0.5px] w-full bg-gray-400"></div>
            <div class="p-5">
                <h3 class="font-bold uppercase">A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG</h3>
                <div class="lg:flex grid grid-cols-1 gap-12 justify-center w-full">
                    <div class="w-full space-y-3 mt-5">
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label for="npwp">NPWP</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"value="{{ ($karyawan->npwp != null) ? formatNpwp($karyawan->npwp) : '-' }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>NIK</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled" value="{{ $karyawan->nik }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>NAMA</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled" value="{{ $karyawan->nama_karyawan }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>ALAMAT</label>
                            </div>
                            <div class="w-full">
                                <textarea class="form-input-disabled" readonly>{{ $karyawan->alamat_ktp }}</textarea>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>JENIS KELAMIN</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled" value="{{ $karyawan->jk }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>JABATAN</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $karyawan->nama_jabatan }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>STATUS PERKAWAINAN</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $status }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>MASA KERJA</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $total_ket }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>KODE OBYEK PAJAK</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="21-100-01" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>KETERANGAN PEGAWAI</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $status_pegawai }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="w-full  space-y-3 mt-5">
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>NO.REKENING GAJI</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $karyawan->no_rekening }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>BPJSTK</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $karyawan->kpj }}" readonly>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="w-full">
                                <label>BPKSKES</label>
                            </div>
                            <div class="w-full">
                                <input type="text" class="form-input-disabled"  value="{{ $karyawan->jkn }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-wrapping border-none mt-10">
                    <table class="tables-even-or-odd ">
                        <thead>
                            <tr>
                                <th rowspan="2">MASA PENGHASILAN</th>
                                <th colspan="2">PENGHASILAN</th>
                                <th rowspan="2">PENGHASILAN BRUTO</th>
                                <th rowspan="2">PAJAK DIBAYAR</th>
                                <th rowspan="2">KET</th>
                            </tr>
                            <tr>
                                <th>RUTIN</th>
                                <th>TIDAK RUTIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_rutin = 0;
                                $total_gaji = 0;
                                $total_tidak_rutin = 0;
                                $total_tj_lainnya = 0;
                                $jaminan = array_sum($jamsostek);
                                $bonus_sum = 0;
                                $total_pph = 0;
                                $total_honorium = 0;
                                $total_pph_21 = 0;
                                $total_penghasilan_bruto = 0;
                                $total_pph_lunas = array_sum($pph);
                            @endphp
                            @for ($i = 0; $i < 12; $i++)
                                <tr>
                                    <td>{{ $bulan[$i] }}</td>
                                    {{--  @if ($i == 7)
                                        @dd($gj[$i], array_sum($gj[$i]),$jamsostek[$i], $jamsostek)
                                    @endif  --}}
                                    <td>{{ (array_sum($gj[$i]) + $jamsostek[$i] > 0) ? rupiah(array_sum($gj[$i]) + $jamsostek[$i]) : '-' }}</td>
                                    <td>{{ (array_sum($penghasilan[$i]) + array_sum($bonus[$i]) > 0) ? rupiah(array_sum($penghasilan[$i]) + array_sum($bonus[$i])) : '-' }}</td>
                                    @php
                                        $bonus_sum += array_sum($bonus[$i]);
                                        $total_rutin += array_sum($gj[$i]) + $jamsostek[$i];
                                        $total_tidak_rutin += (array_sum($penghasilan[$i]) + array_sum($bonus[$i]));
                                        $total_gaji += $gj[$i]['gj_pokok'] + $gj[$i]['tj_keluarga'] + $gj[$i]['tj_jabatan'] +$gj[$i]['gj_penyesuaian'] + $gj[$i]['tj_perumahan'] + $gj[$i]['tj_telepon'] + $gj[$i]['tj_pelaksana'] + $gj[$i]['tj_kemahalan'] + $gj[$i]['tj_kesejahteraan'];
                                        $total_tj_lainnya += $gj[$i]['uang_makan'] + $gj[$i]['tj_pulsa'] + $gj[$i]['tj_vitamin'] + $gj[$i]['tj_transport'] + array_sum($penghasilan[$i]);
                                        $total_penghasilan_bruto += array_sum($gj[$i]) + array_sum($penghasilan[$i]) + array_sum($bonus[$i]) + $jamsostek[$i];
                                    @endphp
                                    <td>{{ (array_sum($gj[$i]) + array_sum($penghasilan[$i]) + array_sum($bonus[$i]) + $jamsostek[$i] > 0) ? rupiah(array_sum($gj[$i]) + array_sum($penghasilan[$i]) + array_sum($bonus[$i]) + $jamsostek[$i]) : '-' }}</td>
                                    <td>{{ ($pph[$i] > 0) ? rupiah($pph[$i]) : '-' }}</td>
                                    <td>{{ (array_sum($gj[$i]) + array_sum($penghasilan[$i]) + array_sum($bonus[$i]) + $jamsostek[$i] > 0) ? 1 : 0 }}</td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1">
                                    Total
                                </td>
                                <td class="bg-theme-primary text-white">{{ ($total_rutin != 0) ? rupiah($total_rutin) : '-' }}</td>
                                <td class="bg-theme-primary text-white">{{ ($total_tidak_rutin != 0) ? rupiah($total_tidak_rutin) : '-' }}</td>
                                <td class="bg-theme-primary text-white">{{ ($total_penghasilan_bruto) ? rupiah($total_penghasilan_bruto) : '-' }}</td>
                                <td class="bg-theme-primary text-white">{{ $total_pph_lunas > 0 ? rupiah($total_pph_lunas) : '-' }}</td>
                                <td class="bg-theme-primary text-white">{{ $total_ket }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            {{--  @include('penghasilan.pajak.rincian')  --}}
        @elseif ($mode == 2)
            <div>
                <div class="tab-wrapper">
                    <button class="btn-tab active-tab" data-tab="penghasilan-teratur">Penghasilan Teratur</button>
                    <button class="btn-tab" data-tab="penghasilan-tidak-teratur">Penghasilan Tidak Teratur</button>
                    <button class="btn-tab" data-tab="bonus">Bonus</button>
                </div>
                <div class="tab-content table-wrapping" id="penghasilan-teratur">
                    <div class="head text-center pb-8 pt-2">
                        <h3 class="font-bold text-2xl uppercase">Penghasilan Teratur</h3>
                    </div>
                    <table class="tables-even-or-odd">
                        <thead>
                            @php
                            $total_gj_pokok = null;
                            $total_tj_keluarga = null;
                            $total_tj_jabatan = null;
                            $total_gj_penyesuaian = null;
                            $total_tj_perumahan = null;
                            $total_tj_telepon = null;
                            $total_tj_pelaksana = null;
                            $total_tj_kemahalan = null;
                            $total_tj_kesejahteraan = null;
                            $total_tj_khusus = null;
                            $total_jamsostek = null;
                            $total_uang_makan = null;
                            $total_tj_pulsa = null;
                            $total_tj_vitamin = null;
                            $total_tj_transport = null;
                        @endphp
                            <tr>
                                <th rowspan="2">Bulan</th>
                                <th rowspan="2">Gaji<br>Pokok</th>
                                <th colspan="9">Tunjangan</th>
                                <th rowspan="2">Total<br>Gaji</th>
                                <th rowspan="2">Penambah<br>Bruto<br>Jamsostek</th>
                                <th rowspan="2">Tunjangan<br>Uang<br>Makan</th>
                                <th rowspan="2">Tunjangan<br>Uang<br>Pulsa</th>
                                <th rowspan="2">Tunjangan<br>Uang<br>Vitamin</th>
                                <th rowspan="2">Tunjangan<br>Uang<br>Transport</th>
                            </tr>
                            <tr style="background-color: #DAE2B6">
                                <th>Keluarga</th>
                                <th>Jabatan</th>
                                <th>penyesuaian</th>
                                <th>Perumahan</th>
                                <th>Listrik & Air</th>
                                <th>Pelaksana</th>
                                <th>Kemahalan</th>
                                <th>Kesejahteraan</th>
                                <th>Khusus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 12; $i++)
                            @php
                                $total_gj_pokok += $gj[$i]['gj_pokok'];
                                $total_tj_keluarga += $gj[$i]['tj_keluarga'];
                                $total_tj_jabatan += $gj[$i]['tj_jabatan'];
                                $total_gj_penyesuaian += $gj[$i]['gj_penyesuaian'];
                                $total_tj_perumahan += $gj[$i]['tj_perumahan'];
                                $total_tj_telepon += $gj[$i]['tj_telepon'];
                                $total_tj_pelaksana += $gj[$i]['tj_pelaksana'];
                                $total_tj_kemahalan += $gj[$i]['tj_kemahalan'];
                                $total_tj_kesejahteraan += $gj[$i]['tj_kesejahteraan'];
                                if ($gj[$i]['tj_multilevel']) {
                                    $total_tj_khusus += $gj[$i]['tj_multilevel'];
                                }
                                if ($gj[$i]['tj_ti']) {
                                    $total_tj_khusus += $gj[$i]['tj_ti'];
                                }
                                if ($gj[$i]['tj_fungsional']) {
                                    $total_tj_khusus += $gj[$i]['tj_fungsional'];
                                }

                                $total_jamsostek += $jamsostek[$i];
                                $total_uang_makan += $gj[$i]['uang_makan'];
                                $total_tj_pulsa += $gj[$i]['tj_pulsa'];
                                $total_tj_vitamin += $gj[$i]['tj_vitamin'];
                                $total_tj_transport += $gj[$i]['tj_transport'];

                                $total_gaji =  $total_gj_pokok + $total_tj_keluarga + $total_tj_jabatan + $total_gj_penyesuaian
                                            + $total_tj_perumahan + $total_tj_telepon + $total_tj_pelaksana + $total_tj_kemahalan
                                            + $total_tj_kesejahteraan;

                                $tj_khusus = 0;
                                if ($gj[$i]['tj_multilevel']) {
                                    $tj_khusus += $gj[$i]['tj_multilevel'];
                                }
                                if ($gj[$i]['tj_ti']) {
                                    $tj_khusus += $gj[$i]['tj_ti'];
                                }
                                if ($gj[$i]['tj_fungsional']) {
                                    $tj_khusus += $gj[$i]['tj_fungsional'];
                                }
                                $total_gaji_bln = ($gj[$i]['gj_pokok']) + ($gj[$i]['tj_keluarga']) + ($gj[$i]['tj_jabatan']) + ($gj[$i]['gj_penyesuaian'])
                                                + ($gj[$i]['tj_perumahan']) + ($gj[$i]['tj_telepon']) + ($gj[$i]['tj_pelaksana']) + ( $gj[$i]['tj_kemahalan'])
                                                + ($gj[$i]['tj_kesejahteraan']) + $tj_khusus;
                            @endphp
                                <tr>
                                    <td>{{ $bulan[$i] }}</td>
                                    <td>{{ ($gj[$i]['gj_pokok'] != 0) ? rupiah($gj[$i]['gj_pokok']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_keluarga'] != 0) ? rupiah($gj[$i]['tj_keluarga']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_jabatan'] != 0) ? rupiah($gj[$i]['tj_jabatan']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['gj_penyesuaian'] != 0) ? rupiah($gj[$i]['gj_penyesuaian']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_perumahan'] != 0) ? rupiah($gj[$i]['tj_perumahan']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_telepon'] != 0) ? rupiah($gj[$i]['tj_telepon']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_pelaksana'] != 0) ? rupiah($gj[$i]['tj_pelaksana']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_kemahalan'] != 0) ? rupiah($gj[$i]['tj_kemahalan']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_kesejahteraan'] != 0) ? rupiah($gj[$i]['tj_kesejahteraan']) : '-' }}</td>
                                    <td>{{ ($tj_khusus != 0) ? rupiah($tj_khusus) : '-' }}</td>
                                    <td>{{ ($total_gaji_bln != 0 ) ? rupiah($total_gaji_bln) : '-' }}</td>
                                    <td>{{ ($jamsostek[$i] != 0) ? rupiah($jamsostek[$i]) : '-' }}</td>
                                    <td>{{ ($gj[$i]['uang_makan'] != 0) ? rupiah($gj[$i]['uang_makan']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_pulsa'] != 0) ? rupiah($gj[$i]['tj_pulsa']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_vitamin'] != 0) ? rupiah($gj[$i]['tj_vitamin']) : '-' }}</td>
                                    <td>{{ ($gj[$i]['tj_transport'] != 0) ? rupiah($gj[$i]['tj_transport']) : '-' }}</td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1">
                                    Total
                                </td>
                                <td class="bg-theme-primary text-white "">{{ rupiah($total_gj_pokok) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_keluarga) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_jabatan) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_gj_penyesuaian) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_perumahan) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_telepon) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_pelaksana) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_kemahalan) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_kesejahteraan) }}</td>
                                <td style="background-color: #FED049; ">{{ rupiah($total_tj_khusus) }}</td>
                                <td style="background-color: #cecece; ">{{ rupiah($total_gaji) }}</td>
                                <td class="bg-theme-primary text-white">{{ rupiah($total_jamsostek) }}</td>
                                <td class="bg-theme-primary text-white">{{ rupiah($total_uang_makan) }}</td>
                                <td class="bg-theme-primary text-white">{{ rupiah($total_tj_pulsa) }}</td>
                                <td class="bg-theme-primary text-white">{{ rupiah($total_tj_vitamin) }}</td>
                                <td class="bg-theme-primary text-white">{{ rupiah($total_tj_transport) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="tab-content table-wrapping hidden" id="penghasilan-tidak-teratur">
                    <div class="head text-center pb-8 pt-2">
                        <h3 class="font-bold text-2xl uppercase">Penghasilan Tidak Teratur</h3>
                    </div>
                    <table class="tables-even-or-odd">
                        <thead>
                            <tr>
                                <th rowspan="2">Bulan</th>
                                <th rowspan="2">T. Uang Lembur</th>
                                <th rowspan="2">Pengganti Biaya Kesehatan</th>
                                <th rowspan="2">Uang Duka</th>
                                <th rowspan="2">SPD</th>
                                <th rowspan="2">SPD Pendidikan </th>
                                <th rowspan="2">SPD Pindah Tugas</th>
                                <th rowspan="2">Pengganti <br>Uang Seragam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_uang_lembur = null;
                            $total_pengganti_kesehatan = null;
                            $total_uang_duka = null;
                            $total_spd = null;
                            $total_spd_pendidikan = null;
                            $total_spd_pindah_tugas = null;
                            $total_pengganti_seragam = null;
                            $total_tambahan = null;
                        @endphp
                        @for ($i = 0; $i < 12; $i++)
                            <tr>
                                <td>{{ $bulan[$i] }}</td>
                                @for ($j = 0; $j < 7; $j++)
                                    <td>{{ ($penghasilan[$i][$j] != 0) ? rupiah($penghasilan[$i][$j]) : '-' }}</td>
                                @endfor
                                @php
                                    $total_uang_lembur += $penghasilan[$i][0];
                                    $total_pengganti_kesehatan += $penghasilan[$i][1];
                                    $total_uang_duka += $penghasilan[$i][2];
                                    $total_spd += $penghasilan[$i][3];
                                    $total_spd_pendidikan += $penghasilan[$i][4];
                                    $total_spd_pindah_tugas += $penghasilan[$i][5];
                                    $total_pengganti_seragam += $penghasilan[$i][6];
                                @endphp
                            </tr>
                        @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" >
                                    Total
                                </td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_uang_lembur) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_pengganti_kesehatan) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_uang_duka) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_spd) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_spd_pendidikan) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_spd_pindah_tugas) }}</td>
                                <td class="bg-theme-primary text-white" colspan="1">{{ rupiah($total_pengganti_seragam) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="tab-content table-wrapping hidden" id="bonus">
                    <div class="head text-center pb-8 pt-2">
                        <h3 class="font-bold text-2xl uppercase">Bonus</h3>
                    </div>
                    <table class="tables-even-or-odd">
                        <thead>
                            <tr>
                                <th rowspan="2">Bulan</th>
                                <th rowspan="2">Tunjangan Hari Raya</th>
                                <th rowspan="2">Jasa Produksi</th>
                                <th rowspan="2">Dana Pendidikan</th>
                                <th rowspan="2">Tambahan <br>Penghasilan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_thr = null;
                                $total_jasa_produksi = null;
                                $total_dana_pendidikan = null;
                                $total_tambahan_penghasilan = null;
                            @endphp
                            @for ($i = 0; $i < 12; $i++)
                                <tr>
                                    <td>{{ $bulan[$i] }}</td>
                                    @for ($j = 0; $j < 4; $j++)
                                        <td>{{ ($bonus[$i][$j] != 0) ? rupiah($bonus[$i][$j]) : '-' }}</td>
                                    @endfor
                                    @php
                                        $total_thr += $bonus[$i][0];
                                        $total_jasa_produksi += $bonus[$i][1];
                                        $total_dana_pendidikan += $bonus[$i][2];
                                        $total_tambahan_penghasilan += $bonus[$i][3];
                                    @endphp
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot >
                            <tr>
                                <td colspan="1">
                                    Total
                                </td>
                                <td class="bg-theme-primary text-white " colspan="1">{{ rupiah($total_thr) }}</td>
                                <td class="bg-theme-primary text-white " colspan="1">{{ rupiah($total_jasa_produksi) }}</td>
                                <td class="bg-theme-primary text-white " colspan="1">{{ rupiah($total_dana_pendidikan) }}</td>
                                <td class="bg-theme-primary text-white " colspan="1">{{ rupiah($total_tambahan_penghasilan) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('extraScript')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>

<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}',
            data: function(params) {
                const is_cabang = "{{auth()->user()->hasRole('cabang')}}"
                const cabang = is_cabang ? "{{auth()->user()->kd_cabang}}" : null
                return {
                    search: params.term || '',
                    page: params.page || 1,
                    cabang: cabang
                }
            },
            cache: true,
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    nipSelect.append(`
        <option value="{{$karyawan?->nip}}">{{$karyawan?->nip}} - {{$karyawan?->nama_karyawan}}</option>
    `).trigger('change');


    // function printReport()
    // {
    //     var prtContent = document.getElementById("reportPrinting");
    //     var mywindow = window.open();

    //     mywindow.document.write(`<html><head><title></title>`);
    //     mywindow.document.write(`<link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />`);
    //     mywindow.document.write(`<link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />`);
    //     mywindow.document.write(`<link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />`);
    //     mywindow.document.write(`<style> .table-responsive {-ms-overflow-style: none; scrollbar-width: none; } .table-responsive::-webkit-scrollbar { overflow-y: hidden; overflow-x: scroll; } </style>`);
    //     mywindow.document.write(prtContent.innerHTML);
    //     mywindow.document.write(`</body></html>`);

    //     setTimeout(function () {
    //         mywindow.print();
    //         //mywindow.close();
    //     }, 1000)
    //     return true;
    // }

</script>
@endpush