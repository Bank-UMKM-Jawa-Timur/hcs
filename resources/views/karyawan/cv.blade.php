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
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- CSS Files -->

    <link href="{{ asset('style/assets/css/datatables.min.css') }}" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('style/assets/css/loading.css') }}"> --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        .vh-100 {
            height: 90vh !important;
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

<body class="overflow-y-scroll">
    <div class="text-left p-5">
        <p class="text-3xl uppercase font-semibold"> CV {{ $karyawan->nama_karyawan }}</p>
    </div>
    <div class="space-y-5 p-2">
        <div class="bg-white border rounded-lg break-after-page">
            <div class="head p-5  rounded-tl-lg rounded-tr-lg border-b">
                <h2 class="font-semibold">Biodata Karyawan</h2>
            </div>
            <div class="p-5">
                <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 ">
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
                        @if ($doks)
                            @if ($doks->foto_diri)
                                <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_diri) }}"
                                    class="w-full max-w-xs  rounded-lg" />
                            @else
                                <img src="{{ asset('style/assets/img/img-not-found.jpg') }}"
                                    class="w-full max-w-xs  rounded-lg" />
                            @endif
                        @else
                            <img src="{{ asset('style/assets/img/img-not-found.jpg') }}"
                                class="w-full max-w-xs  rounded-lg" />
                        @endif
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
                            <p class="font-semibold text-sm">{{ $karyawan->tmp_lahir }},
                                {{ $karyawan->tgl_lahir != null ? $karyawan->tgl_lahir->format('d F Y') : '-' }}</p>
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
                    </div>
                </div>
            </div>
        </div>
       {{-- <div class="print:block hidden">
        <br><br><br><br><br><br>
       </div> --}}

        <div class="bg-white border rounded-lg">
            <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
                <h2 class="font-semibold">Dokumen</h2>
            </div>
            <div class="p-5 grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 justify-center">
               <div class="space-y-5">
                <h2>Foto KTP</h2>
                @if ($doks)
                @if ($doks->foto_ktp)
                    <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_ktp) }}"
                        class="max-w-xs w-full" />
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                @endif
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                @endif
               </div>
               <div class="space-y-5">
                <h2>Foto KK</h2>
                @if ($doks)
                    @if ($doks->foto_kk)
                        <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_kk) }}"
                            class="w-full max-w-3xl border" />
                    @else
                        <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                    @endif
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                @endif
               </div>
               <div class="space-y-5">
                <h2>Foto Buku Nikah</h2>
                @if ($doks)
                    @if ($doks->foto_buku_nikah)
                        <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_buku_nikah) }}"
                            class="w-full max-w-3xl border" />
                    @else
                        <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                    @endif
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-w-xs w-full" />
                @endif
               </div>

            </div>
        </div>
        <div class="bg-white border rounded-lg  ">
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
                        <p class="font-semibold">
                            {{ $karyawan->status_ptkp ? ($karyawan->status_ptkp == 'TK' ? 'TK/0' : $karyawan->status_ptkp) : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
            <div class="bg-white border rounded-lg ">
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
                            <p class="font-semibold">
                                {{ $karyawan->entitas->type == 2 ? "Cabang {$karyawan->entitas->cab->nama_cabang}" : 'Pusat' }}
                            </p>
                        </div>

                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Jabatan:</label>
                            <p class="font-semibold">{{ $karyawan->jabatan?->nama_jabatan }}</p>
                        </div>
                        @if (isset($karyawan->entitas->div))
                            <div class="space-y-5 {{ !$karyawan->entitas->div->nama_divisi ? 'hidden' : '' }}">
                                <label for="" class="font-normal text-gray-500">Divisi:</label>
                                <p class="font-semibold">{{ $karyawan->entitas->div->nama_divisi }}</p>
                            </div>
                        @endif

                        @if (isset($karyawan->entitas->subDiv))
                            <div class="row {{ !$karyawan->entitas->subDiv->nama_subdivisi ? 'hidden' : '' }}">
                                <label class="w-2/4 mt-2">Sub Divisi:</label>
                                <div class="w-full">
                                    <input type="text" disabled class="form-input-disabled"
                                        value="{{ $karyawan->entitas->subDiv->nama_subdivisi }}">
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
                        @if ($karyawan->bagian)
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
                            <p class="font-semibold">{{ $karyawan->status_karyawan }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Status Jabatan:</label>
                            <p class="font-semibold">{{ $karyawan->status_jabatan }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Tanggal Mulai:</label>
                            <p class="font-semibold">{{ $karyawan?->tgl_mulai?->format('d F Y') ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Pendidikan Terakhir:</label>
                            <p class="font-semibold">{{ $karyawan->pendidikan ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Pendidikan Major:</label>
                            <p class="font-semibold">{{ $karyawan->pendidikan_major ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">SK Pengangkatan:</label>
                            <p class="font-semibold">
                                {{ !$karyawan->skangkat || $karyawan->skangkat == '' ? '-' : $karyawan->skangkat }}
                            </p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Tanggal Pengangkatan:</label>
                            <p class="font-semibold">
                                {{ !$karyawan->tanggal_pengangkat || $karyawan->tanggal_pengangkat == '' ? '-' : date('d F Y', strtotime($karyawan->tanggal_pengangkat)) }}
                            </p>
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
                            <label for="" class="font-normal text-gray-500">Kartu Peserta Jamsostek
                                (KPJ):</label>
                            <p class="font-semibold">{{ $karyawan->kpj ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Jaminan Kesehatan Nasional
                                (JKN):</label>
                            <p class="font-semibold">{{ $karyawan->jkn ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for=""
                                class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium' : 'Gaji Pokok' }}:</label>
                            @if (isset($karyawan->gj_pokok) != null)
                                <p class="font-semibold">{{ 'Rp. ' . number_format($karyawan->gj_pokok, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="font-semibold">-</p>
                            @endif
                        </div>
                        <div class="space-y-5">
                            <label for=""
                                class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium Penyesuaian' : 'Gaji Penyesuaian' }}:</label>
                            @if (isset($karyawan->gj_penyesuaian) != null)
                                <p class="font-semibold">
                                    {{ 'Rp. ' . number_format($karyawan->gj_penyesuaian, 0, ',', '.') }}</p>
                            @else
                                <p class="font-semibold">-</p>
                            @endif
                        </div>

                    </div>
                </div>
                @if (isset($tj))
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-5 grid-cols-1 p-5">


                        @foreach ($tj as $item)
                            <div
                                class="row row-tunjangan hover:ring-2 hover:ring-theme-primary hover:bg-gray-50 transition-colors">
                                <label class="w-2/4 font-bold"> <iconify-icon icon="uil:info-circle"></iconify-icon>
                                    {{ $item->nama_tunjangan != 'DPP' ? 'Tunjangan ' . $no++ : 'Iuran' }}</label>
                                <div class="w-full">
                                    <input type="text" disabled class="form-input-disabled"
                                        value="{{ $item->nama_tunjangan }}">
                                </div>
                                <div class="w-full">
                                    <input type="text" disabled class="form-input-disabled"
                                        value="Rp. {{ $item->nama_tunjangan != 'DPP' ? number_format($item->nominal, 0, ',', '.') : number_format($dpp_perhitungan) }}" />
                                </div>
                            </div>
                            @php
                                if ($item->nama_tunjangan != 'DPP') {
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
                            <input type="text" disabled class="form-input-disabled"
                                value="Rp. {{ number_format($totalGaji, 0, ',', '.') }}" />
                        </div>
                    </div>
                </div>
            </div>
            @if ($potongan != null)
                <div class="bg-white border rounded-lg w-full">
                    @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi || $potongan->kredit_pegawai || $potongan->iuran_ik)
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
                                            <p class="font-semibold">Rp.
                                                {{ number_format($potongan->kredit_koperasi, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($potongan->iuran_koperasi)
                                @if ($potongan->iuran_koperasi > 0)
                                    <div class="row ">
                                        <label class="w-2/4 mt-0">Iuran Koperasi</label>
                                        <div class="w-full">
                                            <p class="font-semibold">Rp.
                                                {{ number_format($potongan->iuran_koperasi, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($potongan->kredit_pegawai)
                                @if ($potongan->kredit_pegawai > 0)
                                    <div class="row ">
                                        <label class="w-2/4 mt-0">Kredit Pegawai</label>
                                        <div class="w-full">
                                            <p class="font-semibold">Rp.
                                                {{ number_format($potongan->kredit_pegawai, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($potongan->iuran_ik)
                                @if ($potongan->iuran_ik > 0)
                                    <div class="row ">
                                        <label class="w-2/4 mt-0">Iuran IK</label>
                                        <div class="w-full">
                                            <p class="font-semibold">Rp.
                                                {{ number_format($potongan->iuran_ik, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi || $potongan->kredit_pegawai || $potongan->iuran_ik)
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
                                        <p class="font-semibold">Rp.
                                            {{ number_format($total_potongan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
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
