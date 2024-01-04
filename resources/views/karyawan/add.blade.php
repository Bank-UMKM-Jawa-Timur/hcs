@php
    $arrayPendidikan = array('SD', 'SMP', 'SLTP', 'SLTA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3');
@endphp
@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Tambah Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="{{ route('karyawan.index') }}">Karyawan</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            <div id="accordion">
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingOne">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Biodata Diri Karyawan</a>
                        </h6>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIP</label>
                                    <input type="text" class="@error('nip') is-invalid @enderror form-control" name="nip" id="" value="{{ old('nip') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="number" class="@error('nik') is-invalid @enderror form-control" name="nik" id="" value="{{ old('nik') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nama Karyawan</label>
                                    <input type="text" class="@error('nama') is-invalid @enderror form-control textOnly" name="nama" id="" value="{{ old('nama') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tempat Lahir</label>
                                    <input type="text" class="@error('tmp_lahir') is-invalid @enderror form-control textOnly" name="tmp_lahir" id="" value="{{ old('tmp_lahir') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="date" class="@error('tgl_lahir') is-invalid @enderror form-control" name="tgl_lahir" id="" value="{{ old('tgl_lahir') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Agama</label>
                                    <select name="agama" id="" class="@error('agama') is-invalid @enderror form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($agama as $item)
                                            <option {{ old('agama') == $item->kd_agama ? 'selected' : '' }} value="{{ $item->kd_agama }}">{{ $item->agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jk" id="" class="@error('jk') is-invalid @enderror form-control">
                                        <option {{ old('jk') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('jk') == 'Laki-Laki' ? 'selected' : '' }} value="Laki-laki">Laki-laki</option>
                                        <option {{ old('jk') == 'Perempuan' ? 'selected' : '' }} value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status pernikahan</label>
                                    <select name="status_pernikahan" id="status" class="@error('status_pernikahan') is-invalid @enderror form-control">
                                        <option value="-">--- Pilih ---</option>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Kewarganegaraan</label>
                                    <select name="kewarganegaraan" id="" class="@error('kewarganegaraan') is-invalid @enderror form-control">
                                        <option {{ old('kewarganegaraan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }} value="WNI">WNI</option>
                                        <option {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }} value="WNA">WNA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat KTP</label>
                                    <textarea name="alamat_ktp" id="" class="@error('alamat_ktp') is-invalid @enderror form-control">{{ old('alamat_ktp') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat sekarang</label>
                                    <textarea name="alamat_sek" id="" class="form-control">{{ old('alamat_sek') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingTwo">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <a class="text-decoration-none" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Data Karyawan</a>
                        </h6>
                    </div>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12" id="#kantor_row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nomor Rekening</label>
                                    <input type="number" class="form-control" name="no_rek" value="{{ old('no_rek') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NPWP</label>
                                    <input type="number" class="form-control" name="npwp" value="{{ old('npwp') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="-">Jabatan</label>
                                    <select name="jabatan" id="jabatan" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($jabatan as $item)
                                            <option value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kantor">Kantor</label>
                                    <select name="kantor" id="kantor" class="form-control">
                                        <option value="-">--- Pilih Kantor ---</option>
                                        <option value="1">Kantor Pusat</option>
                                        <option value="2">Kantor Cabang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="kantor_row1">

                            </div>
                            <div class="col-md-6"  id="kantor_row2">

                            </div>
                            <div class="col-md-6"  id="kantor_row3">

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Pangkat Dan Golongan</label>
                                    <select name="panggol" id="" class="@error('panggol') is-invalid @enderror form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($panggol as $item)
                                            <option {{ old('panggol') == $item->golongan ? 'selected' : '--- Pilih ---' }} value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status Jabatan</label>
                                    <select name="status_jabatan" id="" class="@error('status_jabatan') is-invalid @enderror form-control">
                                        <option {{ old('status_jabatan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('status_jabatan') == 'Definitif' ? 'selected' : '' }} value="Definitif">Definitif</option>
                                        <option {{ old('status_jabatan') == 'Penjabat' ? 'selected' : ''}} value="Penjabat">Penjabat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Keterangan Jabatan</label>
                                    <input type="text" class="form-control" name="ket_jabatan" value="{{ old('ket_jabatan') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">KPJ</label>
                                    <input type="text" class="@error('kpj') is-invalid @enderror form-control" name="kpj" value="{{ old('kpj') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">JKN</label>
                                    <input type="number" class="@error('jkn') is-invalid @enderror form-control" name="jkn" value="{{ old('jkn') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Honorarium</label>
                                    <input type="text" id="gj_pokok" class="@error('gj_pokok') is-invalid @enderror form-control" name="gj_pokok" value="{{ old('gj_pokok') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Honorarium Penyesuaian</label>
                                    <input type="text" id="gj_penyesuaian" class="form-control" name="gj_penyesuaian" value="{{ old('gj_penyesuaian') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status Karyawan</label>
                                    <select name="status_karyawan" id="" class="@error('status_karyawan') is-invalid @enderror form-control">
                                        <option {{ old('status_karyawan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('status_karyawan') == 'Tetap' ? 'selected' : '' }} value="Tetap">Tetap</option>
                                        <option {{ old('status_karyawan') == 'IKJP' ? 'selected' : '' }} value="IKJP">IKJP</option>
                                        <option {{ old('status_karyawan') == 'Kontrak Perpanjangan' ? 'selected' : '' }} value="Kontrak Perpanjangan">Kontrak Perpanjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Mulai</label>
                                    <input type="date" class="@error('tgl_mulai') is-invalid @enderror form-control" name="tgl_mulai" value="{{ old('tgl_mulai') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">SK Pengangkatan</label>
                                    <input type="text" class="@error('skangkat') is-invalid @enderror form-control" name="skangkat" value="{{ old('skangkat') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Pengangkatan</label>
                                    <input type="date" class="@error('tanggal_pengangkat') is-invalid @enderror form-control" name="tanggal_pengangkat" value="{{ old('tanggal_pengangkat') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendidikan">Pendidikan</label>
                                    <select name="pendidikan" class="form-control" id="">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($arrayPendidikan as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendidikan_major">Pendidikan Major</label>
                                    <input type="text" class="@error('pendidikan_major') is-invalid @enderror form-control" name="pendidikan_major" value="{{ old('pendidikan_major') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow" id="data_is">
                    <div class="card-header" id="headingThree">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Data Keluarga</a>
                        </h6>
                    </div>

                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is">Pasangan</label>
                                    <select name="is" id="is" class="form-control">
                                        <option {{ old('is') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('is') == 'Suami' ? 'selected' : '' }} value="Suami">Suami</option>
                                        <option {{ old('is') == 'Istri' ? 'selected' : '' }} value="Istri">Istri</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_nama">Nama</label>
                                    <input type="text" name="is_nama" class="form-control" value="{{ old('is_nama') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sk_tunjangan">SK Tunjangan</label>
                                    <input type="text" class="form-control" name="sk_tunjangan_is">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_tgl_lahir">Tanggal Lahir</label>
                                    <input type="date" name="is_tgl_lahir" class="form-control" value="{{ old('is_tgl_lahir') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="is_alamat">Alamat</label>
                                <textarea name="is_alamat" class="form-control">{{ old('is_alamat') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="is_pekerjaan">Pekerjaan</label>
                                <input type="text" class="form-control" name="is_pekerjaan" value="{{ old('is_pekerjaan') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="is_jumlah_anak">Jumlah Anak</label>
                                <input type="number" class="form-control" id="is_jml_anak" name="is_jml_anak" value="{{ old('is_jumlah_anak') }}">
                            </div>
                            <hr>
                            <div class="col-md-12 mt-3" id="row_anak">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingFour">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">Data Tunjangan</a>
                        </h6>
                    </div>

                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is">Tunjangan</label>
                                    <select name="tunjangan[]" id="tunjangan" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is_nama">Nominal</label>
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control rupiah" onkeyup="formatRupiah(this)">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingFive">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">Data Potongan</a>
                        </h6>
                    </div>

                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12" id="row_potongan">
                            <div class="col col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="is_nama">Kredit Koperasi</label>
                                    <input type="number" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="is_nama">Iuran Koperasi</label>
                                    <input type="number" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="is_nama">Kredit Pegawai</label>
                                    <input type="number" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="is_nama">Iuran IK</label>
                                    <input type="number" id="potongan_iuran_ik" name="potongan_iuran_ik" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <button type="submit" id="submit" class="is-btn is-primary">Simpan</button>
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
        var countIdPotongan = 1

        $("#is_jml_anak").keyup(function(){
            $("#row_anak").empty();
            var angka = $(this).val()
            if(angka > 2) angka = 2;

            for(var i = 0; i < angka; i++){
                var ket = (i == 0) ? 'Pertama' : 'Kedua';
                $("#row_anak").append(`
                <h6 class="">Data Anak `+ ket +`</h6>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="nama_anak">Nama Anak</label>
                        <input type="text" class="form-control" name="nama_anak[]">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tgl_lahir_anak[]">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="sk_tunjangan_anak">SK Tunjangan</label>
                        <input type="text" class="form-control" name="sk_tunjangan_anak[]">
                    </div>
                </div>
            `);
            }
        })

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
                                <div class="form-group">
                                    <label for="divisi">Divisi</label>
                                    <select name="divisi" id="divisi" class="form-control">
                                        <option value="">--- Pilih divisi ---</option>
                                    </select>
                                </div>`
                        );
                        $.each(res, function(i, item){
                            $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
                        });

                        $("#kantor_row2").empty();

                        $("#kantor_row2").append(`
                                <div class="form-group">
                                    <label for="subdiv">Sub divisi</label>
                                    <select name="subdiv" id="sub_divisi" class="form-control">
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
                                        $("#kantor_row3").addClass("col-md-6");

                                        $("#kantor_row3").append(`
                                                <div class="form-group">
                                                    <label for="bagian">Bagian</label>
                                                    <select name="bagian" id="bagian" class="form-control">
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
                                <div class="form-group">
                                    <label for="Cabang">Cabang</label>
                                    <select name="cabang" id="cabang" class="form-control">
                                        <option value="">--- Pilih Cabang ---</option>
                                    </select>
                                </div>`
                        );
                        $("#kantor_row2").append(`
                            <div class="form-group">
                                <label for="bagian">Bagian</label>
                                <select name="bagian" id="bagian" class="form-control">
                                    <option value="">--- Pilih bagian ---</option>
                                </select>
                            </div>
                        `)

                        $("#kantor_row3").empty()
                        $("#kantor_row3").removeClass("col-md-6")
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
            $("#kantor_row2").show();
            if(value == "PIMDIV"){
                $("#kantor").val("1")
                kantorChange();
                $('#kantor').attr("disabled", "disabled");
                $("kantor_row2").removeClass("col-md-6")
                $("#kantor_row2").hide();
                $("#kantor_row3").hide()
            } else if(value == "PSD"){
                $("#kantor").val("1")
                kantorChange();
                $('#kantor').attr("disabled", "disabled");
            } else if(value == "PC" || value == "PBP"){
                $("#kantor").val("2")
                kantorChange();
                $('#kantor').attr("disabled", "disabled");
                $("#kantor_row2").hide();
            } else if(value == "PBO"){
                kantorChange();
                $('#kantor').removeAttr("disabled")
                $("kantor_row2").removeClass("col-md-6")
                $("#kantor_row2").hide();
                $("#kantor_row3").hide()
            } else if(value == "-"){
                kantorChange();
            }else {
                $('#kantor').removeAttr("disabled")
            }
        })

        $('#kantor').change(function(){
            kantorChange();
        });

        $('#data_is').hide();

        $('#status').change(function(){
            var stat = $(this).val();

            if(stat == 'Kawin'){
                $('#data_is').show();
            } else{
                $('#data_is').hide();
            }
        })

        $('#collapseFour').on('click', "#btn-add", function(){
            $('#collapseFour').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is">Tunjangan</label>
                                    <select name="tunjangan[]" id="tunjangan" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is_nama">Nominal</label>
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control rupiah" onkeyup="formatRupiah($(this).val())">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
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
                        <div class="form-group">
                            <label for="is">Tahun</label>
                            <select name="potongan_tahun[]" id="potongan_tahun"
                                class="form-control">
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
                        <div class="form-group">
                            <label for="is">Bulan</label>
                            <select name="potongan_bulan[]" id="potongan_bulan"
                                class="form-control">
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
                        <div class="form-group">
                            <label for="is_nama">Kredit Koperasi</label>
                            <input type="number" id="potongan_kredit_koperasi" name="potongan_kredit_koperasi[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="is_nama">Iuran Koperasi</label>
                            <input type="number" id="potongan_iuran_koperasi" name="potongan_iuran_koperasi[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="is_nama">Kredit Pegawai</label>
                            <input type="number" id="potongan_kredit_pegawai" name="potongan_kredit_pegawai[]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="is_nama">Iuran IK</label>
                            <input type="number" id="potongan_iuran_ik" name="potongan_iuran_ik[]" class="form-control" value="">
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
