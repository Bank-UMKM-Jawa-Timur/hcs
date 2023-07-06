<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
  <title>
    Human Capital System | BANK UMKM JATIM
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <!-- CSS Files -->
  <link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/css/datatables.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('style/assets/css/loading.css') }}">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

  <style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    .loader-wrapper {
      width: 100%;
      height: 100%;
      top: 0;
      left: 100px;
      position: fixed;
      background-color: rgba(110, 110, 110, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .vh-100 {
      height: 90vh!important;
    }

    /* body{
        width: 285mm;
        height: 400mm;
    } */

    /* @media print {
        table {
            border: 3px solid #000 !important;
            border-width: 1px 0 0 1px !important;
        }
        th, td, th, tr {
            border: 3px solid #000 !important;
            border-width: 0 1px 1px 0 !important;
        }
    } */
  </style>

</head>

<body>
    <div class="card">
        <div class="card-header">
            <div class="card-header">
                <h5 class="card-title">CV {{ $karyawan->nama_karyawan }}</h5>
            </div>
        </div>
        <div class="card-body">
            @php
                $status = 'TK';
                if ($karyawan->status == 'Kawin' && $suis) {
                    $jml_anak = ($suis->jml_anak > 2) ? 2 : $suis->jml_anak;
                    $status = 'K/'.$jml_anak ?? '0';
                } else if($karyawan->status == 'Kawin' && !$suis){
                    $status = 'K/0';
                }
            @endphp
            <form action="{{ route('karyawan.show', $karyawan->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
                @csrf
                @method('PUT')
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>Biodata Diri Karyawan</h6>
                    </div>
                </div>
                @php
                    $no = 1;
                    function rupiah($angka){
                        $hasil_rupiah = number_format($angka, 0, ",", ".");
                        return $hasil_rupiah;
                    }
                    $totalGaji = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
                    $tj = DB::table('tunjangan_karyawan')
                        ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                        ->where('nip', $karyawan->nip)
                        ->get();
                @endphp
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">NIP</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nip }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">NIK</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nik }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Nama Karyawan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->nama_karyawan }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Tempat, Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->tmp_lahir }}, {{ $karyawan->tgl_lahir->format('d F Y') }}">
                    </div>
                </div>
                @php
                    use Carbon\Carbon;
                    $tanggalLahir = Carbon::create($karyawan->tgl_lahir);
                    $waktuSekarang = Carbon::now();
    
                    $hitung = $waktuSekarang->diff($tanggalLahir);
                    $umur = $hitung->format('%y Tahun | %m Bulan | %d Hari');
    
                @endphp
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Umur</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $umur }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Agama</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan?->agama?->agama ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->jk }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Status Pernikahan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control"  value="{{ $status ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Kewarganegaraan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control"  value="{{ $karyawan->kewarganegaraan }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Alamat KTP</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->alamat_ktp }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Alamat Sekarang</label>
                    <div class="col-sm-10">
                        @php
                            if (!$karyawan->alamat_sek || $karyawan->alamat_sek == '') {
                                $alamatSek = '-';
                            } else {
                                $alamatSek = $karyawan->alamat_sek;
                            }
                        @endphp
                        <input type="text" disabled class="form-control" value="{{ $alamatSek }}">
                    </div>
                </div>
                <hr>
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>No Rekening & NPWP</h6>
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Nomor Rekening</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->no_rekening ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">NPWP</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ npwp($karyawan->npwp) ?? '-' }}">
                    </div>
                </div>
                <hr>
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>Data Karyawan</h6>
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Kantor</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->type == 2 ? "Cabang {$karyawan->entitas->cab->nama_cabang}" : 'Pusat' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Jabatan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->jabatan->nama_jabatan }}">
                    </div>
                </div>
                @if(isset($karyawan->entitas->div))
                    <div class="row m-0 mt-1">
                        <label class="col-sm-2 mt-2">Divisi</label>
                        <div class="col-sm-10">
                            <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->div->nama_divisi }}">
                        </div>
                    </div>
                @endif
                @if(isset($karyawan->entitas->subDiv))
                    <div class="row m-0 mt-1">
                        <label class="col-sm-2 mt-2">Sub Divisi</label>
                        <div class="col-sm-10">
                            <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->subDiv->nama_subdivisi }}">
                        </div>
                    </div>
                @endif
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Pangkat</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->panggol?->pangkat ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Golongan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->panggol?->golongan ?? '-' }}">
                    </div>
                </div>
                @if($karyawan->bagian)
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Bagian</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->bagian->nama_bagian }}">
                    </div>
                </div>
                @endif
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Status Karyawan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->status_karyawan}}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Status Jabatan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->status_jabatan}}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Keterangan Jabatan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->ket_jabatan ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Tanggal Mulai</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan?->tgl_mulai?->format('d F Y') ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-0">Pendidikan Terakhir</label>
                    <div class="col-sm-10">
                            <input type="text" disabled class="form-control" value="{{ ($karyawan->pendidikan ?? '-') }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-0">Pendidikan Major</label>
                    <div class="col-sm-10">
                            <input type="text" disabled class="form-control" value="{{ ($karyawan->pendidikan_major ?? '-') }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">SK Pengangkatan</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ (!$karyawan->skangkat || $karyawan->skangkat == '') ? '-' : $karyawan->skangkat }}">
                    </div>
                </div>
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Tanggal Pengangkatan</label>
                    <div class="col-sm-10">
                        {{-- <input type="text" disabled class="form-control" value="{{ $karyawan?->tanggal_pengangkat?->format('d F Y') ?? '-' }}"> --}}
                        <input type="text" disabled class="form-control" value="{{ (!$karyawan->tanggal_pengangkat || $karyawan->tanggal_pengangkat == '') ? '-' : date('d F Y', strtotime($karyawan->tanggal_pengangkat)) }}">
                    </div>
                </div>
                @php
                    $mulaKerja = Carbon::create($karyawan->tanggal_pengangkat);
                    $waktuSekarang = Carbon::now();
    
                    $hitung = $waktuSekarang->diff($mulaKerja);
                    $masaKerja = $hitung->format('%y Tahun | %m Bulan | %d Hari');
    
                @endphp
                <div class="row m-0 mt-1">
                    <label class="col-sm-2 mt-2">Masa Kerja</label>
                    <div class="col-sm-10">
                        @if (isset($karyawan->tanggal_pengangkat) != null)
                            <input type="text" disabled class="form-control" value="{{ $masaKerja }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <br><br><br>
                <hr class="mt-3">
                <div class="row m-0 mt-2">
                    <div class="col-lg-12">
                        <h6>Data Tunjangan Karyawan</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Kartu Peserta Jamsostek (KPJ)</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->kpj ?? '-' }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Jaminan Kesehatan Nasional (JKN)</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->jkn ?? '-' }}">
                    </div>
                </div>
                <!-- <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">Gaji Pokok</label>
                    <div class="col-sm-10">
                        @if (isset($karyawan->gj_pokok) != null)
                            <input type="text" disabled class="form-control" value="{{ rupiah($karyawan->gj_pokok) }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">Gaji Penyesuaian</label>
                    <div class="col-sm-10">
                        @if (isset($karyawan->gj_penyesuaian) != null)
                            <input type="text" disabled class="form-control" value="{{ rupiah($karyawan->gj_penyesuaian) }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                    {{-- {{$totalGaji}} --}}
                    {{-- {{$karyawan->gj_pokok + $karyawan->gj_penyesuaian}} --}}
                    @if (isset($tj))
                        @foreach ($tj as $item)
                            <div class="row m-0 mt-2">
                                <label class="col-sm-2 mt-2"> {{ ($item->nama_tunjangan != 'DPP') ? 'Tunjangan ' . $no++ : 'Iuran'  }}</label>
                                <div class="col-sm-5">
                                    <input type="text" disabled class="form-control" value="{{ $item->nama_tunjangan }}">
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" disabled class="form-control" value="Rp. {{ rupiah($item->nominal) }}">
                                </div>
                            </div>
                            @php
                                if($item->nama_tunjangan != 'DPP'){
                                    $totalGaji += $item->nominal;
                                }
                            @endphp
                        @endforeach
                    @endif
                <div class="row m-0 mt-2">
                    <label for="" class="col-sm-2 mt-2">Total Gaji</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="Rp. {{ rupiah($totalGaji) }}">
                    </div>
                </div> -->
    
                @if ($karyawan->status == 'Kawin' && $suis != null)
                    <hr>
                    <div class="row m-0 ">
                        <div class="col-lg-12">
                            <h6>Data Keluarga</h6>
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Status Pasangan</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ $suis->enum }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Nama</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ $suis->nama }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">SK Tunjangan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled value="{{ $suis->sk_tunjangan ?? '-' }}">
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ date('d F Y' ,strtotime($suis->tgl_lahir)) }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Alamat</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ $suis->alamat }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Pekerjaan</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ $suis->pekerjaan }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <label class="col-sm-2 mt-0">Jumlah Anak</label>
                        <div class="col-sm-10">
                            @if (isset($suis) != null)
                                <input type="text" disabled class="form-control" value="{{ $suis->jml_anak }}">
                            @else
                                <input type="text" disabled class="form-control" value="-">
                            @endif
                        </div>
                    </div>
    
                    @if (count($data_anak) > 0)
                        @foreach ($data_anak as $key => $item)
                            @php
                                $index = ($key == 0) ? 'Pertama' : 'Kedua';
                            @endphp
                            <div class="row m-0 mt-2">
                                <label class="col-sm-2 mt-0">Nama Anak {{ $index }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{{ $item->nama }}">
                                </div>
                            </div>
                            <div class="row m-0 mt-2">
                                <label class="col-sm-2 mt-0">Tanggal Lahir Anak {{ $index }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{{ date('d F Y', strtotime($item->tgl_lahir)) }}">
                                </div>
                            </div>
                            <div class="row m-0 mt-2">
                                <label class="col-sm-2 mt-0">SK Tunjangan Anak {{ $index }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{{ $item->sk_tunjangan }}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif
                <hr>
    
                <div class="row m-0 mt-3">
                    <div class="col-lg-12">
                        <h6 class="mt-2">Histori</h6>
                    </div>
                </div>
    
                <div class="row m-0 mt-2">
                    <div class="col-12">
                        <p class="m-0 mt-2 text-muted">Histori Jabatan</p>
                        <div class="table-responsive overflow-hidden pt-2">
                            <table class="table text-center" id="pjs-table">
                                <thead>
                                    <tr>
                                        <th style="background-color: #CCD6A6; text-align: center;">#</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">No SK</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">Jabatan</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">Mulai</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">Berakhir</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pjs as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data['no_sk'] }}</td>
                                        <td>{{ $data['jabatan'] }}</td>
                                        <td>{{ date('d M Y', strtotime($data['mulai'])) ?? '-' }}</td>
                                        <td>{{ ($data['berakhir'] != null) ? date('d M Y', strtotime($data['berakhir'])) : '-' }}</td>
                                        <td>{{ !$data['berakhir'] ? 'Aktif' : 'Nonaktif' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
    
                <div class="row m-0 mt-2">
                    <div class="col-12">
                        <p class="m-0 mt-2 text-muted">Histori Surat Peringatan</p>
                        <div class="table-responsive overflow-hidden pt-2">
                            <table class="table text-center" id="sp-table">
                                <thead>
                                    <tr>
                                        <th style="background-color: #CCD6A6; text-align: center;">#</th>
                                        <th style="background-color: #CCD6A6; text-align: center; min-width: 75px;">No SP</th>
                                        <th style="background-color: #CCD6A6; text-align: center; min-width: 100px;">Tanggal</th>
                                        <th style="background-color: #CCD6A6; text-align: center;">Pelanggaran</th>
                                        <th style="background-color: #CCD6A6; text-align: center; min-width: 125px;">Sanksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sp as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->no_sp ?? '-' }}</td>
                                            <td>{{ $item->tanggal_sp->format('d M Y') ?? '-' }}</td>
                                            <td>{{ $item->pelanggaran ?? '-' }}</td>
                                            <td>{{ $item->sanksi ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

  <!--   Core JS Files   -->
  <script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <!-- Chart JS -->
  <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('style/assets/js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('style/assets/js/paper-dashboard.min.js') }}" type="text/javascript"></script>
  <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('style/assets/demo/demo.js') }}"></script>
  <!-- Jam Realtime -->
  <script src="{{ asset('style/assets/js/jam.js') }}" async></script>
  <script src="{{ asset('style/assets/js/Datatables.js') }}"></script>
  <script src="{{ asset('style/assets/js/ReorderWithResize.js') }}"></script>
  <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initChartsPages();
    });

    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    // $('ul.nav>li>a').filter(function() {
    //   return this.href == url;
    // }).parent().addClass('active');

    // // for treeview
    // $('ul.sub-menu>li>a').filter(function() {
    //   return this.href == url;
    // }).parentsUntil(".nav > .sub-menu").addClass('active show');

    // $('ul.sub-menu>li.dropdown>div.dropdown-menu>a').filter(function() {
    //   return this.href == url;
    // }).parentsUntil(".nav > .sub-menu").addClass('active');

    function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

    $(window).on("load", function() {
      $(".loader-wrapper").fadeOut("slow");
      window.print()
    });

    $("#pjs-table").DataTable({
        paging: false,
        info: false,
        searching: false,
        ordering: false
    })
    $("#sp-table").DataTable({
        paging: false,
        info: false,
        searching: false,
        ordering: false
    })
  </script>
</html>