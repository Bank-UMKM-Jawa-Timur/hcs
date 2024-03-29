@extends('layouts.app-template')

@section('modal')

    <div class="modal-layout hidden" id="foto-buku-nikah" tabindex="-1" aria-hidden="true">
        <div class="modal modal-sm">
            <div class="modal-head">
                <div class="flex justify-center">
                    <h2>Foto Buku Nikah</h2>
                </div>
                <button data-modal-dismiss="foto-buku-nikah" class="modal-close"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                @if ($doks)
                    @if ($doks->foto_buku_nikah)
                        <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_buku_nikah) }}"
                            class="w-full max-w-3xl border" />
                    @else
                        <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                    @endif
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                @endif
            </div>
            <div class="modal-footer to-right">
                <button data-modal-dismiss="foto-buku-nikah" class="btn btn-light" type="button">Tutup</button>
            </div>
        </div>
    </div>
    <div class="modal-layout hidden" id="foto-kartu-keluarga" tabindex="-1" aria-hidden="true">
        <div class="modal modal-sm">
            <div class="modal-head">
                <div class="flex justify-center">
                    <h2>Foto Kartu Keluarga</h2>
                </div>
                <button data-modal-dismiss="foto-kartu-keluarga" class="modal-close"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                @if ($doks)
                    @if ($doks->foto_kk)
                        <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_kk) }}"
                            class="w-full max-w-3xl border" />
                    @else
                        <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                    @endif
                @else
                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}" class="max-h-60 max-w-60" />
                @endif
            </div>
            <div class="modal-footer to-right">
                <button data-modal-dismiss="foto-kartu-keluarga" class="btn btn-light" type="button">Tutup</button>
            </div>
        </div>
    </div>

    <div class="modal-layout hidden" id="foto-ktp" tabindex="-1" aria-hidden="true">
        <div class="modal modal-sm">
            <div class="modal-head">
                <div class="heading">
                    <h2>Foto KTP</h2>
                </div>
                <button data-modal-dismiss="foto-ktp" class="modal-close"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                @if ($doks)
                    @if ($doks->foto_ktp)
                        <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_ktp) }}"
                            class="max-h-60 max-w-60" />
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
                <a href="{{ route('export-cv', $karyawan->nip) }}" target="__blank">
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
                            @if ($doks)
                                @if ($doks->foto_diri)
                                    <img src="{{ asset('/upload/dokumen/' . $doks->karyawan_id . '/' . $doks->foto_diri) }}"
                                        class="w-full max-w-xs rounded-lg" />
                                @else
                                    <img src="{{ asset('style/assets/img/img-not-found.jpg') }}"
                                        class="w-full max-w-xs rounded-lg" />
                                @endif
                            @else
                                <img src="{{ asset('style/assets/img/img-not-found.jpg') }}"
                                    class="w-full max-w-xs rounded-lg" />
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
                            <div class="flex items-center gap-5">
                                <div class="space-y-5">
                                    <label for="" class="font-normal text-gray-500">Foto KTP:</label><br>
                                    <button data-modal-id="foto-ktp" data-modal-toggle="modal"
                                        class="px-5 py-2 border bg-white text-sm font-semibold rounded-md flex items-center gap-2"><iconify-icon
                                            icon="material-symbols-light:image-outline"></iconify-icon> Lihat foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- data karyawan --}}
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
                                        ->where(
                                            'kd_divisi',
                                            $subDiv ? $subDiv->kd_divisi : $karyawan->bagian->kd_entitas,
                                        )
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
                                {{ !$karyawan->skangkat || $karyawan->skangkat == '' ? '-' : $karyawan->skangkat }}</p>
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
            {{-- No rek dab npwp --}}
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
                            <p class="font-semibold">
                                {{ $karyawan->status_ptkp ? ($karyawan->status_ptkp == 'TK' ? 'TK/0' : $karyawan->status_ptkp) : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- bpjs kesehatan dan ketenagakerjaan --}}
            <div class="bg-white border rounded-lg">
                <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
                    <h2 class="font-semibold">BPJS Kesehatan dan Ketenagakerjaan</h2>
                </div>
                <div class="p-5">
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center">
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Kartu Peserta Jamsostek (KPJ):</label>
                            <p class="font-semibold">{{ $karyawan->kpj ?? '-' }}</p>
                        </div>
                        <div class="space-y-5">
                            <label for="" class="font-normal text-gray-500">Jaminan Kesehatan Nasional
                                (JKN):</label>
                            <p class="font-semibold">{{ $karyawan->jkn ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- gaji dan tunjangan --}}
            <div class="bg-white border rounded-lg">
                <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
                    <h2 class="font-semibold">Data Gaji dan Tunjangan Karyawan</h2>
                </div>
                {{-- opsi 1 --}}
                {{-- <div class="p-5">
                    <div class="row row-total-tunjangan">
                        <h2 class="font-semibold text-gray-500">Data Gaji</h2>
                        <hr class="text-gray-500">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center ">
                            <div class="space-y-5">
                                <label for="" class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium' : 'Gaji Pokok' }}:</label>
                                @if (isset($karyawan->gj_pokok) != null)
                                    <p class="font-semibold">{{ 'Rp. ' . number_format($karyawan->gj_pokok, 0, ',', '.') }}</p>
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
                </div> --}}
                {{-- opsi 1 --}}
                {{-- opsi 2 --}}
                <div class="p-5">
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center">
                        <div class="space-y-5">
                            <label for=""
                                class="font-normal text-gray-500">{{ $karyawan->status_karyawan == 'Kontrak Perpanjangan' || $karyawan->status_karyawan == 'IKJP' ? 'Honorarium' : 'Gaji Pokok' }}:</label>
                            @if (isset($karyawan->gj_pokok) != null)
                                <p class="font-semibold">{{ 'Rp. ' . number_format($karyawan->gj_pokok, 0, ',', '.') }}</p>
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
                {{-- opsi 2 --}}


                {{-- tunjangan --}}
                @if (isset($tj))
                    {{-- opsi 1 --}}
                    {{-- <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5 grid-cols-1 p-5">
                        @foreach ($tj as $item)
                        @php
                            if ($item->nama_tunjangan != 'DPP') {
                                $totalGaji += $item->nominal;
                            }
                        @endphp
                            <div class="space-y-5">
                                <label for="" class="w-2/4 font-semibold">{{ $item->nama_tunjangan }}</label>
                                <div class="w-full">
                                    <input type="text" disabled class="form-input-disabled"
                                        value="Rp. {{ $item->nama_tunjangan != 'DPP' ? number_format($item->nominal, 0, ',', '.') : number_format($dpp_perhitungan) }}" />
                                </div>
                            </div>
                        @endforeach
                    </div> --}}
                    {{-- opsi 1 --}}
                    {{-- opsi 2 --}}
                    <div class="p-5">
                        <h2 class="font-semibold text-gray-500 mb-3">Data Tunjangan</h2>
                        {{-- <hr class="text-gray-500 mt-3 mb-3"> --}}
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center ">
                            @forelse ($tj as $item)
                            @php
                                if ($item->nama_tunjangan != 'DPP') {
                                    $totalGaji += $item->nominal;
                                }
                            @endphp
                            <div class="space-y-5">
                                <label for="" class="font-normal text-gray-500">{{ $item->nama_tunjangan }}</label>
                                <p class="font-semibold">Rp. {{ $item->nama_tunjangan != 'DPP' ? number_format($item->nominal, 0, ',', '.') : number_format($dpp_perhitungan) }}</p>
                            </div>
                            @empty
                            <div class="space-y-5">
                                Tidak ada data tunjangan.
                            </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- opsi 2 --}}
                @endif
                {{-- opsi 1 --}}
                {{-- <div class="p-5"> --}}
                    {{-- <div class="row row-total-tunjangan">
                        <label for="" class="w-2/4 font-semibold">Total Gaji</label>
                        <div class="w-full">
                            <input type="text" disabled class="form-input-disabled"
                                value="Rp. {{ number_format($totalGaji, 0, ',', '.') }}" />
                        </div>
                    </div> --}}
                {{-- </div> --}}
                    {{-- opsi 1 --}}
                    {{-- opsi 2 --}}
                    <div class="p-5">
                        <h2 class="font-semibold text-gray-500 mb-3">Total Gaji</h2>
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 items-center ">
                            <div class="space-y-5">
                                <p class="font-semibold">Rp. {{ number_format($totalGaji, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- opsi 2 --}}
            </div>
            {{-- potongan --}}
            @if ($potongan != null)
                @if ($potongan->kredit_koperasi || $potongan->iuran_koperasi || $potongan->kredit_pegawai || $potongan->iuran_ik)
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
                                        $total_potongan =
                                            $kredit_koperasi + $iuran_koperasi + $kredit_pegawai + $iuran_ik;
                                    @endphp
                                    <div class="row ">
                                        <label class="w-2/4 mt-0">Total Potongan</label>
                                        <div class="w-full">
                                            <p class="font-semibold">Rp. {{ number_format($total_potongan, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            {{-- data keluarga --}}
            @php
                $status_pernikahan = $karyawan->status;
                if (
                    $status_pernikahan == 'Kawin' ||
                    $status_pernikahan == 'Cerai' ||
                    $status_pernikahan == 'Cerai Mati' ||
                    $status_pernikahan == 'Janda' ||
                    $status_pernikahan == 'Duda'
                ) {
                    $have_anak = true;
                } else {
                    $have_anak = false;
                }
            @endphp
            @if ($have_anak == true && $suis != null)
                <div class="bg-white border rounded-lg w-full ">
                    <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
                        <h2 class="font-semibold">Data Keluarga</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-5 grid-cols-1">
                            <div class="row ">
                                <div class="space-y-5">
                                    <label class="w-2/4 mt-2">Foto Kartu Keluarga</label><br>
                                    <button data-modal-id="foto-kartu-keluarga" data-modal-toggle="modal"
                                        class="px-5 py-2 border bg-white text-sm font-semibold rounded-md flex items-center gap-2"><iconify-icon
                                            icon="material-symbols-light:image-outline"></iconify-icon> Lihat foto
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <div class="row">
                                    <div class="w-2/4">
                                        <label class="">Status Pasangan</label>
                                    </div>
                                    <div class="w-full">
                                        <div class="">
                                            @if (isset($suis) != null)
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
                                                <p class="font-semibold">
                                                    {{ $suis->tgl_lahir != null ? date('d F Y', strtotime($suis->tgl_lahir)) : '-' }}
                                                </p>
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
                            <div class="col-6 space-y-5">
                                <label class="w-2/4 mt-2">Foto Buku Nikah</label><br>
                                <button data-modal-id="foto-buku-nikah" data-modal-toggle="modal"
                                    class="px-5 py-2 border bg-white text-sm font-semibold rounded-md flex items-center gap-2"><iconify-icon
                                        icon="material-symbols-light:image-outline"></iconify-icon> Lihat foto </button>
                            </div>
                        </div>
                    </div>
                    @if (count($data_anak) > 0)
                        <div class="p-5 justify-center">
                            <h2 class="font-bold mb-2 text-gray-500 p-4">Data Anak</h2>
                            <table class="w-full border-separate border border-slate-500">
                                <thead class="text-gray-500">
                                    <tr>
                                        <th class="border border-slate-600 px-5 py-4">Nama ( Anak ke )</th>
                                        <th class="border border-slate-600 px-5 py-4">Tangal lahir</th>
                                        <th class="border border-slate-600 px-5 py-4">SK Tunjangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_anak as $key =>$item)
                                    <tr class="text-center">
                                        <td class="px-5 py-2 border border-slate-700">
                                            {{$item->nama}} ({{$loop->iteration}})
                                        </td>
                                        <td class="px-6 py-2 border border-slate-700">
                                            {{ date('d F Y', strtotime($item->tgl_lahir)) }}
                                        </td>
                                        <td class="px-6 py-2 border border-slate-700">
                                            {{$item->sk_tunjangan ? $item->sk_tunjangan : '-'}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
            {{-- history --}}
            <div class="bg-white border rounded-lg w-full {{ auth()->user()->hasRole('cabang') ? 'hidden' : '' }}">
                <div class="head p-5 rounded-tl-lg rounded-tr-lg border-b">
                    <h2 class="font-semibold">Histori</h2>
                </div>
                <div class="p-2">
                    <div class="col-lg-12 p-5  rounded-md">
                        <p class="text-lg font-bold pb-5 pt-5">Histori Jabatan</p>
                        <div class="table-responsive overflow-hidden pt-2">
                            <table class="tables-even-or-odd border-none" id="pjs-table"
                                style="width: 100%; word-break: break-all;">
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
                                            <td>{{ $data['berakhir'] != null ? date('d M Y', strtotime($data['berakhir'])) : '-' }}
                                            </td>
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
                            <table class="table-even-or-odd border-none" id="sp-table"
                                style="width: 100%; word-break: break-all;">
                                <thead>
                                    <tr>
                                        <th style="background-color: #F9F9F9; text-align: center;">#</th>
                                        <th style="background-color: #F9F9F9; text-align: center; min-width: 75px;">No SP
                                        </th>
                                        <th style="background-color: #F9F9F9; text-align: center; min-width: 100px;">
                                            Tanggal</th>
                                        <th style="background-color: #F9F9F9; text-align: center;">Pelanggaran</th>
                                        <th style="background-color: #F9F9F9; text-align: center; min-width: 125px;">Sanksi
                                        </th>
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
@endsection

@push('extraScript')
    <script>
        $('#pjs-table').DataTable();
        $('#sp-table').DataTable();
    </script>
@endpush
