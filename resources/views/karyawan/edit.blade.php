@php
    $arrayPendidikan = ['SD', 'SMP/SLTP', 'SLTA', 'SMA/SLTA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'D4/S1', 'S1', 'S2', 'S3'];
@endphp
@extends('layouts.app-template')
@include('karyawan.modal.dokument')
@push('script')
    <script>
        $(document).ready(function() {
            var inputRupiah = $('.rupiah-potongan')
            $.each(inputRupiah, function(i, obj) {
                $(this).val(formatRupiah(obj.value))
            })
        })
        // function formatrupiah(angka, prefix) {
        //     var number_string = angka.replace(/[^,\d]/g, '').toString(),
        //         split = number_string.split(','),
        //         sisa = split[0].length % 3,
        //         rupiah = split[0].substr(0, sisa),
        //         ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        //     // tambahkan titik jika yang di input sudah menjadi angka ribuan
        //     if (ribuan) {
        //         separator = sisa ? '.' : '';
        //         rupiah += separator + ribuan.join('.');
        //     }

        //     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        //     return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        // }
    </script>
@endpush
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                @can('manajemen karyawan - data karyawan - edit karyawan')
                    <h2 class="text-2xl font-bold tracking-tighter">Edit Data Karyawan</h2>
                @elsecan('manajemen karyawan - data karyawan - edit karyawan - edit potongan')
                    <h2 class="text-2xl font-bold tracking-tighter">Edit Potongan Karyawan</h2>
                @endcan
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500">Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Edit</a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages space-y-5">
        @can('manajemen karyawan - data karyawan - edit karyawan')
            <div class="card sticky top-20">
                <div>
                    <div
                        class="tab-menu relative after:absolute after:inset-x-0 after:top-1/2 after:block after:h-0.5 after:-translate-y-1/2 after:rounded-lg after:bg-gray-100">
                        <ol class="relative z-10 flex justify-between text-sm font-medium text-gray-500">
                            <li class="flex items-center gap-2 bg-white p-2 tab-btn active cursor-pointer" data-tab="biodata" data-current="0">
                                <span class="count-circle h-6 w-6 rounded-full text-white text-center text-[10px]/6 font-bold">
                                    1
                                </span>

                                <span class="hidden sm:block"> Biodata Karyawan </span>
                            </li>

                            <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="data-karyawan" data-current="1">
                                <span class="count-circle h-6 w-6 rounded-full text-center text-[10px]/6 font-bold text-white">
                                    2
                                </span>

                                <span class="hidden sm:block"> Data Karyawan </span>
                            </li>

                            <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="keluarga" data-current="2">
                                <span class="count-circle h-6 w-6 rounded-full  text-white text-center text-[10px]/6 font-bold">
                                    3
                                </span>

                                <span class="hidden sm:block"> Data Keluarga</span>
                            </li>
                            <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="{{ auth()->user()->hasRole('cabang') ? 'potongan' : 'tunjangan' }}" data-current="3">
                                <span class="count-circle h-6 w-6 rounded-full  text-white text-center text-[10px]/6 font-bold">
                                    4
                                </span>

                                @if(auth()->user()->hasRole('cabang'))
                                    <span class="hidden sm:block"> Data Potongan</span>
                                @else
                                    <span class="hidden sm:block"> Data Tunjangan</span>
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        @elsecan('manajemen karyawan - data karyawan - edit karyawan - edit potongan')
        @endcan
        <form action="{{ route('karyawan.update', $data->nip) }}" method="POST" enctype="multipart/form-data"
              name="karyawan" class="input-box">
            @csrf
            @method('PUT')
            <input type="hidden" name="idTkDeleted" id="idTkDeleted">
            <input type="hidden" name="idPotDeleted" id="idPotDeleted">
            <input type="hidden" name="idAnakDeleted" id="idAnakDeleted">
            {{-- biodata karyawan --}}
            @can('manajemen karyawan - data karyawan - edit karyawan')
                <div class="card tab-pane active" id="biodata">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Biodata Karyawan</h2>
                    </div>
                    <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NIK<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('nik') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="nik" id="" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}} value="{{ old('nik', $data->nik) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Nama Karyawan<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('nama') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}"
                                       name="nama" id="" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}} value="{{ old('nama', $data->nama_karyawan) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Tempat Lahir<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('tmp_lahir') is-invalid @enderror form-input"
                                       name="tmp_lahir" id="" value="{{ old('tmp_lahir', $data->tmp_lahir) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Tanggal Lahir<span class="text-red-500">*</span></label>
                                <input type="date" class="@error('tgl_lahir') is-invalid @enderror form-input"
                                       name="tgl_lahir" id="" value="{{ old('tgl_lahir', $data->tgl_lahir) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Agama<span class="text-red-500">*</span></label>
                                <select name="agama" id="" class="@error('agama') is-invalid @enderror form-input">
                                    <option value="-">--- Pilih ---</option>
                                    @foreach ($agama as $item)
                                        <option value="{{ $item->kd_agama }}"
                                            {{ $data->kd_agama == $item->kd_agama ? 'selected' : '' }}>{{ $item->agama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Jenis Kelamin<span class="text-red-500">*</span></label>
                                <select name="jk" id="" class="@error('jk') is-invalid @enderror form-input">
                                    <option value="-">--- Pilih ---</option>
                                    <option value="Laki-laki" {{ $data->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ $data->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status pernikahan<span class="text-red-500">*</span></label>
                                <select name="status_pernikahan" id="status"
                                        class="@error('status_pernikahan') is-invalid @enderror form-input">
                                    <option value="">--- Pilih ---</option>>
                                    <option value="Kawin" @selected($data->status == 'Kawin')>Kawin</option>
                                    <option value="Belum Kawin" @selected($data->status == 'Belum Kawin')>Belum Kawin</option>
                                    <option value="Cerai" @selected($data->status == 'Cerai')>Cerai</option>
                                    <option value="Cerai Mati" @selected($data->status == 'Cerai Mati')>Cerai Mati</option>
                                    <option value="Janda" @selected($data->status == 'Janda')>Janda</option>
                                    <option value="Duda" @selected($data->status == 'Duda')>Duda</option>
                                    <option value="Tidak Diketahui" @selected($data->status == 'Tidak Diketahui')>Tidak Diketahui</option>
                                </select>
                            </div>
                            <input type="hidden" name="id_pasangan" value="{{ $is?->id }}">
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Kewarganegaraan<span class="text-red-500">*</span></label>
                                <select name="kewarganegaraan" id=""
                                        class="@error('kewarganegaraan') is-invalid @enderror form-input">
                                    <option value="-">--- Pilih ---</option>
                                    <option value="WNI" {{ $data->kewarganegaraan == 'WNI' ? 'selected' : '' }}>WNI
                                    </option>
                                    <option value="WNA" {{ $data->kewarganegaraan == 'WNA' ? 'selected' : '' }}>WNA
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <div class="flex justify-between">
                                    <div>
                                        <label for="">Foto Diri<span class="text-red-500">*</span></label>
                                        <span class="text-theme-primary">.jpg, .jpeg, .png, .webp</span>
                                        <span class="text-red-400">(maks 2Mb)</span>
                                    </div>
                                    <div>
                                        @if ($dokumen)
                                            @if ($dokumen->foto_diri)
                                                <a href="javascript:void(0)" class="ms-3 dokument text-yellow-500 underline"
                                                    data-modal-target="modalDokument" data-modal-toggle="modalDokument"
                                                    data-tittle="Foto Diri"
                                                    data-filepath="{{ asset('/upload/dokumen/' . $dokumen->karyawan_id . '/' . $dokumen->foto_diri) }}"
                                                >Preview</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <input type="file" class="@error('foto_diri') is-invalid @enderror form-input only-image limit-size-2" name="foto_diri" id="foto_diri" accept="image/png, image/jpeg">
                            </div>
                            <span class="text-red-500 m-0 error-msg message-image" style="display: none"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <div class="d-flex">
                                    <div class="flex justify-between">
                                        <div>
                                            <label for="">Foto KTP</label>
                                            <span class="text-theme-primary">.jpg, .jpeg, .png, .webp</span>
                                            <span class="text-red-400">(maks 2Mb)</span>
                                        </div>
                                        <div>
                                            @if ($dokumen)
                                                @if ($dokumen->foto_ktp)
                                                    <a href="javascript:void(0)" class="ms-3 dokument text-yellow-500 underline"
                                                        data-modal-target="modalDokument" data-modal-toggle="modalDokument"
                                                        data-tittle="Foto KTP"
                                                        data-filepath="{{ asset('/upload/dokumen/' . $dokumen->karyawan_id . '/' . $dokumen->foto_ktp) }}"
                                                    >Preview</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="file" class="@error('foto_ktp') is-invalid @enderror  form-input only-image limit-size-2" name="foto_ktp" id="foto_ktp" accept="image/png, image/jpeg">
                            </div>
                            <span class="text-red-500 m-0 error-msg message-image" style="display: none"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5">
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Alamat KTP<span class="text-red-500">*</span></label>
                                <textarea name="alamat_ktp" id="" class="@error('alamat_ktp') is-invalid @enderror form-input">{{ old('alamat_ktp', $data->alamat_ktp) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Alamat sekarang</label>
                                <textarea name="alamat_sek" id="" class="form-input">{{ old('alamat_sek', $data->alamat_sek) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card tab-pane" id="data-karyawan">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Data Karyawan</h2>
                    </div>
                    <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NIP<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('nip') is-invalid @enderror  form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="nip" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}} id="nip" value="{{ old('nip', $data->nip) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Nomor Rekening</label>
                                <input type="number" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="no_rek"
                                       value="{{ old('no_rek', $data->no_rekening) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NPWP</label>
                                <input type="number" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="npwp"
                                       value="{{ old('npwp', $data->npwp) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        @if (auth()->user()->hasRole('cabang'))
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="is">Status PTKP<span class="text-red-500">*</span></label>
                                    <input type="text" readonly class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="status_ptkp" value="{{$data->status_ptkp ? $data->status_ptkp : '-'}}">
                                </div>
                            </div>
                        @else
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="is">Status PTKP<span class="text-red-500">*</span></label>
                                    <select name="status_ptkp" id="" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" {{ auth()->user()->hasRole('cabang') ? 'readonly' : '' }}>
                                        <option value="">--- Pilih ---</option>
                                        <option {{ $data?->status_ptkp == 'K/0' ? 'selected' : '' }} value="K/0">K/0</option>
                                        <option {{ $data?->status_ptkp == 'K/1' ? 'selected' : '' }} value="K/1">K/1</option>
                                        <option {{ $data?->status_ptkp == 'K/2' ? 'selected' : '' }} value="K/2">K/2</option>
                                        <option {{ $data?->status_ptkp == 'K/3' ? 'selected' : '' }} value="K/3">K/3</option>
                                        <option {{ $data?->status_ptkp == 'K/I/0' ? 'selected' : '' }} value="K/I/0">K/I/0</option>
                                        <option {{ $data?->status_ptkp == 'K/I/1' ? 'selected' : '' }} value="K/I/1">K/I/1</option>
                                        <option {{ $data?->status_ptkp == 'K/I/2' ? 'selected' : '' }} value="K/I/2">K/I/2</option>
                                        <option {{ $data?->status_ptkp == 'K/I/3' ? 'selected' : '' }} value="K/I/3">K/I/3</option>
                                        <option {{ $data?->status_ptkp == 'TK/0' ? 'selected' : '' }} value="TK/0">TK/0</option>
                                        <option {{ $data?->status_ptkp == 'TK/1' ? 'selected' : '' }} value="TK/1">TK/1</option>
                                        <option {{ $data?->status_ptkp == 'TK/2' ? 'selected' : '' }} value="TK/2">TK/2</option>
                                        <option {{ $data?->status_ptkp == 'TK/3' ? 'selected' : '' }} value="TK/3">TK/3</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        @php
                            foreach ($jabatan as $key => $value) {
                                if ($data->kd_jabatan == $value->kd_jabatan) {
                                    $data_jabatan = $value->kd_jabatan . ' - ' . $value->nama_jabatan;
                                }
                            }
                        @endphp
                        @if ( auth()->user()->hasRole('cabang'))
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="">Jabatan</label>
                                    <input type="hidden" name="jabatan" id="jabatan" value="{{$data->kd_jabatan}}">
                                    <input type="text" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" value="{{$data_jabatan}}" readonly>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="">Jabatan<span class="text-red-500">*</span></label>
                                    <select name="jabatan" id="jabatan" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($jabatan as $item)
                                            <option value="{{ $item->kd_jabatan }}"
                                                {{ $data->kd_jabatan == $item->kd_jabatan ? 'selected' : '' }}>
                                                {{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="kantor">Kantor</label>
                                <select name="kantor" id="kantor" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                    <option value="">--- Pilih Kantor ---</option>
                                    <option value="1">Kantor Pusat</option>
                                    <option value="2">Kantor Cabang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="kantor_row1">

                        </div>
                        <div class="col-md-6" id="kantor_row2">

                        </div>
                        <div class="col-md-6" id="kantor_row3">

                        </div>

                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Pangkat Dan Golongan</label>
                                <select name="panggol" id=""
                                        class="@error('panggol') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($panggol as $item)
                                        <option value="{{ $item->golongan }}"
                                            {{ $data->kd_panggol == $item->golongan ? 'selected' : '' }}>
                                            {{ $item->golongan }} - {{ $item->pangkat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status Jabatan</label>
                                <select name="status_jabatan" id=""
                                        class="@error('status_jabatan') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                    <option value="">--- Pilih ---</option>
                                    <option value="Definitif" {{ $data->status_jabatan == 'Definitif' ? 'selected' : '' }}>
                                        Definitif</option>
                                    <option value="Penjabat" {{ $data->status_jabatan == 'Penjabat' ? 'selected' : '' }}>
                                        Penjabat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Keterangan Jabatan</label>
                                <input type="text" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="ket_jabatan"  {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}
                                       value="{{ old('ket_jabatan', $data->ket_jabatan) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">KPJ<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('kpj') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="kpj"
                                       value="{{ old('kpj', $data->kpj) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">JKN<span class="text-red-500">*</span></label>
                                <input type="text" class="@error('jkn') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="jkn"
                                       value="{{ old('jkn', $data->jkn) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Honorarium<span class="text-red-500">*</span></label>
                                <input type="text" id="gj_pokok"
                                       class="@error('gj_pokok') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="gj_pokok"
                                       value="{{ old('gj_pokok', $data->gj_pokok) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Honorarium Penyesuaian</label>
                                <input type="text" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" id="gj_penyesuaian" name="gj_penyesuaian"
                                       value="{{ old('gj_penyesuaian', $data->gj_penyesuaian) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        @if (auth()->user()->hasRole('cabang'))
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label for="">Status Karyawan<span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" name="status_karyawan" value="{{$data->status_karyawan ? $data->status_karyawan : ''}}" readonly>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label for="">Status Karyawan<span class="text-red-500">*</span></label>
                                    <select name="status_karyawan" id=""
                                            class="@error('status_karyawan') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}">
                                        <option value="">--- Pilih ---</option>
                                        <option value="Tetap" {{ $data->status_karyawan == 'Tetap' ? 'selected' : '' }}>Tetap
                                        </option>
                                        <option value="IKJP" {{ $data->status_karyawan == 'IKJP' ? 'selected' : '' }}>IKJP
                                        </option>
                                        <option value="Kontrak Perpanjangan"
                                            {{ $data->status_karyawan == 'Kontrak Perpanjangan' ? 'selected' : '' }}>Kontrak
                                            Perpanjangan</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Tanggal Mulai<span class="text-red-500">*</span></label>
                                <input type="date" class="@error('tgl_mulai') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}"
                                       name="tgl_mulai" value="{{ old('tgl_mulai', $data->tgl_mulai ?? null) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">SK Pengangkatan</label>
                                <input type="text" class="@error('skangkat') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}"
                                       name="skangkat" value="{{ old('skangkat', $data->skangkat) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Tanggal Pengangkatan</label>
                                <input type="date" class="@error('tanggal_pengangkat') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}"
                                       name="tanggal_pengangkat"
                                       value="{{ old('tanggal_pengangkat', $data->tanggal_pengangkat) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                        @if (auth()->user()->hasRole('cabang'))
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label for="">Pendidikan</label>
                                    <input type="text" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" readonly value="{{$data->pendidikan ? $data->pendidikan : '-'}}">
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label for="pendidikan">Pendidikan</label>
                                    <select name="pendidikan" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" id="" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($arrayPendidikan as $item)
                                            <option value="{{ $item }}"
                                                    @if ($data?->pendidikan == $item) selected @endif>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="pendidikan_major">Pendidikan Major</label>
                                <input type="text" class="@error('pendidikan_major') is-invalid @enderror form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}"
                                       name="pendidikan_major" value="{{ old('pendidikan_major', $data?->pendidikan_major) }}" {{auth()->user()->hasRole('cabang') ? 'readonly' : ''}}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card tab-pane" id="keluarga">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Data Keluarga</h2>
                    </div>
                    <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                        <div class="col-md-4">
                            <div class="input-box">
                                <div class="flex justify-between">
                                    <div>
                                        <label for="">Foto KK</label>
                                        <span class="text-theme-primary">.jpg, .jpeg, .png, .webp</span>
                                        <span class="text-red-400">(maks 2Mb)</span>
                                    </div>
                                    <div>
                                        @if ($dokumen)
                                            @if ($dokumen->foto_kk)
                                                <a href="javascript:void(0)" class="ms-3 dokument text-yellow-500 underline"
                                                    data-modal-target="modalDokument" data-modal-toggle="modalDokument"
                                                    data-tittle="Foto Kartu Keluarga"
                                                    data-filepath="{{ asset('/upload/dokumen/' . $dokumen->karyawan_id . '/' . $dokumen->foto_kk) }}"
                                                >Preview</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <input type="file" name="foto_kk" class="form-input only-image limit-size-2" id="foto_kk" accept="image/png, image/jpeg, image/jpg, image/webp">
                            </div>
                            <span class="text-red-500 m-0 error-msg message-image" style="display: none"></span>
                        </div>
                    </div>
                            <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 {{$data->status != "Belum Kawin" && $data->status != "Tidak Diketahui" && $data->status != "" ? '' : 'hidden'}}" id="parent-family">
                                {{-- data pasangan --}}
                                <div class="col-md-4 pasangan">
                                    <div class="input-box">
                                        <label for="is">Pasangan</label>
                                        <select name="is" id="is" class="form-input">
                                            <option value="">--- Pilih ---</option>
                                            <option {{ $is?->enum == 'Suami' ? 'selected' : '' }} value="Suami">Suami</option>
                                            <option {{ $is?->enum == 'Istri' ? 'selected' : '' }} value="Istri">Istri</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pasangan">
                                    <div class="input-box">
                                        <label for="is_nama">Nama</label>
                                        <input type="text" name="is_nama" id="is_nama" value="{{ $is?->nama }}"
                                            class="form-input">
                                    </div>
                                </div>
                                <div class="col-md-4 pasangan">
                                    <div class="input-box">
                                        <label for="sk_tunjangan_is">SK Tunjangan</label>
                                        <input type="text" name="sk_tunjangan_is" id="sk_tunjangan_is"
                                            value="{{ $is?->sk_tunjangan }}" class="form-input">
                                    </div>
                                </div>
                                <div class="col-md-6 pasangan">
                                    <div class="input-box">
                                        <label for="is_tgl_lahir">Tanggal Lahir</label>
                                        <input type="date" name="is_tgl_lahir" class="form-input" value="{{ $is?->tgl_lahir }}"
                                            id="is_tgl_lahir">
                                    </div>
                                </div>
                                <div class="col-md-6 pasangan">
                                    <div class="input-box">
                                        <label for="is_pekerjaan">Pekerjaan</label>
                                        <input type="text" class="form-input" name="is_pekerjaan" id="is_pekerjaan"
                                            value="{{ $is?->pekerjaan }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-box">
                                        <div class="flex justify-between">
                                            <div>
                                                <label for="">Foto Buku Nikah</label>
                                                <span class="text-theme-primary">.jpg, .jpeg, .png, .webp</span>
                                                <span class="text-red-400">(maks 2Mb)</span>
                                            </div>
                                            <div>
                                                @if ($dokumen)
                                                    @if ($dokumen->foto_buku_nikah)
                                                        <a href="javascript:void(0)" class="ms-3 dokument text-yellow-500 underline"
                                                            data-modal-target="modalDokument" data-modal-toggle="modalDokument"
                                                            data-tittle="Foto Buku Nikah"
                                                            data-filepath="{{ asset('/upload/dokumen/' . $dokumen->karyawan_id . '/' . $dokumen->foto_buku_nikah) }}"
                                                        >Preview</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <input type="file" name="foto_buku_nikah" class="form-input only-image limit-size-2" id="foto_buku_nikah" accept="image/png, image/jpeg, image/jpg, image/webp">
                                    </div>
                                    <span class="text-red-500 m-0 error-msg message-image" style="display: none"></span>
                                </div>
                                {{-- data anak --}}
                                <div class="col-md-6">
                                    <div class="input-box">
                                        <label for="is_jumlah_anak">Jumlah Anak</label>
                                        <div class="flex gap-3">
                                            <input type="number" class="form-input form-input-disabled" name="is_jml_anak" id="is_jml_anak"
                                                value="{{ count($data_anak) }}" readonly>
                                                <button type="button" class="btn btn-success" id="add-row-anak"><i class="ti ti-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-3 space-y-5 " id="row_anak">
                                    @if (count($data_anak) > 0)
                                        @foreach ($data_anak as $key => $item)
                                            <div id="anak-{{ $key }}" class="child-div">
                                                <h6 class="font-bold text-lg mb-5 ket-anak">Data Anak {{ $key + 1 }}</h6>
                                                <div class="grid col-span-5 w-full lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 form-anak" data-anak="{{ $key }}">
                                                    <input type="hidden" name="id_anak[]" value="{{ $item->id }}">
                                                    <div class="col-md-6">
                                                        <div class="input-box">
                                                            <label for="nama_anak">Nama Anak</label>
                                                            <input type="text" class="form-input" value="{{ $item->nama }}"
                                                                name="nama_anak[]">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-box">
                                                            <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                                                            <input type="date" class="form-input" name="tgl_lahir_anak[]"
                                                                value="{{ $item->tgl_lahir }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 content-center">
                                                        <div class="input-box">
                                                            <label for="sk_tunjangan_anak_{{ $key }}">SK Tunjangan</label>
                                                            <div class="flex">
                                                                <span class="inline-flex items-center px-2 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                    <input onchange="onCheckSKAnak(this)" type="checkbox" id="check_sk_anak_{{ $key }}" name="check_sk_anak[]" class="check_sk_anak w-8 h-8" @if ($item->sk_tunjangan) checked @endif @if (count($data_anak) > 2 && !$item->sk_tunjangan) disabled @endif>
                                                                </span>
                                                                <input type="text" onchange="cekSkAnak(this)" id="sk_tunjangan_anak_{{ $key }}"
                                                                        class="form-input sk-anak @if (count($data_anak) > 2 && !$item->sk_tunjangan) form-input-disabled @endif"
                                                                        name="sk_tunjangan_anak[]" value="{{ $item->sk_tunjangan }}" @if (count($data_anak) > 2 && !$item->sk_tunjangan) readonly @endif>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="flex items-center mt-7 gap-3">
                                                            <div>
                                                                <button class="btn btn-success btn-add-anak" type="button">
                                                                    <i class="ti ti-plus"></i>
                                                                </button>
                                                            </div>
                                                            <div>
                                                                <button class="btn btn-danger btn-remove-anak" type="button" data-parent-anak="{{ $key }}" data-id-anak="{{ $item->id }}">
                                                                    <i class="ti ti-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-6 col-span-3">
                                    <div class="input-box">
                                        <label for="is_alamat">Alamat</label>
                                        <textarea name="is_alamat" class="form-input" id="is_alamat">{{ $is?->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>
                </div>
                @if(auth()->user()->hasRole('cabang'))
                    <div class="card tab-pane space-y-5" id="potongan">
                        <div class="head-card border-b pb-5">
                            <h2 class="font-bold text-lg">Data Potongan</h2>
                        </div>
                        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 "
                             id="row_potongan">
                            <div class="col col-md-6 col-sm-6">
                                <div class="input-box">
                                    <label for="is_nama">Kredit Koperasi</label>
                                    <input type="text" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi"
                                           class="form-input rupiah-potongan"
                                           value="{{ old('potongan_kredit_koperasi', $data->potongan?->kredit_koperasi ? $data->potongan->kredit_koperasi : '0') }}">
                                </div>
                            </div>
                            <div class="col col-md-6 col-sm-6">
                                <div class="input-box">
                                    <label for="is_nama">Iuran Koperasi</label>
                                    <input type="text" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi"
                                           class="form-input rupiah-potongan"
                                           value="{{ old('potongan_iuran_koperasi', $data->potongan?->iuran_koperasi ? $data->potongan->iuran_koperasi : '0') }}">
                                </div>
                            </div>
                            <div class="col col-md-6 col-sm-6">
                                <div class="input-box">
                                    <label for="is_nama">Kredit Pegawai</label>
                                    <input type="text" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai"
                                           class="form-input rupiah-potongan"
                                           value="{{ old('potongan_kredit_pegawai', $data->potongan?->kredit_pegawai ? $data->potongan->kredit_pegawai : '0') }}">
                                </div>
                            </div>
                            <div class="col col-md-6 col-sm-6">
                                <div class="input-box">
                                    <label for="is_nama">Iuran IK</label>
                                    <input type="text" id="potongan_iuran_ik" name="potongan_iuran_ik"
                                           class="form-input rupiah-potongan"
                                           value="{{ old('potongan_iuran_ik', $data->potongan?->iuran_ik ? $data->potongan->iuran_ik : '0') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card tab-pane space-y-5" id="tunjangan">
                        <div class="head-card border-b pb-5">
                            <h2 class="font-bold text-lg">Data Tunjangan</h2>
                        </div>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($data->tunjangan as $key => $tj)
                            @php
                                $no++;
                            @endphp
                            <div id="parent_tunjangan{{$key}}">
                                <div class="grid items-center lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-10" id="collapseFour">
                                    <div class="col-md-5">
                                        <div class="input-box">
                                            <label for="is">Tunjangan </label>
                                            <select name="tunjangan[]" id="tunjangan" class="form-input">
                                                <option value="">--- Pilih ---</option>
                                                @foreach ($tunjangan as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $tj->id_tunjangan ? 'selected' : '' }}>
                                                        {{ $item->nama_tunjangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_tk[]" id="id_tk" value="{{ $tj->id }}">
                                    <div class="col-md-5">
                                        <div class="input-box">
                                            <label for="nominal{{ $key }}">Nominal</label>
                                            <input type="text" id="nominal{{ $key }}" name="nominal_tunjangan[]"
                                                value="{{ number_format($tj->nominal, 0, ',', '.') }}" class="form-input"
                                                onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)">
                                            <input type="hidden" name="id_tunjangan[]" value="{{$tj->id}}">
                                        </div>
                                    </div>
                                    <div class="flex gap-5 mt-6">
                                        @if ($key == 0)
                                            <div class="col-md-1">
                                                <button class="btn btn-success" type="button" id="btn-add">
                                                    <i class="ti ti-plus"></i>
                                                </button>
                                            </div>
                                        @endif
                                        @if ($key > 0)
                                            <div class="col-md-1">
                                                <button class="btn btn-danger" type="button" data-id_parent="{{$key}}" id="btn-delete-tunjangan">
                                                    <i class="ti ti-minus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <input type="hidden" name="tunjangan_baru" value="true">
                            <div id="parent_tunjangan0">
                                <div class="grid items-center lg:grid-cols-3 mb-4 md:grid-cols-2 grid-cols-1 gap-10" id="collapseFour">
                                    <div class="col-md-5">
                                        <div class="input-box">
                                            <label for="is">Tunjangan</label>
                                            <select name="tunjangan[]" id="tunjangan" class="form-input">
                                                <option value="">--- Pilih ---</option>
                                                @foreach ($tunjangan as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-box">
                                            <label for="nominal0">Nominal</label>
                                            <input type="text" id="nominal0" name="nominal_tunjangan[]"
                                                value="" class="form-input"
                                                onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)">
                                            <input type="hidden" name="id_tunjangan[]">
                                        </div>
                                    </div>
                                    <div class="flex gap-5 mt-6">
                                        <div class="col-md-1">
                                            <button class="btn btn-success" type="button" id="btn-add">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-danger" type="button"
                                            id="btn-delete">
                                                <i class="ti ti-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                        <div id="new_item"></div>
                    </div>
                @endif
            @elsecan('manajemen karyawan - data karyawan - edit karyawan - edit potongan')
                <div class="card tab-pane active" id="potongan">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Data Potongan</h2>
                    </div>
                    <div id="collapseFive" class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 "
                         id="row_potongan">
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Kredit Koperasi</label>
                                <input type="text" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi"
                                       class="form-input rupiah-potongan"
                                       value="{{ old('potongan_kredit_koperasi', $data->potongan?->kredit_koperasi ? $data->potongan->kredit_koperasi : '0') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Iuran Koperasi</label>
                                <input type="text" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi"
                                       class="form-input rupiah-potongan"
                                       value="{{ old('potongan_iuran_koperasi', $data->potongan?->iuran_koperasi ? $data->potongan->iuran_koperasi : '0') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Kredit Pegawai</label>
                                <input type="text" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai"
                                       class="form-input rupiah-potongan"
                                       value="{{ old('potongan_kredit_pegawai', $data->potongan?->kredit_pegawai ? $data->potongan->kredit_pegawai : '0') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Iuran IK</label>
                                <input type="text" id="potongan_iuran_ik" name="potongan_iuran_ik"
                                       class="form-input rupiah-potongan"
                                       value="{{ old('potongan_iuran_ik', $data->potongan?->iuran_ik ? $data->potongan->iuran_ik : '0') }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="flex justify-between card">
                <button class="btn btn-success" type="submit"><i class="ti ti-circle-check"></i><span
                        class="lg:block hidden">Simpan</span></button>
                @can('manajemen karyawan - data karyawan - edit karyawan')
                    <div class="flex gap-5">
                        <button class="btn btn-light prev-btn hidden" type="button"><i class="ti ti-arrow-left"></i><span
                                class="lg:block hidden">Form Sebelumnya</span></button>
                        <button class="btn btn-secondary next-btn" type="button"><span class="lg:block hidden">Form
                                Selanjutnya</span><i class="ti ti-arrow-right"></i></button>
                    </div>
                @endcan
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>

        function cekSkAnak(input) {
            var check_sk_tunjangan_anak = $("input[name='check_sk_anak[]']");
            var total_checked = 0
            check_sk_tunjangan_anak.each(function() {
                if ($(this).is(':checked'))
                    total_checked++
            })

            check_sk_tunjangan_anak.each(function() {
                if (total_checked >= 2)
                    $(this).prop('disabled', !$(this).is(':checked'))
                else
                    $(this).prop('disabled', false)
            })
        }

        function onCheckSKAnak(checkbox) {
            var is_check = checkbox.checked
            var input_sk = $(`#${checkbox.id}`).parent().parent().find('.sk-anak')
            input_sk.prop('readonly', !is_check)
            if (is_check) {
                input_sk.removeClass('form-input-disabled')
            }
            else {
                input_sk.val('')
                input_sk.addClass('form-input-disabled')
            }
            cekSkAnak(input_sk)
        }
        $(`.dokument`).on('click', function(){
            const tittle = $(this).data('tittle')
            const filepath = $(this).data('filepath')
            console.log(filepath);
            var target = '#modalDokument';
            $(`${target} #tittle`).html(tittle)
            $(`${target} #image-content`).removeClass('hidden')
            $(`${target} #image-content`).attr('src', filepath)
        })

        let status = $("#status");
        var nip = $("#nip").val()
        var x = {{ $data->count_tj }};
        var subdiv;
        var bag;
        let kd_divisi;
        var idDeleted = []
        var idPotonganDeleted = []
        var idAnakDeleted = []
        var countAnak = $("#is_jml_anak").val()
        toggleButtonAnak()

        kantorChange();
        getKantor();
        jabatanChange();
        var gaji_pokok = $("#gj_pokok").val();
        if (gaji_pokok != undefined) {
            $("#gj_pokok").val(formatRupiah($("#gj_pokok").val()));
        }
        var gj_penyesuaian = $('#gj_penyesuaian').val();
        if (gj_penyesuaian != undefined) {
            $("#gj_penyesuaian").val(formatRupiah($("#gj_penyesuaian").val()));
        }

        var nominal = $('#nominal').val();
        if (nominal != undefined) {
            $("#nominal").val(formatRupiah($("#nominal").val()));
        }

        $("#is_jml_anak").change(function() {
            var angka = $(this).val()

            // Toggle Button
            toggleButtonAnak()
        })
        let layoutPages = document.querySelector('.layout-pages');
        var $tabPanes = $('.tab-pane');
        var currentTab = 0;

        $('.tab-btn').on('click', function() {
            var tabId = $(this).data('tab');
            var current = $(this).data("current");
            $('.tab-pane').removeClass('active');
            $('#' + tabId).addClass('active');
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
            currentTab = current;
            toggleButtons();
        });


        // Move to next tab
        $('.next-btn').on('click', function() {

            if (currentTab < $tabPanes.length - 1) {
                $tabPanes.eq(currentTab).removeClass('active');
                $('.tab-btn').eq(currentTab).removeClass('active');
                currentTab++;
                $tabPanes.eq(currentTab).addClass('active');
                $('.tab-btn').eq(currentTab).addClass('active');
                toggleButtons()
            }
        });

        // Move to previous tab
        $('.prev-btn').on('click', function() {
            if (currentTab > 0) {
                $tabPanes.eq(currentTab).removeClass('active');
                $('.tab-btn').eq(currentTab).removeClass('active');
                currentTab--;
                $tabPanes.eq(currentTab).addClass('active');
                $('.tab-btn').eq(currentTab).addClass('active');
                toggleButtons()
            }
        });

        function toggleButtons() {
            layoutPages.scrollTo({
                top: 0,
                left: 100,
                behavior: 'smooth'
            });
            if (currentTab === 0) {
                $('.prev-btn').addClass('hidden')
            } else {
                $('.prev-btn').removeClass('hidden')
            }
            if (currentTab === $tabPanes.length - 1) {
                $('.next-btn').addClass('hidden')
            } else {
                $('.next-btn').removeClass('hidden')
            }

        }

        $('#gj_pokok').keyup(function() {
            var angka = $(this).val();
            console.log(angka);

            $("#gj_pokok").val(formatRupiah(angka));
        })
        $('#gj_penyesuaian').keyup(function() {
            var angka = $(this).val();

            $("#gj_penyesuaian").val(formatRupiah(angka));
        })
        $('#nominal').keyup(function() {
            var angka = $(this).val();
            console.log(angka);

            $("#nominal").val(formatRupiah(angka));
        })
        $(".rupiah-potongan").keyup(function() {
            var value = $(this).val();
            $(this).val(formatRupiah(value))
        })

        function getKantor() {
            $.ajax({
                type: "GET",
                url: "{{ route('getKantorKaryawan') }}?nip={{ $data->nip }}",
                datatype: "json",
                success: function(res) {
                    console.log(res.kantor);
                    if (res.kantor == "Pusat") {
                        $("#kantor").val(1)
                        kd_divisi = res.div.kd_divisi
                        kantorChange(res.div.kd_divisi)
                        if (res.subdiv != null) {
                            subdiv = res.subdiv;
                        } else {
                            console.log('test');
                            $("#kantor_row2").hide();
                            $("#kantor_row2").empty();
                        }
                        if (res.bag != null) {
                            bag = res.bag.kd_bagian
                        }
                    } else if (res.kantor == "Cabang") {
                        $("#kantor").val(2).change()
                        kantorChange(res.kd_kantor)
                        if (res.bag != null) {
                            bag = res.bag.kd_bagian
                        }
                    }
                }
            })
        }

        function kantorChange(kd_div) {
            var kantor_id = $("#kantor").val();

            if (kantor_id == 1) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('get_divisi') }}",
                    datatype: 'JSON',
                    success: function(res) {
                        $("#kantor_row1").empty();
                        $("#kantor_row1").append(`
                                <div class="input-box">
                                    <label for="divisi">Divisi</label>
                                    <select name="divisi" id="divisi" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}">
                                        <option value="">--- Pilih divisi ---</option>
                                    </select>
                                </div>`);
                        $.each(res, function(i, item) {
                            $('#divisi').append('<option value="' + item.kd_divisi + '" ' + (kd_div ===
                                item.kd_divisi ? 'selected' : '') + '>' + item.nama_divisi +
                                '</option>')
                        });
                        var value = $('#divisi').val();
                        if ($("#jabatan").val() != 'PIMDIV' || $("#jabatan") != "DIRHAN" || $("#jabatan") !=
                            "DIRPEM" || $("#jabatan") != "DIRUMK" || $("#jabatan") != "DIRUT" || $(
                                "#jabatan") != "KOMU" || $("#jabatan") != "KOM") {
                            divisiChange(value);

                            $("#kantor_row2").empty();

                            $("#kantor_row2").append(`
                                    <div class="input-box">
                                        <label for="subdiv">Sub divisi</label>
                                        <select name="subdiv" id="sub_divisi" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}">
                                            <option value="">--- Pilih sub divisi ---</option>
                                        </select>
                                    </div>`);

                            $("#divisi").change(function() {
                                var value = $(this).val();
                                divisiChange(value);
                            })
                        }
                    }
                })
            } else if (kantor_id == 2) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('get_cabang') }}",
                    datatype: 'JSON',
                    success: function(res) {
                        $("#kantor_row1").empty();
                        $("#kantor_row2").empty();
                        $("#kantor_row1").append(`
                                <div class="input-box">
                                    <label for="Cabang">Cabang</label>
                                    <select name="cabang" id="cabang" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                        <option value="">--- Pilih Cabang ---</option>
                                    </select>
                                </div>`);
                        $("#kantor_row2").append(`
                            <div class="input-box">
                                <label for="bagian">Bagian</label>
                                <select name="bagian" id="bagian" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                    <option value="">--- Pilih bagian ---</option>
                                </select>
                            </div>
                        `)

                        $("#kantor_row3").empty()
                        $("#kantor_row3").removeClass("col-md-6")
                        $.each(res[0], function(i, item) {
                            $('#cabang').append('<option value="' + item.kd_cabang + '" ' + (kd_div ===
                                item.kd_cabang ? 'selected' : '') + '>' + item.nama_cabang +
                                '</option>')
                        })
                        $.each(res[1], function(i, item) {
                            $('#bagian').append('<option value="' + item.kd_bagian + '" ' + (bag ===
                                item.kd_bagian ? 'selected' : '') + '>' + item.nama_bagian +
                                '</option>')
                        })
                    }
                })
            } else {
                $("#kantor_row1").empty();
                $("#kantor_row2").empty();
                $("#kantor_row3").empty();
            }
        }

        function divisiChange(divisi) {
            if (divisi) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('get_subdivisi') }}?divisiID=" + divisi,
                    datatype: "JSON",
                    success: function(res1) {
                        $('#kantor_row2').show();
                        $('#sub_divisi').empty();

                        $("#kantor_row3").empty();
                        $("#kantor_row3").addClass("col-md-6");

                        $("#kantor_row3").append(`
                                <div class="input-box">
                                    <label for="bagian">Bagian</label>
                                    <select name="bagian" id="bagian" class="form-input {{auth()->user()->hasRole('cabang') ? 'form-input-disabled' : ''}}" @if(auth()->user()->hasRole('cabang')) aria-readonly="true" @endif>
                                        <option value="">--- Pilih bagian ---</option>
                                    </select>
                                </div>`);

                        if (res1.length < 1) {
                            $('#kantor_row2').hide();
                            subdivChange(divisi);
                            return;
                        }

                        $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
                        $.each(res1, function(i, item) {
                            $('#sub_divisi').append('<option value="' + item.kd_subdiv + '" ' + (
                                subdiv === item.kd_subdiv ? 'selected' : '') + '>' + item
                                .nama_subdivisi + '</option>')
                        });
                        var val = $('#sub_divisi').val();
                        subdivChange(val, divisi)

                        $("#sub_divisi").change(function() {
                            var val = $(this).val();
                            subdivChange(val)
                        })
                    }
                })
            }
        }

        function subdivChange(kd_subdiv, divisi) {
            if (kd_subdiv.length < 1) {
                updateBagianDirectly(divisi);
                return;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('getBagian') }}?kd_entitas=" + kd_subdiv,
                datatype: "JSON",
                success: function(res2) {
                    $('#bagian').empty();
                    $('#bagian').append('<option value="">--- Pilih Bagian ---</option>')
                    $.each(res2, function(i, item) {
                        $('#bagian').append('<option value="' + item.kd_bagian + '" ' + (bag === item
                                .kd_bagian ? 'selected' : '') + '>' + item.nama_bagian +
                            '</option>')
                    });
                }
            })
        }

        function jabatanChange() {
            var value = $("#jabatan").val();
            $("#kantor_row2").show();
            if (value == "PIMDIV" || value == "DIRHAN" || value == "DIRPEM" || value == "DIRUMK" || value == "DIRUT" ||
                value == "KOMU" || value == "KOM") {
                $("#kantor").val("1")
                kantorChange();
                $('#kantor').attr("aria-readonly", "true");
                $("kantor_row2").removeClass("col-md-6")
                $("#kantor_row2").hide();
                $("#kantor_row3").hide()
            } else if (value == "PSD") {
                $("#kantor").val("1")
                kantorChange();
                $('#kantor').attr("aria-readonly", "true");
            } else if (value == "PC" || value == "PBP") {
                $("#kantor").val("2")
                kantorChange();
                $('#kantor').attr("aria-readonly", "true");
                $("#kantor_row2").hide();
            } else if (value == "PBO") {
                kantorChange();
                $('#kantor').removeAttr("aria-readonly")
                $("kantor_row2").removeClass("col-md-6")
                $("#kantor_row2").hide();
                $("#kantor_row3").hide()
            } else if (value == "-") {
                kantorChange();
            } else {
                $('#kantor').removeAttr("aria-readonly")
            }
        }

        function formatRupiahTwo(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }


        function inputFormatRupiah(id) {
            $('#' + id).on('input', function() {
                var inputValue = $(this).val().replace(/\D/g, "");
                var formattedValue = formatRupiahTwo(inputValue);
                $(this).val(formattedValue);
            });
        }

        $("#jabatan").change(function() {
            jabatanChange()
        })

        $('#kantor').change(function() {
            kantorChange();
        });

        $('#status').change(function() {
            var stat = $(this).val();

            if (stat == '') {
                $('#data_is').hide();
                $('#parent-family').addClass('hidden');
                $('.pasangan').addClass('hidden');
            } else {
                if (stat == 'Belum Kawin') {
                    $('#data_is').hide();
                    $('#parent-family').addClass('hidden');
                    $('.pasangan').removeClass('hidden');
                }
                else if (stat == 'Tidak Diketahui') {
                    $('#data_is').hide();
                    $('#parent-family').addClass('hidden');
                    $('.pasangan').removeClass('hidden');
                }
                else if (stat == 'Cerai') {
                    $('#data_is').show();
                    $('#parent-family').removeClass('hidden');
                    $('.pasangan').addClass('hidden');
                }
                else if (stat == 'Cerai Mati') {
                    $('#data_is').show();
                    $('#parent-family').removeClass('hidden');
                    $('.pasangan').addClass('hidden');
                }
                else {
                    $('#data_is').show();
                    $('#parent-family').removeClass('hidden');
                    $('.pasangan').removeClass('hidden');
                }
            }
        })

        $('#collapseFour').on('click', "#btn-add", function() {
            // console.log('masuk');
            x++;
            $('#new_item').append(`
                <div id="parent_tunjangan${x}" class="grid items-center lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-10">
                    <div class="col-md-5">
                        <div class="input-box">
                            <label for="is">Tunjangan </label>
                            <select name="tunjangan[]" id="tunjangan" class="form-input">
                                <option value="">--- Pilih ---</option>
                                @foreach ($tunjangan as $item)
            <option value="{{ $item->id }}">
                                        {{ $item->nama_tunjangan }}</option>
                                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" name="id_tk[]" id="id_tk" value="">
    <div class="col-md-5">
        <div class="input-box">
            <label for="is_nama">Nominal</label>
            <input type="text" id="nominal${x}" name="nominal_tunjangan[]"
                                value="" class="form-input"
                                onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)">
                        </div>
                    </div>
                    <div class="flex gap-5 mt-6">
                        <div class="col-md-1">
                            <button class="btn btn-danger" type="button" data-id_parent="${x}" id="btn-delete-tunjangan">
                                <i class="ti ti-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '#btn-delete-tunjangan', function() {
            const parent = $(this).data("id_parent");
            const parents = '#parent_tunjangan' + parent;
            $(`${parents}`).remove();
            x--;
        });

        $('#collapseFive').on('click', "#btn-add-potongan", function() {
            $('#collapseFive').append(`
                <hr class="mx-4">
                <div class="row m-0 pb-3 col-md-12" id="row_tunjangan">
                    <input type="hidden" name="id_pot[]" id="id_pot" value="">
                    <div class="col col-md-4 col-sm-6">
                        <div class="input-box">
                            <label for="is">Tahun</label>
                            <select name="potongan_tahun[]" id="potongan_tahun"
                                class="form-input">
                                <option value="0">-- Pilih tahun --</option>
                                @php
                $sekarang = date('Y');
                $awal = $sekarang - 5;
                $akhir = $sekarang + 5;
            @endphp
            @for ($i = $awal; $i <= $akhir; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
            </select>
        </div>
    </div>
    <div class="col col-md-4 col-sm-6">
        <div class="input-box">
            <label for="is">Bulan</label>
            <select name="potongan_bulan[]" id="potongan_bulan"
                class="form-input">
                <option value="0">-- Pilih bulan --</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
    </div>
    <div class="col col-md-4 col-sm-6">
        <div class="input-box">
            <label for="is_nama">Kredit Koperasi</label>
            <input type="number" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi[]" class="form-input" value="">
        </div>
    </div>
    <div class="col col-md-3 col-sm-6">
        <div class="input-box">
            <label for="is_nama">Iuran Koperasi</label>
            <input type="number" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi[]" class="form-input" value="">
        </div>
    </div>
    <div class="col col-md-3 col-sm-6">
        <div class="input-box">
            <label for="is_nama">Kredit Pegawai</label>
            <input type="number" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai[]" class="form-input" value="">
        </div>
    </div>
    <div class="col col-md-3 col-sm-6">
        <div class="input-box">
            <label for="is_nama">Iuran IK</label>
            <input type="number" id="potongan_iuran_ik" name="potongan_iuran_ik[]" class="form-input" value="">
        </div>
    </div>
    <div class="col col-md-1">
        <button class="btn btn-info mt-3" type="button" id="btn-add-potongan">
            <i class="bi-plus-lg"></i>
        </button>
    </div>
    <div class="col col-md-1">
        <button class="btn btn-info mt-3" type="button" id="btn-delete-potongan">
            <i class="bi-dash-lg"></i>
        </button>
    </div>
</div>
`);
            countIdPotongan++
        });

        $('#collapseFive').on('click', "#btn-delete-potongan", function() {
            var row = $(this).closest('.row')
            var hr = row.parent().find('hr').remove()
            var value = row.children('#id_pot').val()
            if (countIdPotongan > 1) {
                if (value != null) {
                    idPotonganDeleted.push(value)
                    row.remove()
                    hr.remove()
                    countIdPotongan--;
                    $("#idPotDeleted").val(idPotonganDeleted)
                } else {
                    $(this).closest('.row').remove()
                    $(this).closest('.row').parent().find('hr').remove()
                    countIdPotongan--;
                }
            }
        })

        function updateBagianDirectly(divisi) {
            const kd_bagian = '{{ $data->kd_bagian }}';

            $.ajax({
                type: "GET",
                url: "{{ route('getBagian') }}?kd_entitas=" + divisi,
                datatype: "JSON",
                success: function(res2) {
                    $('#bagian').empty();
                    $('#bagian').append('<option value="">--- Pilih Bagian ---</option>')
                    $.each(res2, function(i, item) {
                        $('#bagian').append(
                            `<option ${item.kd_bagian == kd_bagian ? 'selected' : ''} value="${item.kd_bagian}">${item.nama_bagian}</option>`
                        );
                    });
                }
            })
        }

        $("#status_karyawan").change(function() {
            var value = $(this).val();
            if (value == "IKJP" || value == "Kontrak Perpanjangan") {
                $("#labelGajiPokok").html("Honorarium")
            } else {
                $("#labelGajiPokok").html("Gaji Pokok")
            }
        })

        $("").keydown(function(event) {
            var inputValue = event.which;
            // allow letters and whitespaces only.
            if (!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) {
                event.preventDefault();
            }
        })
        function toggleButtonAnak() {
            if (countAnak == 0) {
                $('#add-row-anak').removeClass('hidden')
            }
            else {
                $('#add-row-anak').addClass('hidden')
            }
        }

        $("#row_anak").on("click", '.btn-add-anak', function(){
            $(".preloader").removeAttr('style');
            var angka = countAnak;
            var isDisabled = countAnak > 2 ? 'disabled' : '';
            var iteration = parseInt(countAnak) + 1
            $("#row_anak").append(`
                <div id="anak-${countAnak}" class="child-div">
                    <h6 class="font-bold text-lg mb-5 ket-anak">Data Anak ` + iteration + `</h6>
                    <div class="grid col-span-5 w-full lg:grid-cols-4 items-center md:grid-cols-2 grid-cols-1 gap-5 form-anak" data-anak="${countAnak}">
                        <input type="hidden" name="id_anak[]" value="">
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="nama_anak">Nama Anak</label>
                                <input type="text" class="form-input" name="nama_anak[]" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                                <input type="date" class="form-input" name="tgl_lahir_anak[]" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="sk_tunjangan_anak_${countAnak}">SK Tunjangan</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-2 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <input
                                            onchange="onCheckSKAnak(this)" type="checkbox" id="check_sk_anak_${countAnak}"
                                            name="check_sk_anak[]" class="check_sk_anak w-8 h-8">
                                    </span>
                                    <input type="text" onchange="cekSkAnak(this)" id="sk_tunjangan_anak_${countAnak}" class="form-input sk-anak form-input-disabled" name="sk_tunjangan_anak[]" readonly>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div class="flex items-center mt-7 gap-3">
                                <div>
                                    <button class="btn btn-success btn-add-anak" type="button">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                                <div>
                                    <button class="btn btn-danger btn-remove-anak" type="button" data-parent-anak="${countAnak}" data-id-anak="">
                                        <i class="ti ti-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            // Reset readonly
            var input = $(`#anak-${countAnak}`).find('.sk-anak')
            cekSkAnak(input)

            countAnak++;
            $("#is_jml_anak").val(countAnak);
            $("#is_jml_anak").trigger('change');
            $(".preloader").hide()
        })

        function resetKetAnak() {
            var div_anak = $('#row_anak').find('.child-div')
            div_anak.each(function(i) {
                $(this).prop('id', `anak-${i}`)
                $(this).find('.form-anak').attr('data-anak', i)
                $(this).find('.btn-remove-anak').attr('data-parent-anak', i)
                $(this).find('.ket-anak').html(`Data Anak ${i + 1}`)
            })
        }

        $("#row_anak").on("click", '.btn-remove-anak', function(){
            var parent = $(this).data('parent-anak');
            var parents = `anak-${parent}`
            var idDeleted = $(this).data('id-anak');
            if (idDeleted.length == 0 || idDeleted != null) {
                idAnakDeleted.push(idDeleted)
                $("#idAnakDeleted").val(idAnakDeleted)
            }

            $(`#${parents}`).remove()
            countAnak--;
            $("#is_jml_anak").val(countAnak);
            $("#is_jml_anak").trigger('change');

            // Reset ket anak
            resetKetAnak()

            // Reset readonly
            var input = $(this).parent().parent().find('.sk-anak')
            cekSkAnak(input)
        })

        $("#add-row-anak").on("click", function() {
            var iteration = parseInt(countAnak) + 1
            $("#row_anak").append(`
                <div id="anak-${countAnak}" class="child-div">
                    <h6 class="font-bold text-lg mb-5 ket-anak">Data Anak ` + iteration + `</h6>
                    <div class="grid col-span-5 w-full lg:grid-cols-4 items-center md:grid-cols-2 grid-cols-1 gap-5 form-anak" data-anak="${countAnak}">
                        <input type="hidden" name="id_anak[]" value="">
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="nama_anak">Nama Anak</label>
                                <input type="text" class="form-input" name="nama_anak[]" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                                <input type="date" class="form-input" name="tgl_lahir_anak[]" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="sk_tunjangan_anak_${countAnak}">SK Tunjangan</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-2 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <input
                                            onchange="onCheckSKAnak(this)" type="checkbox" id="check_sk_anak_${countAnak}"
                                            name="check_sk_anak[]" class="check_sk_anak w-8 h-8">
                                    </span>
                                    <input type="text" onchange="cekSkAnak(this)" id="sk_tunjangan_anak_${countAnak}" class="form-input sk-anak form-input-disabled" name="sk_tunjangan_anak[]" readonly>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div class="flex items-center mt-7 gap-3">
                                    <div>
                                        <button class="btn btn-success btn-add-anak" type="button">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <button class="btn btn-danger btn-remove-anak" type="button" data-parent-anak="${countAnak}" data-id-anak="">
                                            <i class="ti ti-minus"></i>
                                        </button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            `);

            // Reset readonly
            var input = $(`#anak-${countAnak}`).find('.sk-anak')
            cekSkAnak(input)

            countAnak++
            $("#is_jml_anak").val(countAnak)
            $("#is_jml_anak").trigger("change")
        })
    </script>
@endsection
