@php
    $arrayPendidikan = array('SD', 'SMP', 'SLTP', 'SLTA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3');
@endphp
@extends('layouts.app-template')
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
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Data Karyawan</h2>

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
    <div class="p-5">
        <div class="card sticky top-20">
            <div>
                <div
                  class="tab-menu relative after:absolute after:inset-x-0 after:top-1/2 after:block after:h-0.5 after:-translate-y-1/2 after:rounded-lg after:bg-gray-100"
                >
                  <ol class="relative z-10 flex justify-between text-sm font-medium text-gray-500">
                    <li class="flex items-center gap-2 bg-white p-2 tab-btn active cursor-pointer" data-tab="data-biodata">
                      <span class="count-circle h-6 w-6 rounded-full text-white text-center text-[10px]/6 font-bold">
                        1
                      </span>

                      <span class="hidden sm:block"> Biodata Karyawan </span>
                    </li>

                    <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer"  data-tab="data-karyawan">
                      <span
                        class="count-circle h-6 w-6 rounded-full text-center text-[10px]/6 font-bold text-white"
                      >
                        2
                      </span>

                      <span class="hidden sm:block"> Data Karyawan </span>
                    </li>

                    {{-- <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="data-keluarga">
                      <span class="count-circle h-6 w-6 rounded-full  text-white text-center text-[10px]/6 font-bold">
                        3
                      </span>

                      <span class="hidden sm:block"> Data Keluarga</span>
                    </li> --}}
                    <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="data-tunjangan">
                      <span class="count-circle h-6 w-6 rounded-full  text-white text-center text-[10px]/6 font-bold">
                        3
                      </span>

                      <span class="hidden sm:block"> Data Tunjangan</span>
                    </li>
                    <li class="flex items-center gap-2 bg-white p-2 tab-btn cursor-pointer" data-tab="data-potongan">
                      <span class="count-circle h-6 w-6 rounded-full  text-white text-center text-[10px]/6 font-bold">
                        4
                      </span>

                      <span class="hidden sm:block"> Data Potongan</span>
                    </li>
                  </ol>
                </div>
              </div>
        </div>
    </div>
    <div  class="body-pages  space-y-5">
        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data" name="karyawan" class="space-y-5">
            @csrf
            <div class="">
                <div class="card tab-pane active" id="data-biodata">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Biodata Karyawan</h2>
                    </div>
                    <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Foto Karyawan</label>
                                <input type="file" class="@error('foto_diri') is-invalid @enderror  form-input only-image" name="foto_diri" id="foto_diri" accept="image/png, image/jpeg">
                                <span class="text-red-500 m-0 error-msg" style="display: none"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Foto KTP</label>
                                <input type="file" class="@error('foto_ktp') is-invalid @enderror  form-input only-image" name="foto_ktp" id="foto_ktp" accept="image/png, image/jpeg">
                                <span class="text-red-500 m-0 error-msg" style="display: none"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NIP</label>
                                <input type="text" class="@error('nip') is-invalid @enderror form-input" name="nip" id="" value="{{ old('nip') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NIK</label>
                                <input type="text" class="@error('nik') is-invalid @enderror form-input" name="nik" id="" value="{{ old('nik') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Nama Karyawan</label>
                                <input type="text" class="@error('nama') is-invalid @enderror form-input" name="nama" id="" value="{{ old('nama') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Tempat Lahir</label>
                                <input type="text" class="@error('tmp_lahir') is-invalid @enderror form-input" name="tmp_lahir" id="" value="{{ old('tmp_lahir') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Tanggal Lahir</label>
                                <input type="date" class="@error('tgl_lahir') is-invalid @enderror form-input" name="tgl_lahir" id="" value="{{ old('tgl_lahir') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Agama</label>
                                <select name="agama" id="" class="@error('agama') is-invalid @enderror form-input">
                                    <option value="-">--- Pilih ---</option>
                                    @foreach ($agama as $item)
                                    <option {{ old('agama') == $item->kd_agama ? 'selected' : '' }} value="{{ $item->kd_agama }}">{{ $item->agama }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Jenis Kelamin</label>
                                <select name="jk" id="" class="@error('jk') is-invalid @enderror form-input">
                                    <option {{ old('jk') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                    <option {{ old('jk') == 'Laki-Laki' ? 'selected' : '' }} value="Laki-laki">Laki-laki</option>
                                    <option {{ old('jk') == 'Perempuan' ? 'selected' : '' }} value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status pernikahan</label>
                                <select name="status_pernikahan" id="status" class="@error('status_pernikahan') is-invalid @enderror form-input">
                                    <option value="">--- Pilih ---</option>>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Cerai">Cerai</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                    <option value="Janda">Janda</option>
                                    <option value="Duda">Duda</option>
                                    <option value="Tidak Diketahui">Tidak Diketahui</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status PTKP</label>
                                <select name="status_ptkp" id="" class="@error('status_ptkp') is-invalid @enderror form-input">
                                    <option {{ old('status_ptkp') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                    <option {{ old('status_ptkp') == 'K/0' ? 'selected' : '' }} value="K/0">K/0</option>
                                    <option {{ old('status_ptkp') == 'K/1' ? 'selected' : '' }} value="K/1">K/1</option>
                                    <option {{ old('status_ptkp') == 'K/2' ? 'selected' : '' }} value="K/2">K/2</option>
                                    <option {{ old('status_ptkp') == 'K/3' ? 'selected' : '' }} value="K/3">K/3</option>
                                    <option {{ old('status_ptkp') == 'K/I/0' ? 'selected' : '' }} value="K/3">K/I/0</option>
                                    <option {{ old('status_ptkp') == 'K/I/1' ? 'selected' : '' }} value="K/3">K/I/1</option>
                                    <option {{ old('status_ptkp') == 'K/I/2' ? 'selected' : '' }} value="K/3">K/I/2</option>
                                    <option {{ old('status_ptkp') == 'K/I/3' ? 'selected' : '' }} value="K/3">K/I/3</option>
                                    <option {{ old('status_ptkp') == 'TK/0' ? 'selected' : '' }} value="TK/0">TK/0</option>
                                    <option {{ old('status_ptkp') == 'TK/1' ? 'selected' : '' }} value="K/3">TK/1</option>
                                    <option {{ old('status_ptkp') == 'TK/2' ? 'selected' : '' }} value="K/3">TK/2</option>
                                    <option {{ old('status_ptkp') == 'TK/3' ? 'selected' : '' }} value="K/3">TK/3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Kewarganegaraan</label>
                                <select name="kewarganegaraan" id="" class="@error('kewarganegaraan') is-invalid @enderror form-input">
                                    <option {{ old('kewarganegaraan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                    <option {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }} value="WNI">WNI</option>
                                    <option {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }} value="WNA">WNA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5">
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Alamat KTP</label>
                                <textarea name="alamat_ktp" id="" class="@error('alamat_ktp') is-invalid @enderror form-input">{{ old('alamat_ktp') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Alamat sekarang</label>
                                <textarea name="alamat_sek" id="" class="form-input">{{ old('alamat_sek') }}</textarea>
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
                                <label for="">Nomor Rekening</label>
                                <input type="number" class="form-input" name="no_rek" value="{{ old('no_rek') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">NPWP</label>
                                <input type="number" class="form-input" name="npwp" value="{{ old('npwp') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Jabatan</label>
                                <select name="jabatan" id="jabatan" class="form-input">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($jabatan as $item)
                                                <option value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="kantor">Kantor</label>
                                <select name="kantor" id="kantor" class="form-input">
                                    <option value="">--- Pilih Kantor ---</option>
                                    <option value="1">Kantor Pusat</option>
                                    <option value="2">Kantor Cabang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 hidden" id="kantor_row1">

                        </div>
                        <div class="col-md-6 hidden"  id="kantor_row2">

                        </div>
                        <div class="col-md-6 hidden"  id="kantor_row3">

                        </div>

                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Pangkat Dan Golongan</label>
                                <select name="panggol" id="" class="@error('panggol') is-invalid @enderror form-input">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($panggol as $item)
                                                <option {{ old('panggol') == $item->golongan ? 'selected' : '--- Pilih ---' }} value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status Jabatan</label>
                                <select name="status_jabatan" id="" class="@error('status_jabatan') is-invalid @enderror form-input">
                                    <option {{ old('status_jabatan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                    <option {{ old('status_jabatan') == 'Definitif' ? 'selected' : '' }} value="Definitif">Definitif</option>
                                    <option {{ old('status_jabatan') == 'Penjabat' ? 'selected' : ''}} value="Penjabat">Penjabat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">Keterangan Jabatan</label>
                                <input type="text" class="form-input" name="ket_jabatan" value="{{ old('ket_jabatan') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">KPJ</label>
                                <input type="text" class="@error('kpj') is-invalid @enderror form-input" name="kpj" value="{{ old('kpj') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-box">
                                <label for="">JKN</label>
                                <input type="text" class="@error('jkn') is-invalid @enderror form-input" name="jkn" value="{{ old('jkn') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Honorarium</label>
                                <input type="text" id="gj_pokok" class="@error('gj_pokok') is-invalid @enderror form-input" name="gj_pokok" value="{{ old('gj_pokok') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Honorarium Penyesuaian</label>
                                <input type="text" class="form-input" id="gj_penyesuaian" name="gj_penyesuaian" value="{{ old('gj_penyesuaian') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Status Karyawan</label>
                                <select name="status_karyawan" id="" class="@error('status_karyawan') is-invalid @enderror form-input">
                                    <option {{ old('status_karyawan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                    <option {{ old('status_karyawan') == 'Tetap' ? 'selected' : '' }} value="Tetap">Tetap</option>
                                    <option {{ old('status_karyawan') == 'IKJP' ? 'selected' : '' }} value="IKJP">IKJP</option>
                                    <option {{ old('status_karyawan') == 'Kontrak Perpanjangan' ? 'selected' : '' }} value="Kontrak Perpanjangan">Kontrak Perpanjangan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Tanggal Mulai</label>
                                <input type="date" class="@error('tgl_mulai') is-invalid @enderror form-input" name="tgl_mulai" value="{{ old('tgl_mulai') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">SK Pengangkatan</label>
                                <input type="text" class="@error('skangkat') is-invalid @enderror form-input" name="skangkat" value="{{ old('skangkat')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="">Tanggal Pengangkatan</label>
                                <input type="date" class="@error('tanggal_pengangkat') is-invalid @enderror form-input" name="tanggal_pengangkat" value="{{ old('tanggal_pengangkat') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="pendidikan">Pendidikan</label>
                                <select name="pendidikan" class="form-input" id="">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($arrayPendidikan as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <label for="pendidikan_major">Pendidikan Major</label>
                                <input type="text" class="@error('pendidikan_major') is-invalid @enderror form-input" name="pendidikan_major" value="{{ old('pendidikan_major') }}">
                            </div>
                        </div>
                    </div>
                </div>
                    {{-- <div class="card tab-pane" id="data-keluarga">
                        <div class="head-card border-b pb-5">
                            <h2 class="font-bold text-lg">Data Keluarga</h2>
                        </div>
                        <div class="grid pb-10 gap-8 mt-5 lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="is">Pasangan</label>
                                    <select name="is" id="is" class="form-input">
                                        <option {{ old('is') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('is') == 'Suami' ? 'selected' : '' }} value="Suami">Suami</option>
                                        <option {{ old('is') == 'Istri' ? 'selected' : '' }} value="Istri">Istri</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="is_nama">Nama</label>
                                    <input type="text" name="is_nama" id="is_nama"  value="{{ old('is_nama') }}" class="form-input">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box">
                                    <label for="sk_tunjangan_is">SK Tunjangan</label>
                                    <input type="text" name="sk_tunjangan_is" id="sk_tunjangan_is" value="{{ old('sk_tunjangan_is') }}" class="form-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label for="is_tgl_lahir">Tanggal Lahir</label>
                                    <input type="date" name="is_tgl_lahir" class="form-input" value="{{ old('is_tgl_lahir') }}" id="is_tgl_lahir">
                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="input-box">
                                <label for="is_pekerjaan">Pekerjaan</label>
                                <input type="text" class="form-input" name="is_pekerjaan" id="is_pekerjaan" value="{{ old('is_pekerjaan') }}"">
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-box">
                                <label for="is_jumlah_anak">Jumlah Anak</label>
                                <input type="number" class="form-input" name="is_jml_anak" id="is_jml_anak" value="{{ old('is_jumlah_anak') }}">
                                </div>
                            </div>
                            <div class="col-span-3 space-y-5 " id="row_anak">
                            </div>
                            <div class="col-md-6 col-span-3">
                                <div class="input-box">
                                    <label for="is_alamat">Alamat</label>
                                    <textarea name="is_alamat" class="form-input">{{ old('is_alamat') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                <div class="card tab-pane space-y-5" id="data-tunjangan" >
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Data Tunjangan</h2>
                    </div>
                    <div id="collapseFour">
                        <div class="row grid items-center lg:grid-cols-3 mb-4 md:grid-cols-2 grid-cols-1 gap-10">
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
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
                            <div class="col-md-5">
                                <div class="input-box">
                                    <label for="is_nama">Nominal</label>
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-input" onkeyup="formatRupiah(this)">
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
                </div>

                <div class="card tab-pane" id="data-potongan">
                    <div class="head-card border-b pb-5">
                        <h2 class="font-bold text-lg">Data Potongan</h2>
                    </div>
                    <div id="collapseFive" class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 " id="row_potongan">
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Kredit Koperasi</label>
                                <input type="text" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi" class="form-input rupiah-potongan" onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)" value="{{ old('potongan_kredit_koperasi', $data->potongan->kredit_koperasi ?? '') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Iuran Koperasi</label>
                                <input type="text" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi" class="form-input rupiah-potongan" onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)" value="{{ old('potongan_iuran_koperasi', $data->potongan->iuran_koperasi ?? '') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Kredit Pegawai</label>
                                <input type="text" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai" class="form-input rupiah-potongan" onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)" value="{{ old('potongan_kredit_pegawai', $data->potongan->kredit_pegawai ?? '') }}">
                            </div>
                        </div>
                        <div class="col col-md-6 col-sm-6">
                            <div class="input-box">
                                <label for="is_nama">Iuran IK</label>
                                <input type="text" id="potongan_iuran_ik" name="potongan_iuran_ik" class="form-input rupiah-potongan" onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)" value="{{ old('potongan_iuran_ik', $data->potongan->iuran_ik ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <div class="p-5">
            <div class="flex justify-between card">
                <button class="btn btn-primary" type="submit"><i class="ti ti-plus"></i><span class="lg:block hidden">Tambah</span></button>
                <div class="flex gap-5">
                    <button class="btn btn-light prev-btn hidden" type="button"><i class="ti ti-arrow-left"></i><span class="lg:block hidden">Form Sebelumnyaa</span></button>
                    <button class="btn btn-secondary next-btn" type="button"><span class="lg:block hidden">Form Selanjutnya</span><i class="ti ti-arrow-right"></i></button>
                </div>
            </div>
          </div>
        </form>
    </div>
@endsection

@section('custom_script')
<script>
    let kantor = $('#kantor_row');
    let status = $('#status');
    $('#kantor').attr("disabled", "disabled");
    var x =1;
    var countIdPotongan = 1;
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

    $("#is_jml_anak").keyup(function(){
        $("#row_anak").empty();
        var angka = $(this).val()
        if(angka > 2) angka = 2;

        for(var i = 0; i < angka; i++){
            var ket = (i == 0) ? 'Pertama' : 'Kedua';
            $("#row_anak").append(`
            <h6 class="">Data Anak `+ ket +`</h6>
            <div class="row">
                <div class="col-md-6 input-box">
                    <label for="nama_anak">Nama Anak</label>
                    <input type="text" class="form-input" name="nama_anak[]">
                </div>
                <div class="col-md-6 input-box">
                    <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                    <input type="date" class="form-input" name="tgl_lahir_anak[]">
                </div>
                <div class="col-md-6 input-box">
                    <label for="sk_tunjangan_anak">SK Tunjangan</label>
                    <input type="text" class="form-input" name="sk_tunjangan_anak[]">
                </div>
            </div>
        `);
        }
    })
    let layoutPages = document.querySelector('.layout-pages');
    var $tabPanes = $('.tab-pane');
        var currentTab = 0;

        $('.tab-btn').on('click', function() {
            var tabId = $(this).data('tab');
            $('.tab-pane').removeClass('active');
            $('#' + tabId).addClass('active');
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
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
            layoutPages.scrollTo({ top: 0, left: 100, behavior: 'smooth' });
            if (currentTab === 0) {
                $('.prev-btn').addClass('hidden')
            } else {
                $('.prev-btn').removeClass('hidden')
            }
            if (currentTab ===  $tabPanes.length - 1) {
                $('.next-btn').addClass('hidden')
            } else {
                $('.next-btn').removeClass('hidden')
            }

        }
    function formatNumber(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }
    $('#gj_pokok').keyup(function(){
        var angka = $(this).val();

        $("#gj_pokok").val(formatRupiah(angka));
    })
    $('#gj_penyesuaian').keyup(function(){
        var angka = $(this).val();

        $("#gj_penyesuaian").val(formatRupiah(angka));
    })
    $('#nominal').keyup(function(){
        var angka = $(this).val();
        console.log(angka);

        $("#nominal").val(formatRupiah(angka));
    })

    function kantorChange(){
        var kantor_id = $("#kantor").val();

        if(kantor_id == 1){
            $.ajax({
                type: "GET",
                url: "{{ route('get_divisi') }}",
                datatype: 'JSON',
                success: function(res){
                    $("#kantor_row1").empty();
                    $("#kantor_row1").append(`
                            <div class="input-box">
                                <label for="divisi">Divisi</label>
                                <select name="divisi" id="divisi" class="form-input divisi">
                                    <option value="">--- Pilih divisi ---</option>
                                </select>
                            </div>`
                    );
                    $.each(res, function(i, item){
                        $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
                    });

                    $("#kantor_row2").empty();

                    $("#kantor_row2").append(`
                            <div class="input-box">
                                <label for="subdiv">Sub divisi</label>
                                <select name="subdiv" id="sub_divisi" class="form-input">
                                    <option value="">--- Pilih sub divisi ---</option>
                                </select>
                            </div>`
                    );

                    $("#divisi").change(function(){
                        var divisi = $(this).val();

                        if(divisi){
                            $.ajax({
                                type: "GET",
                                url: "{{ route('get_subdivisi') }}?divisiID="+divisi,
                                datatype: "JSON",
                                success: function(res1){
                                    $('#sub_divisi').empty();
                                    $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
                                    $.each(res1, function(i, item){
                                        $('#sub_divisi').append('<option value="'+item.kd_subdiv+'">'+item.nama_subdivisi+'</option>')
                                    });

                                    $("#kantor_row3").empty();
                                    $("#kantor_row3").removeClass("hidden");
                                    $("#kantor_row3").addClass("col-md-6");

                                    $("#kantor_row3").append(`
                                            <div class="input-box">
                                                <labe l for="bagian">Bagian</labe>
                                                <select name="bagian" id="bagian" class="form-input">
                                                    <option value="">--- Pilih bagian ---</option>
                                                </select>
                                            </div>`
                                    );

                                    $("#sub_divisi").change(function(){
                                        $.ajax({
                                            type: "GET",
                                            url: "{{ route('getBagian') }}?kd_entitas="+$(this).val(),
                                            datatype: "JSON",
                                            success: function(res2){
                                                $('#bagian').empty();
                                                $('#bagian').append('<option value="">--- Pilih Bagian ---</option>')
                                                $.each(res2, function(i, item){
                                                    $('#bagian').append('<option value="'+item.kd_bagian+'">'+item.nama_bagian+'</option>')
                                                });
                                            }
                                        })
                                    })
                                }
                            })
                        }
                    })
                }
            })
        } else if(kantor_id == 2){
            $.ajax({
                type: "GET",
                url: "{{ route('get_cabang') }}",
                datatype: 'JSON',
                success: function(res){
                    $("#kantor_row1").empty();
                    $("#kantor_row2").empty();
                    $("#kantor_row1").append(`
                            <div class="input-box">
                                <label for="Cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-input">
                                    <option value="">--- Pilih Cabang ---</option>
                                </select>
                            </div>`
                    );
                    $("#kantor_row2").append(`
                        <div class="input-box">
                            <label for="bagian">Bagian</label>
                            <select name="bagian" id="bagian" class="form-input">
                                <option value="">--- Pilih bagian ---</option>
                            </select>
                        </div>
                    `)

                    $("#kantor_row3").empty()
                    $("#kantor_row3").removeClass("col-md-6")
                    $("#kantor_row3").removeClass("hidden")
                    $.each(res[0], function(i, item){
                        $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.nama_cabang+'</option>')
                    })
                    $.each(res[1], function(i, item){
                        $('#bagian').append('<option value="'+item.kd_bagian+'">'+item.nama_bagian+'</option>')
                    })
                }
            })
        } else {
            $("#kantor_row1").empty();
            $("#kantor_row2").empty();
            $("#kantor_row3").empty();
        }
    }

    $("#jabatan").change(function(){
        var value = $(this).val();
        $("#kantor_row1").removeClass('hidden');
        $("#kantor_row2").removeClass('hidden');
        if(value == "PIMDIV" || value == "DIRHAN" || value == "DIRPEM" || value == "DIRUMK" || value == "DIRUT" || value == "KOMU" || value == "KOM"){
            $("#kantor").val("1")
            kantorChange();
            $('#kantor').attr("disabled", "disabled");
            $("kantor_row2").removeClass("col-md-6")
            $("#kantor_row2").addClass('hidden');
            $("#kantor_row3").addClass('hidden')
        } else if(value == "PSD"){
            $("#kantor").val("1")
            kantorChange();
            $('#kantor').attr("disabled", "disabled");
        } else if(value == "PC" || value == "PBP"){
            $("#kantor").val("2")
            kantorChange();
            $('#kantor').attr("disabled", "disabled");
            $("#kantor_row2").addClass('hidden');
        } else if(value == "PBO"){
            kantorChange();
            $('#kantor').removeAttr("disabled")
            $("kantor_row2").removeClass("col-md-6")
            $("#kantor_row2").addClass('hidden');
            $("#kantor_row3").addClass('hidden')
        } else if(value == "-"){
            kantorChange();
        }else {
            $('#kantor').removeAttr("disabled")
        }
    })


    $('#kantor').change(function(){
        kantorChange();
    });

    $('#data_is').addClass('hidden');

    $('#status').change(function(){
        var stat = $(this).val();

        if(stat == 'Kawin'){
            $('#data_is').removeClass('hidden');
        } else{
            $('#data_is').addClass('hidden');
        }
    })

    $('#collapseFour').on('click', "#btn-add", function(){
        x++;
        $('#collapseFour').append(`
        <div class="row grid items-center lg:grid-cols-3 mb-4 md:grid-cols-2 grid-cols-1 gap-10">
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
                        <input type="hidden" name="id_tk[]" id="id_tk" value="">
                        <div class="col-md-5">
                            <div class="input-box">
                                <label for="is_nama">Nominal</label>
                                <input type="text" id="nominal${x}" name="nominal_tunjangan[]" class="form-input"
                                onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)">
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
        `);
        x++
    });

    $('#collapseFour').on('click', "#btn-delete", function(){
        if(x > 1){
            $(this).closest('.row').remove()
            x--;
        }
    })
    $('#nominal').keyup(function(){
        var angka = $(this).val();

        $("#nominal").val(formatRupiah(angka));
    })

    $("#row_anak").on("click", "#btn_plus-anak", function(){

        y++;
    });

    $("#row_anak").on("click", "#btn_minus-anak", function(){
        if(y > 1){
            $(this).closest('.card').remove()
            y--;
        }
    })

    $('#collapseFive').on('click', "#btn-add-potongan", function(){
        $('#collapseFive').append(`
            <hr class="mx-4">
            <div class="row m-0 pb-3 col-md-12" id="row_tunjangan">
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
                            @for($i=$awal;$i<=$akhir;$i++)
                                <option value="{{$i}}">{{$i}}</option>
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

    $('#collapseFive').on('click', "#btn-delete-potongan", function(){
        var row = $(this).closest('.row')
        var hr = row.parent().find('hr').remove()
        var value = row.children('#id_pot').val()
        if(countIdPotongan > 1){
            $(this).closest('.row').remove()
            $(this).closest('.row').parent().find('hr').remove()
            countIdPotongan--;
        }
    })

    $(".textOnly").keydown(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault();
        }
    })
</script>
@endsection
