@extends('layouts.template')
@include('vendor.select2')
@section('content')
<style>
    .dataTables_wrapper .dataTables_filter{
        float: right;
    }
    .dataTables_wrapper .dataTables_length{
        float: left;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        width: 90%;
    }

    .table-responsive {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .table-responsive::-webkit-scrollbar {
        overflow-y: hidden;
        overflow-x: scroll;
    }

    thead, tbody, tr, td {
        text-align: center;
    }

    .sticky-col {
        position: sticky;
        width: 100px;
        left: 0;
        z-index: 10;
        background-color: white
    }
</style>

<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title font-weight-bold">Pajak Penghasilan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('pajak_penghasilan.index') }}">Pajak Penghasilan </a></p>
        </div>
    </div>
</div>

<div class="card-body">
    <form action="{{ route('get-penghasilan') }}" method="post">
        @csrf
            <div class="row m-0">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Karyawan:</label>
                        <select name="nip" id="nip" class="form-control"></select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <label for="mode">Mode Lihat Data</label>
                    <div class="form-group">
                        <select name="mode" class="form-control">
                            <option value="">--- Pilih Mode ---</option>
                            <option value="1" {{ ($request->mode == 1) ? 'selected' : '' }}>Bukti Pembayaran Gaji Pajak</option>
                            <option value="2" {{ ($request->mode == 2) ? 'selected' : '' }}>Detail Gaji Pajak</option>
                        </select>
                    </div>
                </div>
                @php
                $already_selected_value = date('y');
                $earliest_year = 2022;
                @endphp
                <div class="col-md-4">
                    <label for="tahun">Tahun:</label>
                    <div class="form-group">
                        <select name="tahun" class="form-control">
                            <option value="">--- Pilih Tahun ---</option>
                            @foreach (range(date('Y'), $earliest_year) as $x)
                                <option value="{{ $x }}" @selected($request->tahun == $x)>{{ $x }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="penghasilan/gajipajak">
                    <button class="is-btn is-primary" type="submit">Tampilkan</button>
                </a>
            </div>
    </form>
    <div class="card ml-3 mr-3 mb-3 mt-4 shadow" id="reportPrinting">
        <div class="col-md-12">
            @php
                $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                $total_ket = 0;
                $status = 'TK';
                if ($karyawan->status == 'K' || $karyawan->status == 'Kawin') {
                    $anak = DB::table('mst_karyawan')
                        ->where('keluarga.nip', $karyawan->nip)
                        ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                        ->whereIn('enum', ['Suami', 'Istri'])
                        ->first('jml_anak');
                    if ($anak != null && $anak->jml_anak > 3) {
                        $status = 'K/3';
                    } else if ($anak != null) {
                        $status = 'K/'.$anak->jml_anak;
                    } else {
                        $status = 'K/0';
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
                $ptkp = DB::table('set_ptkp')
                    ->where('kode', $status)
                    ->first();

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
            <div class="card-body ml-0 mr-0 mt-0 mb-2">
                <div class="row m-0 mt-1">
                    <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold">BUKTI PEMBAYARAN GAJI PAJAK {{ $tahun }}</p>
                    <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold; margin-top: -15px">{{ $karyawan->nama_karyawan }}</p>
                    <div class="col-sm-12 text-right">
                        <input type="button" class="btn btn-success" style="margin-top: -5px" value="Print" onClick="printReport()">
                    </div>
                </div>
                <hr style="margin-top: 5px">
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NPWP</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ ($karyawan->npwp != null) ? formatNpwp($karyawan->npwp) : '-' }}">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">NO. REKENING GAJI :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->no_rekening }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NIK</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nik }}">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">BPJSTK :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->kpj }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NAMA</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nama_karyawan }}">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">BPKSKES :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->jkn }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">ALAMAT</label>
                    <div class="col-sm-5">
                        <textarea type="textarea" disabled class="form-control pl-2">{{ $karyawan->alamat_ktp }}</textarea>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">JENIS KELAMIN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->jk }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">JABATAN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nama_jabatan }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">STATUS PERKAWINAN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $status }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">MASA KERJA</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="{{ $total_ket }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">KODE OBYEK PAJAK</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="21-100-01">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">KETERANGAN PEGAWAI</label>
                    <div class="col-sm-5 ">
                        <input type="text" disabled class="form-control" value="{{ $status_pegawai }}">
                    </div>
                </div>

                <div class="table-responsive mt-5" style="align-content: center">
                    <table class="table text-center cell-border table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="background-color: #CCD6A6;">MASA PENGHASILAN</th>
                                <th colspan="2" style="background-color: #CCD6A6;">PENGHASILAN</th>
                                <th rowspan="2" style="background-color: #CCD6A6;">PENGHASILAN BRUTO</th>
                                <th rowspan="2" style="background-color: #CCD6A6;">PAJAK DIBAYAR</th>
                                <th rowspan="2" style="background-color: #CCD6A6;">KET</th>
                            </tr>
                            <tr style="background-color: #DAE2B6">
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
                        <tfoot style="font-weight: bold">
                            <tr>
                                <td colspan="1">
                                    Total
                                </td>
                                <td style="background-color: #54B435; ">{{ ($total_rutin != 0) ? rupiah($total_rutin) : '-' }}</td>
                                <td style="background-color: #54B435; ">{{ ($total_tidak_rutin != 0) ? rupiah($total_tidak_rutin) : '-' }}</td>
                                <td style="background-color: #54B435; ">{{ ($total_penghasilan_bruto) ? rupiah($total_penghasilan_bruto) : '-' }}</td>
                                <td style="background-color: #54B435; ">{{ $total_pph_lunas > 0 ? rupiah($total_pph_lunas) : '-' }}</td>
                                <td style="background-color: #54B435; ">{{ $total_ket }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @php
                    $lima_persen = ceil(0.05 * $total_penghasilan_bruto);
                    $keterangan = 500000 * $total_ket;
                    $biaya_jabatan = 0;
                    $no_14 = 0;
                    if($lima_persen > $keterangan){
                        $biaya_jabatan = $keterangan;
                    } else {
                        $biaya_jabatan = $lima_persen;
                    }

                    if ($total_ket == 0) {
                        $no_14 = 0;
                    } else {
                        $rumus_14 = 0;
                        if (0.05 * ($total_gaji + $total_tj_lainnya + $jaminan + $bonus_sum) > $keterangan) {
                            $rumus_14 = ceil($keterangan);
                        } else{
                            $rumus_14 = ceil(0.05 * ($total_gaji + $total_tj_lainnya + $jaminan + $bonus_sum));
                        }
                        $no_14 = (($total_rutin + $total_tidak_rutin) - $bonus_sum - $pengurang - $biaya_jabatan) / $total_ket * 12 + $bonus_sum + ($biaya_jabatan - $rumus_14);
                    }

                    $no_16 = 0;
                    if ($status == 'Mutasi Keluar') {
                        $no_16 = floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000;
                    } else {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 0) {
                            $no_16 = 0;
                        } else {
                            $no_16 = $no_14 - $ptkp?->ptkp_tahun;
                        }
                    }
                    $persen5 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
                            $persen5 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
                        } else {
                            $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
                        }
                    } else {
                        $persen5 = 0;
                    }
                    $persen15 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
                            $persen15 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000- 60000000) * 0.18;
                        } else {
                            $persen15 = 190000000 * 0.15;
                        }
                    } else {
                        $persen15 = 0;
                    }
                    $persen25 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                            $persen25 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
                        } else {
                            $persen25 = 250000000 * 0.25;
                        }
                    } else {
                        $persen25 = 0;
                    }
                    $persen30 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 5000000000) {
                            $persen30 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
                        } else {
                            $persen30 = 4500000000 * 0.30;
                        }
                    } else {
                        $persen30 = 0;
                    }
                    $persen35 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 5000000000) {
                            $persen35 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.35 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.42;
                    } else {
                        $persen35 = 0;
                    }

                    $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;

                    $no19 = floor(($no17 / 12) * $total_ket);
                @endphp

                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>B.1. RINCIAN PENGHASILAN</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-3 mt-2">PENGHASILAN TERATUR</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_rutin) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-3">PENGHASILAN TIDAK TERATUR</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_tidak_rutin) }}">
                    </div>
                </div><div class="row m-0 mt-3">
                    <div class="col-lg-12">
                        <h6>PENGHASILAN BRUTO</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">1. Gaji/Pensiun atau THT/JHT</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_gaji) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">2. Tunjangan PPh</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="-">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">3. Tunjangan Lainnya, Uang Lembur dan sebagainya</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_tj_lainnya) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">4. Honorarium dan Imbalan Lainnya</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="-">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">5. Premi Asuransi yang dibayarkan Pemberi Kerja</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($jaminan) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 ">6. Penerimaan dalam Bentuk Natura atau Kenikmatan Lainnya yang dikenakan Pemotongan PPh Pasal 21</label>
                    <div class="col-sm-3 mt-1 ">
                        <input type="text" disabled class="form-control" value="-">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">7. Tantiem, Bonus, Gratifikasi, Jaspro dan THR</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($bonus_sum) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5" style="margin-top: 40px">8. Jumlah Penghasilan Bruto (1 + 2 + 3 + 4 + 5 + 6 + 7)</label>
                    <div class="col-sm-3 ">
                        <hr>
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_gaji + $total_pph + $total_tj_lainnya + $total_honorium + $jaminan + $total_pph_21 + $bonus_sum) }}">
                    </div>
                </div>
                <div class="row m-0 mt-4">
                    <label class="col-sm-5 mt-2" style="font-weight: bold; text-align: center;">Total Penghasilan (Teratur + Tidak Teratur)</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" style="font-weight: bold" value="{{ rupiah($total_rutin + $total_tidak_rutin) }}">
                    </div>
                </div>

                <div class="row m-0 mt-3">
                    <div class="col-lg-12">
                        <h6>PENGURANGAN PENGHASILAN</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">9. Biaya Jabatan/Biaya Pensiun</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($biaya_jabatan) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">10. Iuran Pensiun atau Iuran THT/JHT</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($pengurang) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">11. Jumlah Pengurangan (9 + 10)</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($biaya_jabatan + $pengurang) }}">
                    </div>
                </div>

                <div class="row m-0 mt-3">
                    <div class="col-lg-12">
                        <h6>B.2 PENGHITUNGAN PPh PASAL 21</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">12. Jumlah Penghasilan Neto (8 - 11)</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah(($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang)) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">13. Penghasilan Neto Masa sebelumnya</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="-">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2" style="font-weight: bold; text-align: center;">Total Penghasilan Neto</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" style="font-weight: bold;" value="{{ rupiah(($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang)) }}">
                    </div>
                </div>

                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">14. Jumlah Penghasilan Neto untuk PPh Pasal 21 (Setahun/Disetahunkan)</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah(round($no_14)) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">15. Penghasilan Tidak Kena Pajak (PTKP)</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($ptkp?->ptkp_tahun) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">16. Penghasilan Kena Pajak Setahun/Disetahunkan</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($no_16) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">17. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($no17) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">18. PPh Pasal 21 yang telah dipotong Masa Sebelumnya</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="-">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">19. PPh Pasal 21 Terutang</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($no19) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">20. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($total_pph_lunas) }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">U. PPh Pasal 21 yang masih harus dibayar</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="{{ rupiah($no19 - $total_pph_lunas) }}">
                    </div>
                </div>
            </div>
            @else
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                            Penghasilan Teratur
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link border" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                        Penghasilan Tidak Teratur
                    </button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link border" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                        Bonus
                    </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive ml-2">
                            <table class="table cell-border" id="table_export" style="width: 100%; white-space: nowrap;">
                                <tr>
                                    <td class="p-0">
                                        <h5 class="card-title mt-3" style="text-align: start">PENGHASILAN TERATUR</h5>
                                        <table class="table text-center cell-border table-striped " style="width: 100%;">
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
                                                    $total_jamsostek = null;
                                                    $total_uang_makan = null;
                                                    $total_tj_pulsa = null;
                                                    $total_tj_vitamin = null;
                                                    $total_tj_transport = null;
                                                @endphp
                                                <tr>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Bulan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Gaji<br>Pokok</th>
                                                    <th colspan="8" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Tunjangan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; max-width: 30px; font-size: 8px;">Total<br>Gaji</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Penambah<br>Bruto<br>Jamsostek</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Tunjangan<br>Uang<br>Makan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Tunjangan<br>Uang<br>Pulsa</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Tunjangan<br>Uang<br>Vitamin</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; min-width: 30px; font-size: 8px;">Tunjangan<br>Uang<br>Transport</th>
                                                </tr>
                                                <tr style="background-color: #DAE2B6">
                                                    <th style="font-size: 8px; min-width: 20px;">Keluarga</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Jabatan</th>
                                                    <th style="font-size: 8px; min-width: 20px;">penyesuaian</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Perumahan</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Listrik & Air</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Pelaksana</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Kemahalan</th>
                                                    <th style="font-size: 8px; min-width: 20px;">Kesejahteraan</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 11px;">
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

                                                    $total_jamsostek += $jamsostek[$i];
                                                    $total_uang_makan += $gj[$i]['uang_makan'];
                                                    $total_tj_pulsa += $gj[$i]['tj_pulsa'];
                                                    $total_tj_vitamin += $gj[$i]['tj_vitamin'];
                                                    $total_tj_transport += $gj[$i]['tj_transport'];

                                                    $total_gaji =  $total_gj_pokok + $total_tj_keluarga + $total_tj_jabatan + $total_gj_penyesuaian
                                                                   + $total_tj_perumahan + $total_tj_telepon + $total_tj_pelaksana + $total_tj_kemahalan
                                                                   + $total_tj_kesejahteraan;

                                                    $total_gaji_bln = ($gj[$i]['gj_pokok']) + ($gj[$i]['tj_keluarga']) + ($gj[$i]['tj_jabatan']) + ($gj[$i]['gj_penyesuaian'])
                                                                      + ($gj[$i]['tj_perumahan']) + ($gj[$i]['tj_telepon']) + ($gj[$i]['tj_pelaksana']) + ( $gj[$i]['tj_kemahalan'])
                                                                      + ($gj[$i]['tj_kesejahteraan']);
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
                                                        <td>{{ ($total_gaji_bln != 0 ) ? rupiah($total_gaji_bln) : '-' }}</td>
                                                        <td>{{ ($jamsostek[$i] != 0) ? rupiah($jamsostek[$i]) : '-' }}</td>
                                                        <td>{{ ($gj[$i]['uang_makan'] != 0) ? rupiah($gj[$i]['uang_makan']) : '-' }}</td>
                                                        <td>{{ ($gj[$i]['tj_pulsa'] != 0) ? rupiah($gj[$i]['tj_pulsa']) : '-' }}</td>
                                                        <td>{{ ($gj[$i]['tj_vitamin'] != 0) ? rupiah($gj[$i]['tj_vitamin']) : '-' }}</td>
                                                        <td>{{ ($gj[$i]['tj_transport'] != 0) ? rupiah($gj[$i]['tj_transport']) : '-' }}</td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                            <tfoot style="font-weight: bold; font-size: 11px;">
                                                <tr>
                                                    <td colspan="1">
                                                        Total
                                                    </td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_gj_pokok) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_keluarga) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_jabatan) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_gj_penyesuaian) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_perumahan) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_telepon) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_pelaksana) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_kemahalan) }}</td>
                                                    <td style="background-color: #FED049; ">{{ rupiah($total_tj_kesejahteraan) }}</td>
                                                    <td style="background-color: #cecece; ">{{ rupiah($total_gaji) }}</td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_jamsostek) }}</td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_uang_makan) }}</td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_tj_pulsa) }}</td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_tj_vitamin) }}</td>
                                                    <td style="background-color: #54B435; ">{{ rupiah($total_tj_transport) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive ml-0">
                            <table class="table cell-border" id="table_export" style="width: 100%; white-space: nowrap;">
                                <tr>
                                    <td class="p-0">
                                        <h5 class="card-title mt-3" style="text-align: start">PENGHASILAN TIDAK TERATUR</h5>
                                        <table class="table text-center cell-border table-striped" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Bulan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">T. Uang Lembur</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Pengganti Biaya Kesehatan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Uang Duka</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">SPD</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">SPD Pendidikan </th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">SPD Pindah Tugas</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Pengganti <br>Uang Seragam</th>
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
                                            <tfoot style="font-weight: bold">
                                                <tr>
                                                    <td  colspan="1" >
                                                        Total
                                                    </td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_uang_lembur) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_pengganti_kesehatan) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_uang_duka) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_spd) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_spd_pendidikan) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_spd_pindah_tugas) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_pengganti_seragam) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="table-responsive ml-2">
                            <table class="table cell-border" id="table_export" style="width: 100%; white-space: nowrap;">
                                <tr>
                                    <td class="p-0">
                                        <h5 class="card-title mt-3" style="text-align: start">BONUS</h5>
                                        <table class="table text-center cell-border table-striped" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Bulan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Tunjangan Hari Raya</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Jasa Produksi</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Dana Pendidikan</th>
                                                    <th rowspan="2" style="background-color: #CCD6A6; ">Tambahan <br>Penghasilan</th>
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
                                            <tfoot style="font-weight: bold">
                                                <tr>
                                                    <td colspan="1">
                                                        Total
                                                    </td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_thr) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_jasa_produksi) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_dana_pendidikan) }}</td>
                                                    <td style="background-color: #54B435; " colspan="1">{{ rupiah($total_tambahan_penghasilan) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('script')
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
                return {
                    search: params.term || '',
                    page: params.page || 1
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

    function printReport()
    {
        var prtContent = document.getElementById("reportPrinting");
        var mywindow = window.open();

        mywindow.document.write(`<html><head><title></title>`);
        mywindow.document.write(`<link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />`);
        mywindow.document.write(`<link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />`);
        mywindow.document.write(`<link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />`);
        mywindow.document.write(`<style> .table-responsive {-ms-overflow-style: none; scrollbar-width: none; } .table-responsive::-webkit-scrollbar { overflow-y: hidden; overflow-x: scroll; } </style>`);
        mywindow.document.write(prtContent.innerHTML);
        mywindow.document.write(`</body></html>`);

        setTimeout(function () {
            mywindow.print();
            //mywindow.close();
        }, 1000)
        return true;
    }
</script>
@endpush
