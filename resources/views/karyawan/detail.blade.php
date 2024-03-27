@extends('layouts.app-template')

@section('modal')

<div class="modal-layout hidden" id="foto-diri" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="flex justify-center">
                <h2>Foto Diri</h2>
            </div>
            <button data-modal-dismiss="foto-diri"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-body">
            @if($doks)
            @if ($doks->foto_diri)
                <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_diri) }}"  class="max-h-60 max-w-60" />
            @else
                <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
            @endif
        @else
            <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
        @endif
        </div>
        <div class="modal-footer to-right">
            <button data-modal-dismiss="foto-diri" class="btn btn-light" type="button">Tutup</button>
        </div>
    </div>
</div>

<div class="modal-layout hidden" id="foto-ktp" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Foto KTP</h2>
            </div>
            <button data-modal-dismiss="foto-ktp"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-body">
            @if($doks)
            @if ($doks->foto_ktp)
                <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_ktp) }}"  class="max-h-60 max-w-60" />
            @else
                <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
            @endif
        @else
            <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
        @endif
        </div>
        <div class="modal-footer to-right">
            <button data-modal-dismiss="foto-ktp" class="btn btn-light" type="button">Tutup</button>
        </div>
    </div>
</div>

@endsection

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
        <div class="space-y-5">
            <div class="bg-white border rounded-lg">
                <div class="head p-5 pb-8 rounded-tl-lg rounded-tr-lg border-b">
                    <h2 class="font-semibold">Biodata Karyawan</h2>
                </div>
                <div class="p-5">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5  items-">
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

                <div class="profile-layout">
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="w-full max-w-xs rounded-lg" alt="">
                </div>
                <div class="space-y-5">
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">NIK:</label>
                        <p class="font-semibold text-sm">{{ $karyawan->nik }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Nama Karyawan:</label>
                        <p class="font-semibold text-sm">{{ $karyawan->nama_karyawan }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Tempat, Tanggal Lahir:</label>
                        <p class="font-semibold text-sm">{{ $karyawan->tmp_lahir }}, {{ ($karyawan->tgl_lahir != null) ? $karyawan->tgl_lahir->format('d F Y') : '-' }}</p>
                    </div>
                    @php
                        use Carbon\Carbon;
                        $tanggalLahir = Carbon::create($karyawan->tgl_lahir);
                        $waktuSekarang = Carbon::now();

                        $hitung = $waktuSekarang->diff($tanggalLahir);
                        $umur = $hitung->format('%y Tahun | %m Bulan | %d Hari');
                    @endphp
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Umur:</label>
                        <p class="font-semibold">{{ $umur }}</p>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Agama:</label>
                        <p class="font-semibold">{{ $karyawan?->agama?->agama ?? '-' }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Jenis Kelamin:</label>
                        <p class="font-semibold">{{ $karyawan->jk }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Status Pernikahan:</label>
                        <p class="font-semibold">{{ $karyawan->status ?? '-' }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Kewarganegaraan:</label>
                        <p class="font-semibold">{{ $karyawan->kewarganegaraan }}</p>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Alamat KTP:</label>
                        <p class="font-semibold">{{ $karyawan->alamat_ktp }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Alamat Sekarang:</label>
                        @php
                        if (!$karyawan->alamat_sek || $karyawan->alamat_sek == '') {
                            $alamatSek = '-';
                        } else {
                            $alamatSek = $karyawan->alamat_sek;
                        }
                    @endphp
                        <p class="font-semibold">{{ $alamatSek }}</p>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Foto Diri:</label><br>
                            <button data-modal-id="foto-diri" data-modal-toggle="modal" class="px-5 py-2 border bg-white text-sm font-semibold rounded-md flex items-center gap-2"><iconify-icon icon="material-symbols-light:image-outline"></iconify-icon> Lihat foto </button>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Foto KTP:</label><br>
                            <button data-modal-id="foto-ktp" data-modal-toggle="modal" class="px-5 py-2 border bg-white text-sm font-semibold rounded-md flex items-center gap-2"><iconify-icon icon="material-symbols-light:image-outline"></iconify-icon> Lihat foto </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white border rounded-lg">
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">No Rekening & NPWP</h2>
        </div>
        <div class="p-5">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center">
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Nomor Rekening:</label><br>
                    <p class="font-semibold">{{ $karyawan->no_rekening ?? '-' }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">NPWP:</label><br>
                    <p class="font-semibold">{{ npwp($karyawan->npwp) ?? '-' }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Status PTKP:</label><br>
                    <p class="font-semibold">{{ $karyawan->status_ptkp ? ($karyawan->status_ptkp == "TK" ? "TK/0" : $karyawan->status_ptkp) : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white border rounded-lg">
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">Data Karyawan</h2>
        </div>
        <div class="p-5">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5  ">
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">NIP:</label>
                        <p class="font-semibold">{{ $karyawan->nip }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Kantor:</label>
                        <p class="font-semibold">{{ $karyawan->entitas->type == 2 ? "Cabang {$karyawan->entitas->cab->nama_cabang}" : 'Pusat' }}</p>
                    </div>

                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Jabatan:</label>
                        <p class="font-semibold">{{ $karyawan->jabatan?->nama_jabatan }}</p>
                    </div>
                    @if(isset($karyawan->entitas->div))
                    <div class="space-y-5 {{ !$karyawan->entitas->div->nama_divisi ? 'hidden' : '' }}">
                        <label for="" class="font-normal text-gray-500">Divisi:</label>
                        <p class="font-semibold">{{ $karyawan->entitas->div->nama_divisi }}</p>
                    </div>
                    @endif

                            @if(isset($karyawan->entitas->subDiv))
                            <div class="row {{ !$karyawan->entitas->subDiv->nama_subdivisi ? 'hidden' : '' }}">
                                <label class="w-2/4 mt-2">Sub Divisi:</label>
                                <div class="w-full">
                                    <input type="text" disabled class="form-input-disabled" value="{{ $karyawan->entitas->subDiv->nama_subdivisi }}">
                                </div>
                            </div>
                        @endif


                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Pangkat:</label>
                        <p class="font-semibold">{{ $karyawan->panggol?->pangkat ?? '-' }}</p>
                    </div>
                    <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Golongan:</label>
                        <p class="font-semibold">{{ $karyawan->panggol?->golongan ?? '-' }}</p>
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
                     <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Divisi:</label>
                        <p class="font-semibold">{{ $divisi->nama_divisi }}</p>
                    </div>
                    @if ($subDiv)
                     <div class="space-y-5">
                        <label for="" class="font-normal text-gray-500">Sub Divisi:</label>
                        <p class="font-semibold">{{ $subDiv->nama_subdivisi }}</p>
                    </div>
                    @endif
                @endif
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Bagian:</label>
                    <p class="font-semibold">{{ $karyawan->bagian->nama_bagian }}</p>
                </div>
                @endif
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Status Karyawan:</label>
                    <p class="font-semibold">{{ $karyawan->status_karyawan}}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Status Jabatan:</label>
                    <p class="font-semibold">{{ $karyawan->status_jabatan}}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Tanggal Mulai:</label>
                    <p class="font-semibold">{{ $karyawan?->tgl_mulai?->format('d F Y') ?? '-'}}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Pendidikan Terakhir:</label>
                    <p class="font-semibold">{{ ($karyawan->pendidikan ?? '-') }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Pendidikan Major:</label>
                    <p class="font-semibold">{{ ($karyawan->pendidikan_major ?? '-') }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">SK Pengangkatan:</label>
                    <p class="font-semibold">{{ (!$karyawan->skangkat || $karyawan->skangkat == '') ? '-' : $karyawan->skangkat }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Tanggal Pengangkatan:</label>
                    <p class="font-semibold">{{ (!$karyawan->tanggal_pengangkat || $karyawan->tanggal_pengangkat == '') ? '-' : date('d F Y', strtotime($karyawan->tanggal_pengangkat)) }}</p>
                </div>
                @php
                $mulaKerja = Carbon::create($karyawan->tanggal_pengangkat);
                $waktuSekarang = Carbon::now();

                $hitung = $waktuSekarang->diff($mulaKerja);
                $masaKerja = $hitung->format('%y Tahun | %m Bulan | %d Hari');
            @endphp
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Masa Kerja:</label>
                    @if (isset($karyawan->tanggal_pengangkat) != null)
                    <p class="font-semibold">{{ $masaKerja }}</p>
                    @else
                    <p class="font-semibold">-</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white border rounded-lg">
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">Data Tunjangan Karyawan</h2>
        </div>
        <div class="p-5">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 p-5">
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Kartu Peserta Jamsostek (KPJ):</label>
                    <p class="font-semibold">{{ $karyawan->kpj ?? '-' }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">Jaminan Kesehatan Nasional (JKN):</label>
                    <p class="font-semibold">{{ $karyawan->jkn ?? '-' }}</p>
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium' : 'Gaji Pokok' }}:</label>
                    @if (isset($karyawan->gj_pokok) != null)
                        <p class="font-semibold">{{'Rp. '. number_format($karyawan->gj_pokok, 0, ",", ".") }}</p>
                    @else
                        <p class="font-semibold">-</p>
                    @endif
                </div>
                <div class="space-y-5">
                    <label for="" class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium Penyesuaian' : 'Gaji Penyesuaian' }}:</label>
                    @if (isset($karyawan->gj_penyesuaian) != null)
                        <p class="font-semibold">{{ 'Rp. '. number_format($karyawan->gj_penyesuaian, 0, ",", ".") }}</p>
                    @else
                        <p class="font-semibold">-</p>
                    @endif
                </div>

                    </div>
                </div>
                @if (isset($tj))
                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-5 grid-cols-1 p-5">


         @foreach ($tj as $item)
             <div class="row row-tunjangan hover:ring-2 hover:ring-theme-primary hover:bg-gray-50 transition-colors">
                  <label class="w-2/4 font-bold"> <iconify-icon icon="uil:info-circle"></iconify-icon> {{ ($item->nama_tunjangan != 'DPP') ? 'Tunjangan ' . $no++ : 'Iuran'  }}</label>
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
     </div>
     @endif
     <div class="p-5">
        <div class="row row-total-tunjangan">
                <label for="" class="w-2/4 font-semibold">Total Gaji</label>
                <div class="w-full">
                    <input type="text" disabled class="form-input-disabled" value="Rp. {{ number_format($totalGaji, 0, ",", ".") }}" />
                </div>
            </div>
     </div>
    </div>
    @if ($potongan != null)
    <div class="bg-white border rounded-lg w-full">
        @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi
        || $potongan->kredit_pegawai || $potongan->iuran_ik)
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">Data Potongan</h2>
        </div>
        @endif
        <div class="p-5">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                @if ($potongan->kredit_koperasi)
                @if ($potongan->kredit_koperasi > 0)
                    <div class="row ">
                        <label class="w-2/4 mt-0">Kredit Koperasi</label>
                        <div class="w-full">
                            <p class="font-semibold">Rp. {{ number_format($potongan->kredit_koperasi, 0, ",", ".") }}</p>
                        </div>
                    </div>
                @endif
            @endif
            @if ($potongan->iuran_koperasi)
                @if ($potongan->iuran_koperasi > 0)
                    <div class="row ">
                        <label class="w-2/4 mt-0">Iuran Koperasi</label>
                        <div class="w-full">
                            <p class="font-semibold">Rp. {{ number_format($potongan->iuran_koperasi, 0, ",", ".") }}</p>
                        </div>
                    </div>
                @endif
            @endif
            @if ($potongan->kredit_pegawai)
                @if ($potongan->kredit_pegawai > 0)
                    <div class="row ">
                        <label class="w-2/4 mt-0">Kredit Pegawai</label>
                        <div class="w-full">
                           <p class="font-semibold">Rp. {{ number_format($potongan->kredit_pegawai, 0, ",", ".") }}</p>
                    </div>
                    </div>
                @endif
            @endif
            @if ($potongan->iuran_ik)
                @if ($potongan->iuran_ik > 0)
                    <div class="row ">
                        <label class="w-2/4 mt-0">Iuran IK</label>
                        <div class="w-full">
                           <p class="font-semibold">Rp. {{ number_format($potongan->iuran_ik, 0, ",", ".") }}</p>
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
                <div class="row ">
                    <label class="w-2/4 mt-0">Total Potongan</label>
                    <div class="w-full">
                        <p class="font-semibold">Rp. {{ number_format($total_potongan, 0, ",", ".") }}</p>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
    @endif
    @if ($karyawan->status == 'Kawin' && $suis != null)
    <div class="bg-white border rounded-lg w-full">
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">Data Keluarga</h2>
        </div>
        <div class="p-5">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-5 grid-cols-1">
                <div class="col-lg-12 mt-2">
                    <div class="row">
                        <div class="w-2/4">
                            <label class="">Status Pasangan</label>
                        </div>
                        <div class="w-full">
                            @if (isset($suis) != null)
                            <div class="">
                                   <p class="font-semibold">{{ $suis->enum ?? '-' }}</p>
                                @else
                                <p class="font-semibold">-</p>
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
                                <p class="font-semibold">{{ $suis->nama ?? '-' }}</p>
                                @else
                                <p class="font-semibold">-</p>
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
                                <p class="font-semibold">{{ $suis->sk_tunjangan ?? '-' }}</p>
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
                                <p class="font-semibold">{{ $suis->tgl_lahir != null ? date('d F Y' ,strtotime($suis->tgl_lahir)) : '-' }}</p>
                                @else
                                <p class="font-semibold">-</p>
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
                                <p class="font-semibold">{{ $suis->alamat ?? '-' }}</p>
                                @else
                                <p class="font-semibold">-</p>
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
                                <p class="font-semibold">{{ $suis->pekerjaan ?? '-' }}</p>
                                @else
                                <p class="font-semibold">-</p>
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
                                <p class="font-semibold">{{ $suis->jml_anak }}</p>
                                @else
                                <p class="font-semibold">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <label class="w-2/4 mt-2">Foto Buku Nikah</label>
                    <div class="w-full mt-5">
                        @if($doks)
                            @if ($doks->foto_buku_nikah)
                                <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_buku_nikah) }}"   class="w-full max-w-3xl border"/>
                            @else
                                <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                            @endif
                        @else
                            <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                        @endif
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
            <div class="row ">
                <label class="w-2/4 mt-2">Foto Kartu Keluarga</label>
                <div class="w-full">
                    @if($doks)
                        @if ($doks->foto_kk)
                            <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_kk) }}"   class="w-full max-w-3xl border"/>
                        @else
                            <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                        @endif
                    @else
                        <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                    @endif
                </div>
            </div>
            </div>

    @endif
        </div>
    </div>
    <div class="bg-white border rounded-lg w-full {{auth()->user()->hasRole('cabang') ? 'hidden' : ''}}">
        <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
            <h2 class="font-semibold">Histori</h2>
        </div>
        <div class="p-2">
            <div class="col-lg-12 p-5  rounded-md">
                <p class="text-lg font-bold pb-5 pt-5">Histori Jabatan</p>
                <div class="table-responsive overflow-hidden pt-2">
                    <table class="tables-even-or-odd border-none" id="pjs-table" style="width: 100%; word-break: break-all;">
                        <thead>
                            <tr>
                                <th style="background-color: #F9F9F9; text-align: center;">#</th>
                                <th style="background-color: #F9F9F9; text-align: center;">No SK</th>
                                <th style="background-color: #F9F9F9; text-align: center;">Jabatan</th>
                                <th style="background-color: #F9F9F9; text-align: center;">Mulai</th>
                                <th style="background-color: #F9F9F9; text-align: center;">Berakhir</th>
                                <th style="background-color: #F9F9F9; text-align: center;">Status</th>
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
            <div class="col-lg-12 p-5  rounded-md">
                <p class="text-lg font-bold pb-5 pt-5">Histori Surat Peringatan</p>
                <div class="table-responsive overflow-hidden pt-2">
                    <table class="table-even-or-odd border-none" id="sp-table" style="width: 100%; word-break: break-all;">
                        <thead>
                            <tr>
                                <th style="background-color: #F9F9F9; text-align: center;">#</th>
                                <th style="background-color: #F9F9F9; text-align: center; min-width: 75px;">No SP</th>
                                <th style="background-color: #F9F9F9; text-align: center; min-width: 100px;">Tanggal</th>
                                <th style="background-color: #F9F9F9; text-align: center;">Pelanggaran</th>
                                <th style="background-color: #F9F9F9; text-align: center; min-width: 125px;">Sanksi</th>
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
    </div>
</div>
    </div>
    </div>
</div>
@endsection

@push('extraScript')
<script>
    $('#pjs-table').DataTable();
    $('#sp-table').DataTable();
</script>
@endpush
