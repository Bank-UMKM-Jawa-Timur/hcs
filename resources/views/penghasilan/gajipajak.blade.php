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
            <h5 class="card-title">Pajak Penghasilan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="">Pajak Penghasilan </a></p>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row m-0">
        <div class="col">
            <a class="mb-3" href="{{ route('penghasilan.create') }}">
                <button class="btn btn-primary">Import penghasilan</button>
            </a>
        </div>
    </div>
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
                        <option value="1">Bukti Pembayaran Gaji Pajak</option>
                        <option value="2">Detail Gaji Pajak</option>
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
                <button class="btn btn-info" type="submit">Tampilkan</button>
            </a>
        </div>
  </form>
    <div class="card ml-3 mr-3 mb-3 mt-4 shadow" id="reportPrinting">
        <div class="col-md-12">
            @php
                $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

                function rupiah($angka){
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
            @endphp
            @if ($mode == 1)
            <div class="card-body ml-0 mr-0 mt-0 mb-2">
                <div class="row m-0 mt-2 mb-2">
                    <label class="col-sm-2 mt-2">Nomor</label>
                    <div class="col-sm-7">
                        <input type="text" disabled class="form-control" style="max-width: 100px" value="">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" class="btn-success" style="text-align: right" value="Print" onClick="printReport()">
                    </div>
                </div>
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG</h6>
                    </div>
                </div> 
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NPWP</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">NO. REKENING GAJI :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NIK</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">NAMA</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">ALAMAT</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">JENIS KELAMIN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">JABATAN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">BPJSKT :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">STATUS PERKAWINAN</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                    <label class="col-sm-2 mt-2 text-left">BPKSKES :</label>
                    <div class="col-sm-3">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">MASA KERJA</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">KODE OBYEK PAJAK</label>
                    <div class="col-sm-5">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">KETERANGAN PEGAWAI</label>
                    <div class="col-sm-5 ">
                        <input type="text" disabled class="form-control" value="">
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
                                <th rowspan="2" style="background-color: #CCD6A6;">KETERANGAN</th>
                            </tr>
                            <tr style="background-color: #DAE2B6">
                                <th>RUTIN</th>
                                <th>TIDAK RUTIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tbody>
                        <tfoot style="font-weight: bold">
                            <tr>
                                <td colspan="1">
                                    Total 
                                </td>
                                <td style="background-color: #54B435; ">-</td>
                                <td style="background-color: #54B435; ">-</td>
                                <td style="background-color: #54B435; ">-</td>
                                <td style="background-color: #54B435; ">-</td>
                                <td style="background-color: #54B435; ">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>B.1. RINCIAN PENGHASILAN</h6>
                    </div>
                </div> 
                <div class="row m-0 mt-2">
                    <label class="col-sm-3 mt-2">PENGHASILAN TERATUR</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-3">PENGHASILAN TIDAK TERATUR</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div><div class="row m-0 mt-3">
                    <div class="col-lg-12">
                        <h6>PENGHASILAN BRUTO</h6>
                    </div>
                </div> 
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">1. Gaji/Pensiun atau THT/JHT</label>
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">2. Tunjangan PPh</label> 
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">3. Tunjangan Lainnya, Uang Lembur dan sebagainya</label> 
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">4. Honorarium dan Imbalan Lainnya</label> 
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">5. Premi Asuransi yang dibayarkan Pemberi Kerja</label>  
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 ">6. Penerimaan dalam Bentuk Natura atau Kenikmatan Lainnya yang dikenakan Pemotongan PPh Pasal 21</label>  
                    <div class="col-sm-3 mt-1 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">7. Tantiem, Bonus, Gratifikasi, Jaspro dan THR</label>  
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5" style="margin-top: 40px">8. Jumlah Penghasilan Bruto (1 + 2 + 3 + 4 + 5 + 6 + 7)</label>  
                    <div class="col-sm-3 ">
                        <hr>    
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-4">
                    <label class="col-sm-5 mt-2" style="font-weight: bold; text-align: center;">Total Penghasilan (Teratur + Tidak Teratur)</label>  
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" style="font-weight: bold" value="">
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
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">10. Iuran Pensiun atau Iuran THT/JHT</label>  
                    <div class="col-sm-3 ">
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">11. Jumlah Pengurangan (9 + 10)</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
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
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">13. Penghasilan Neto Masa sebelumnya</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2" style="font-weight: bold; text-align: center;">Total Penghasilan Neto</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" style="font-weight: bold;" value="">
                    </div>
                </div>

                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-1">14. Jumlah Penghasilan Neto untuk PPh Pasal 21 (Setahun/Disetahunkan)</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">15. Penghasilan Tidak Kena Pajak (PTKP)</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">16. Penghasilan Kena Pajak Setahun/Disetahunkan</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">17. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">18. PPh Pasal 21 yang telah dipotong Masa Sebelumnya</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">19. PPh Pasal 21 Terutang</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">20. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-5 mt-2">U. PPh Pasal 21 yang masih harus dibayar</label>  
                    <div class="col-sm-3 "> 
                        <input type="text" disabled class="form-control" value="">
                    </div>
                </div>
            </div>
            @else     
                <div class="table-responsive">
                    <table class="table cell-border" id="table_export" style="width: 100%; white-space: nowrap; table-layout: fixed;">
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title mt-3" style="text-align: start">PENGHASILAN TERATUR</h5>
                                <table class="table text-center cell-border table-striped" style="width: 100%;">
                                    <thead>
                                        @php
                                            $total_k = null;
                                            $total_non = null;
                                        @endphp
                                        <tr>
                                            <th class="sticky-col" rowspan="2" style="background-color: #CCD6A6; min-width: 100px;">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 100px; ">Gaji Pokok</th>
                                            <th colspan="8" style="background-color: #CCD6A6; ">Tunjangan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; ">JAMSOSTEK</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; ">Penambah <br>Bruto Jamsostek</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; ">T. Uang Makan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; ">T. Uang Pulsa</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; ">T. Uang Vitamin</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px; ">T. Uang Transport</th>
                                        </tr>
                                        <tr style="background-color: #DAE2B6">
                                            <th>Keluarga</th>
                                            <th>Jabatan</th>
                                            <th>penyesuaian</th>
                                            <th>Perumahan</th>
                                            <th style="min-width: 100px">Listrik & Air</th>
                                            <th>Pelaksana</th>
                                            <th>Kemahalan</th>
                                            <th>Kesejahteraan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td class="sticky-col">{{ $bulan[$i] }}</td>
                                                <td>{{ ($gj[$i]['gj_pokok'] != 0) ? rupiah($gj[$i]['gj_pokok']) : '-' }}</td>
                                                <td >{{ ($gj[$i]['tj_keluarga'] != 0) ? rupiah($gj[$i]['tj_keluarga']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_jabatan'] != 0) ? rupiah($gj[$i]['tj_jabatan']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['gj_penyesuaian'] != 0) ? rupiah($gj[$i]['gj_penyesuaian']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_perumahan'] != 0) ? rupiah($gj[$i]['tj_perumahan']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_telepon'] != 0) ? rupiah($gj[$i]['tj_telepon']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_pelaksana'] != 0) ? rupiah($gj[$i]['tj_pelaksana']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_kemahalan'] != 0) ? rupiah($gj[$i]['tj_kemahalan']) : '-' }}</td>
                                                <td  >{{ ($gj[$i]['tj_kesejahteraan'] != 0) ? rupiah($gj[$i]['tj_kesejahteraan']) : '-' }}</td>
                                                <td>{{ ($jamsostek[$i] != 0) ?rupiah($jamsostek[$i]) : '-' }}</td>
                                                <td>-</td>
                                                <td>{{ ($gj[$i]['uang_makan'] != 0) ? rupiah($gj[$i]['uang_makan']) : '-' }}</td>
                                                <td>{{ ($gj[$i]['tj_pulsa'] != 0) ? rupiah($gj[$i]['tj_pulsa']) : '-' }}</td>
                                                <td>{{ ($gj[$i]['tj_vitamin'] != 0) ? rupiah($gj[$i]['tj_vitamin']) : '-' }}</td>
                                                <td>{{ ($gj[$i]['tj_transport'] != 0) ? rupiah($gj[$i]['tj_transport']) : '-' }}</td>
                                                @php
                                                    $total_k += $gj[$i]['tj_keluarga'] + $gj[$i]['tj_jabatan'] + $gj[$i]['gj_penyesuaian'] + $gj[$i]['tj_perumahan'] + $gj[$i]['tj_telepon'] + $gj[$i]['tj_pelaksana'] + $gj[$i]['tj_kemahalan'] +$gj[$i]['tj_kesejahteraan'];
                                                    $total_non += $gj[$i]['gj_pokok'] + $gj[$i]['tj_pulsa'] + $gj[$i]['tj_pulsa'] + $gj[$i]['uang_makan'];
                                            @endphp
                                        </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td class="sticky-col" colspan="1">
                                                Total 
                                            </td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #FED049; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                            <td style="background-color: #54B435; ">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title mt-5" style="text-align: start">PENGHASILAN TIDAK TERATUR</h5>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_penghasilan = array();
                                        @endphp
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                @for ($j = 0; $j < 6; $j++)
                                                    <td>{{ ($penghasilan[$i][$j] != 0) ? rupiah($penghasilan[$i][$j]) : '-' }}</td>
                                                    @php
                                                        array_push($total_penghasilan, $penghasilan[$i][$j]);
                                                    @endphp
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td  colspan="1" >
                                                Total
                                            </td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title mt-5" style="text-align: start">BONUS</h5>
                                <table class="table text-center cell-border table-striped" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6; ">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; ">Tunjangan Hari Raya</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; ">Jasa Produksi</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; ">Dana Pendidikan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_bonus =array();
                                        @endphp
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                @for ($j = 0; $j < 3; $j++)
                                                    <td>{{ ($bonus[$i][$j] != 0) ? rupiah($bonus[$i][$j]) : '-' }}</td>
                                                    @php
                                                        array_push($total_bonus, $bonus[$i][$j]);
                                                    @endphp
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="1">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                            <td style="background-color: #54B435; " colspan="1">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </table>
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

        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />');
        mywindow.document.write('<link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />');
        mywindow.document.write('<link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />');
        mywindow.document.write('<style> .table-responsive {-ms-overflow-style: none; scrollbar-width: none; } .table-responsive::-webkit-scrollbar { overflow-y: hidden; overflow-x: scroll; } </style>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(prtContent.innerHTML);
        mywindow.document.write('</body></html>');

        setTimeout(function () {
        mywindow.print();
        mywindow.close();
        }, 1000)
        return true;

        return true;
    }
</script>
@endpush
