@extends('layouts.app-template')
@section('content')
    <div class="card-header">
        <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="card-title font-weight-bold">Detail Data Karyawan</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Detail</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            <a href="{{ route('export-cv', $karyawan->nip) }}"  target="__blank" >
                <button class="btn btn-primary-light"><i class="ti ti-file-export"></i> Export</button>
            </a>
        </div>
        </div>
    </div>
    <div class="body-pages">
    <div class="card space-y-5">
        @php
            $status = 'TK';
            if ($karyawan->status == 'Kawin' && $suis) {
                $jml_anak = ($suis->jml_anak > 3) ? 3 : $suis->jml_anak;
                $status = 'K/'.$jml_anak ?? '0';
            } else if($karyawan->status == 'Kawin' && !$suis){
                $status = 'K/0';
            }
        @endphp
        <form action="{{ route('karyawan.show', $karyawan->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="row m-0">
                <div class="col-lg-12">
                    <h6 class="row-title">Biodata Diri Karyawan</h6>
                </div>
            </div>
            @php
                $no = 1;
                $totalGaji = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
                $tj = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                    ->where('nip', $karyawan->nip)
                    ->where('status', 1)
                    ->groupBy('mst_tunjangan.nama_tunjangan')
                    ->get();
            @endphp
            <div class="row ">
                <label class="w-2/4 mt-2">NIP</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->nip }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">NIK</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->nik }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Nama Karyawan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->nama_karyawan }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Tempat, Tanggal Lahir</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->tmp_lahir }}, {{ ($karyawan->tgl_lahir != null) ? $karyawan->tgl_lahir->format('d F Y') : '-' }}">
                </div>
            </div>
            @php
                use Carbon\Carbon;
                $tanggalLahir = Carbon::create($karyawan->tgl_lahir);
                $waktuSekarang = Carbon::now();

                $hitung = $waktuSekarang->diff($tanggalLahir);
                $umur = $hitung->format('%y Tahun | %m Bulan | %d Hari');

            @endphp
            <div class="row ">
                <label class="w-2/4 mt-2">Umur</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $umur }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Agama</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan?->agama?->agama ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Jenis Kelamin</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->jk }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Status Pernikahan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled"  value="{{ $karyawan->status ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Kewarganegaraan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled"  value="{{ $karyawan->kewarganegaraan }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Alamat KTP</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->alamat_ktp }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Alamat Sekarang</label>
                <div class="w-full">
                    @php
                        if (!$karyawan->alamat_sek || $karyawan->alamat_sek == '') {
                            $alamatSek = '-';
                        } else {
                            $alamatSek = $karyawan->alamat_sek;
                        }
                    @endphp
                    <input type="text" disabled class="form-input-disabled" value="{{ $alamatSek }}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>No Rekening & NPWP</h6>
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Nomor Rekening</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->no_rekening ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">NPWP</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ npwp($karyawan->npwp) ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Status PTKP</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->status_ptkp ?? '-' }}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Karyawan</h6>
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Kantor</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->entitas->type == 2 ? "Cabang {$karyawan->entitas->cab->nama_cabang}" : 'Pusat' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Jabatan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->jabatan?->nama_jabatan }}">
                </div>
            </div>
            @if(isset($karyawan->entitas->div))
                <div class="row ">
                    <label class="w-2/4 mt-2">Divisi</label>
                    <div class="w-full">
                        <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->entitas->div->nama_divisi }}">
                    </div>
                </div>
            @endif
            @if(isset($karyawan->entitas->subDiv))
                <div class="row ">
                    <label class="w-2/4 mt-2">Sub Divisi</label>
                    <div class="w-full">
                        <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->entitas->subDiv->nama_subdivisi }}">
                    </div>
                </div>
            @endif
            <div class="row ">
                <label class="w-2/4 mt-2">Pangkat</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->panggol?->pangkat ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Golongan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->panggol?->golongan ?? '-' }}">
                </div>
            </div>
            @if($karyawan->bagian)
                @if ($karyawan->kd_entitas == null)
                    @php
                        $subDiv = DB::table('mst_sub_divisi')
                            ->where('kd_subdiv', $karyawan->bagian->kd_entitas)
                            ->first();
                        $divisi = DB::table('mst_divisi')
                            ->where('kd_divisi', $subDiv ? $subDiv->kd_divisi : $karyawan->bagian->kd_entitas)
                            ->first();
                    @endphp
                    <div class="row ">
                        <label class="w-2/4 mt-2">Divisi</label>
                        <div class="w-full">
                            <input type="text" disabled class="form-input-disabled" value="{{ $divisi->nama_divisi }}">
                        </div>
                    </div>
                    @if ($subDiv)
                        <div class="row ">
                            <label class="w-2/4 mt-2">Sub Divisi</label>
                            <div class="w-full">
                                <input type="text" disabled class="form-input-disabled" value="{{ $subDiv->nama_subdivisi }}">
                            </div>
                        </div>
                    @endif
                @endif
            <div class="row ">
                <label class="w-2/4 mt-2">Bagian</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->bagian->nama_bagian }}">
                </div>
            </div>
            @endif
            <div class="row ">
                <label class="w-2/4 mt-2">Status Karyawan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->status_karyawan}}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Status Jabatan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->status_jabatan}}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Keterangan Jabatan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->ket_jabatan ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Tanggal Mulai</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan?->tgl_mulai?->format('d F Y') ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-0">Pendidikan Terakhir</label>
                <div class="w-full">
                        <input type="text" disabled class="form-input-disabled" value="{{ ($karyawan->pendidikan ?? '-') }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-0">Pendidikan Major</label>
                <div class="w-full">
                        <input type="text" disabled class="form-input-disabled" value="{{ ($karyawan->pendidikan_major ?? '-') }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">SK Pengangkatan</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ (!$karyawan->skangkat || $karyawan->skangkat == '') ? '-' : $karyawan->skangkat }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">Tanggal Pengangkatan</label>
                <div class="w-full">
                    {{-- <input type="text" disabled class="form-input-disabled" value="{{ $karyawan?->tanggal_pengangkat?->format('d F Y') ?? '-' }}"> --}}
                    <input type="text" disabled class="form-input-disabled" value="{{ (!$karyawan->tanggal_pengangkat || $karyawan->tanggal_pengangkat == '') ? '-' : date('d F Y', strtotime($karyawan->tanggal_pengangkat)) }}">
                </div>
            </div>
            @php
                $mulaKerja = Carbon::create($karyawan->tanggal_pengangkat);
                $waktuSekarang = Carbon::now();

                $hitung = $waktuSekarang->diff($mulaKerja);
                $masaKerja = $hitung->format('%y Tahun | %m Bulan | %d Hari');
            @endphp
            <div class="row ">
                <label class="w-2/4 mt-2">Masa Kerja</label>
                <div class="w-full">
                    @if (isset($karyawan->tanggal_pengangkat) != null)
                        <input type="text" disabled class="form-input-disabled" value="{{ $masaKerja }}">
                    @else
                        <input type="text" disabled class="form-input-disabled" value="-">
                    @endif
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Tunjangan Karyawan</h6>
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-0">Kartu Peserta Jamsostek (KPJ)</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->kpj ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-0">Jaminan Kesehatan Nasional (JKN)</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->jkn ?? '-' }}">
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium' : 'Gaji Pokok' }}</label>
                <div class="w-full">
                    @if (isset($karyawan->gj_pokok) != null)
                        <input type="text" disabled class="form-input-disabled" value="{{'Rp. '. number_format($karyawan->gj_pokok, 0, ",", ".") }}">
                    @else
                        <input type="text" disabled class="form-input-disabled" value="-">
                    @endif
                </div>
            </div>
            <div class="row ">
                <label class="w-2/4 mt-2">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium Penyesuaian' : 'Gaji Penyesuaian' }}</label>
                <div class="w-full">
                    @if (isset($karyawan->gj_penyesuaian) != null)
                        <input type="text" disabled class="form-input-disabled" value="{{ 'Rp. '. number_format($karyawan->gj_penyesuaian, 0, ",", ".") }}">
                    @else
                        <input type="text" disabled class="form-input-disabled" value="-">
                    @endif
                </div>
            </div>
            <br>
            {{-- {{$totalGaji}} --}}
            {{-- {{$karyawan->gj_pokok + $karyawan->gj_penyesuaian}} --}}
            @if (isset($tj))
                @foreach ($tj as $item)
                    <div class="row ">
                        <label class="w-2/4 mt-2"> {{ ($item->nama_tunjangan != 'DPP') ? 'Tunjangan ' . $no++ : 'Iuran'  }}</label>
                        <div class="w-full">
                            <input type="text" disabled class="form-input-disabled" value="{{ $item->nama_tunjangan }}">
                        </div>
                        <div class="w-full">
                            <input type="text" disabled class="form-input-disabled" value="Rp. {{ ($item->nama_tunjangan != 'DPP') ? number_format($item->nominal, 0, ",", ".") : number_format($dpp_perhitungan) }}" />
                        </div>
                    </div>
                    @php
                        if($item->nama_tunjangan != 'DPP'){
                            $totalGaji += $item->nominal;
                        }
                    @endphp
                @endforeach
            @endif
            <br>
            <div class="row ">
                <label for="" class="w-2/4 mt-2">Total Gaji</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($totalGaji, 0, ",", ".") }}" />
                </div>
            </div>
            @if ($potongan != null)
                @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi
                    || $potongan->kredit_pegawai || $potongan->iuran_ik)
                    <hr>
                    <div class="row m-0 ">
                        <div class="col-lg-12">
                            <h6>Data Potongan</h6>
                        </div>
                    </div>
                @endif
                @if ($potongan->kredit_koperasi)
                    @if ($potongan->kredit_koperasi > 0)
                        <div class="row ">
                            <label class="w-2/4 mt-0">Kredit Koperasi</label>
                            <div class="w-full">
                                <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($potongan->kredit_koperasi, 0, ",", ".") }}">
                            </div>
                        </div>
                    @endif
                @endif
                @if ($potongan->iuran_koperasi)
                    @if ($potongan->iuran_koperasi > 0)
                        <div class="row ">
                            <label class="w-2/4 mt-0">Iuran Koperasi</label>
                            <div class="w-full">
                                <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($potongan->iuran_koperasi, 0, ",", ".") }}">
                            </div>
                        </div>
                    @endif
                @endif
                @if ($potongan->kredit_pegawai)
                    @if ($potongan->kredit_pegawai > 0)
                        <div class="row ">
                            <label class="w-2/4 mt-0">Kredit Pegawai</label>
                            <div class="w-full">
                                <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($potongan->kredit_pegawai, 0, ",", ".") }}">
                            </div>
                        </div>
                    @endif
                @endif
                @if ($potongan->iuran_ik)
                    @if ($potongan->iuran_ik > 0)
                        <div class="row ">
                            <label class="w-2/4 mt-0">Iuran IK</label>
                            <div class="w-full">
                                <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($potongan->iuran_ik, 0, ",", ".") }}">
                            </div>
                        </div>
                    @endif
                @endif
                @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi
                    || $potongan->kredit_pegawai || $potongan->iuran_ik)
                    @php
                        $kredit_koperasi = $potongan->kredit_koperasi;
                        $iuran_koperasi = $potongan->iuran_koperasi;
                        $kredit_pegawai = $potongan->kredit_pegawai;
                        $iuran_ik = $potongan->iuran_ik;
                        $total_potongan = $kredit_koperasi + $iuran_koperasi + $kredit_pegawai + $iuran_ik;
                    @endphp
                    <br>
                    <div class="row ">
                        <label class="w-2/4 mt-0">Total Potongan</label>
                        <div class="w-full">
                            <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($total_potongan, 0, ",", ".") }}">
                        </div>
                    </div>
                @endif
            @endif

            @if ($karyawan->status == 'Kawin' && $suis != null)
                <hr>
                <div class="row m-0 mt-4">
                    <div class="col-lg-12">
                        <h6 class="row-title">Data Keluarga</h6>
                    </div>
                </div>
                <div class="">
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Status Pasangan</label>
                            </div>
                            <div class="w-full">
                                @if (isset($suis) != null)
                                <div class="">
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->enum ?? '-' }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Nama</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    @if (isset($suis) != null)
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->nama ?? '-' }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">SK Tunjangan</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    <input type="text" class="form-input-disabled" disabled value="{{ $suis->sk_tunjangan ?? '-' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Tanggal Lahir</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    @if (isset($suis) != null)
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->tgl_lahir != null ? date('d F Y' ,strtotime($suis->tgl_lahir)) : '-' }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Alamat</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    @if (isset($suis) != null)
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->alamat ?? '-' }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Pekerjaan</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    @if (isset($suis) != null)
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->pekerjaan ?? '-' }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="w-2/4">
                                <label class="">Jumlah Anak</label>
                            </div>
                            <div class="w-full">
                                <div class="">
                                    @if (isset($suis) != null)
                                        <input type="text" disabled class="form-input-disabled" value="{{ $suis->jml_anak }}">
                                    @else
                                        <input type="text" disabled class="form-input-disabled" value="-">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($data_anak) > 0)
                    <br>
                    @foreach ($data_anak as $key => $item)
                        @php
                            $index = ($key == 0) ? 'Pertama' : 'Kedua';
                        @endphp
                        <div class="row ">
                            <div class="row">
                                <div class="w-2/4">
                                    <label class="">Nama Anak {{ $index }}</label>
                                </div>
                                <div class="w-full">
                                    <div class="">
                                        <input type="text" class="form-input-disabled" disabled value="{{ $item->nama }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="row">
                                <div class="w-2/4">
                                    <label class="">Tanggal Lahir Anak {{ $index }}</label>
                                </div>
                                <div class="w-full">
                                    <div class="">
                                        <input type="text" class="form-input-disabled" disabled value="{{ date('d F Y', strtotime($item->tgl_lahir)) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="row">
                                <div class="w-2/4">
                                    <label class="w-2/4 mt-0">SK Tunjangan Anak {{ $index }}</label>
                                </div>
                                <div class="w-full">
                                    <div class="">
                                        <input type="text" class="form-input-disabled" disabled value="{{ $item->sk_tunjangan }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
            <hr>

            <div class="">
                <div class="col-lg-12">
                    <h6 class="text-2xl font-bold pb-5 pt-5">Histori</h6>
                </div>
                <div class="col-lg-12">
                    <p class="text-lg font-bold pb-5 pt-5">Histori Jabatan</p>
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="tables-even-or-odd border-none" id="pjs-table" style="width: 100%; word-break: break-all;">
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
                <div class="col-lg-12">
                    <p class="text-lg font-bold pb-5 pt-5">Histori Surat Peringatan</p>
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table-even-or-odd border-none" id="sp-table" style="width: 100%; word-break: break-all;">
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

            <div class="row m-3">
                <a href="{{route('karyawan.index')}}">
                    <button type="button" class="btn btn-primary">Kembali</button>
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('extraScript')
<script>
    $('#pjs-table').DataTable();
    $('#sp-table').DataTable();
</script>
@endpush
