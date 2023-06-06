@extends('layouts.template')
@include('vendor.select2')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Pengkinian Data Karyawan</h5>
            <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > <a href="{{ route('pengkinian_data.index') }}">Pengkinian Data</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('pengkinian_data.store') }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
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
                                    <label for="">NIP:</label>
                                    <select name="nip" id="nip" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="number" class="@error('nik') is-invalid @enderror form-control" name="nik" id="nik" value="{{ old('nik') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nama Karyawan</label>
                                    <input type="text" class="@error('nama') is-invalid @enderror form-control" name="nama" id="nama" value="{{ old('nama') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tempat Lahir</label>
                                    <input type="text" class="@error('tmp_lahir') is-invalid @enderror form-control" name="tmp_lahir" id="tmp_lahir" value="{{ old('tmp_lahir') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="date" class="@error('tgl_lahir') is-invalid @enderror form-control" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Agama</label>
                                    <select name="agama" id="agama" class="@error('agama') is-invalid @enderror form-control">
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
                                    <select name="jk" id="jk" class="@error('jk') is-invalid @enderror form-control">
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
                                    <select name="kewarganegaraan" id="kewarganegaraan" class="@error('kewarganegaraan') is-invalid @enderror form-control">
                                        <option {{ old('kewarganegaraan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }} value="WNI">WNI</option>
                                        <option {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }} value="WNA">WNA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat KTP</label>
                                    <textarea name="alamat_ktp" id="alamat_ktp" class="@error('alamat_ktp') is-invalid @enderror form-control">{{ old('alamat_ktp') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat sekarang</label>
                                    <textarea name="alamat_sek" id="alamat_sekarang" class="form-control">{{ old('alamat_sek') }}</textarea>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nomor Rekening</label>
                                    <input type="number" class="form-control" name="no_rek" value="{{ old('no_rek') }}" id="no_rek">
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
                                    <select name="panggol" id="kd_panggol" class="@error('panggol') is-invalid @enderror form-control">
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
                                    <select name="status_jabatan" id="status_jabatan" class="@error('status_jabatan') is-invalid @enderror form-control">
                                        <option {{ old('status_jabatan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('status_jabatan') == 'Definitif' ? 'selected' : '' }} value="Definitif">Definitif</option>
                                        <option {{ old('status_jabatan') == 'Penjabat' ? 'selected' : ''}} value="Penjabat">Penjabat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Keterangan Jabatan</label>
                                    <input type="text" class="form-control" name="ket_jabatan" value="{{ old('ket_jabatan') }}" id="ket_jabatan">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">KPJ</label>
                                    <input type="text" class="@error('kpj') is-invalid @enderror form-control" name="kpj" value="{{ old('kpj') }}" id="kpj">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">JKN</label>
                                    <input type="number" class="@error('jkn') is-invalid @enderror form-control" name="jkn" value="{{ old('jkn') }}" id="jkn">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gaji Pokok</label>
                                    <input type="text" id="gj_pokok" class="@error('gj_pokok') is-invalid @enderror form-control" name="gj_pokok" value="{{ old('gj_pokok') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gaji Penyesuaian</label>
                                    <input type="text" id="gj_penyesuaian" class="form-control" name="gj_penyesuaian" value="{{ old('gj_penyesuaian') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Status Karyawan</label>
                                    <select name="status_karyawan" id="status_karyawan" class="@error('status_karyawan') is-invalid @enderror form-control">
                                        <option {{ old('status_karyawan') == '-' ? 'selected' : '' }} value="-">--- Pilih ---</option>
                                        <option {{ old('status_karyawan') == 'Tetap' ? 'selected' : '' }} value="Tetap">Tetap</option>
                                        <option {{ old('status_karyawan') == 'IKJP' ? 'selected' : '' }} value="IKJP">IKJP</option>
                                        <option {{ old('status_karyawan') == 'Kontrak Perpanjangan' ? 'selected' : '' }} value="Kontrak Perpanjangan">Kontrak Perpanjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">SK Pengangkatan</label>
                                    <input type="text" class="@error('skangkat') is-invalid @enderror form-control" name="skangkat" value="{{ old('skangkat') }}" id="skangkat">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Pengangkatan</label>
                                    <input type="date" class="@error('tanggal_pengangkat') is-invalid @enderror form-control" name="tanggal_pengangkat" value="{{ old('tanggal_pengangkat') }}" id="tanggal_pengangkat">
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
                                    <input type="text" name="is_nama" class="form-control" value="{{ old('is_nama') }}" id="is_nama">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sk_tunjangan">SK Tunjangan</label>
                                    <input type="text" class="form-control" name="sk_tunjangan_is" id="sk_tunjangan_is">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_tgl_lahir">Tanggal Lahir</label>
                                    <input type="date" name="is_tgl_lahir" class="form-control" value="{{ old('is_tgl_lahir') }}" id="is_tgl_lahir">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="is_alamat">Alamat</label>
                                <textarea name="is_alamat" class="form-control" id="is_alamat">{{ old('is_alamat') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="is_pekerjaan">Pekerjaan</label>
                                <input type="text" class="form-control" name="is_pekerjaan" value="{{ old('is_pekerjaan') }}" id="is_pekerjaan">
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
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
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
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control">
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
            </div>

            <div class="row m-3">
                <button type="submit" id="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        let kantor = $('#kantor_row');
        let status = $('#status');
        $('#kantor').attr("disabled", "disabled");
        let nip;
        var x =1;
        let dataJmlAnak = [];
        var subdiv;
        var bag;
        var bagian;

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
                                        console.log(res1);
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
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control">
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

        // Function to get all data karyawan
        $("#nip").change(() => {
            nip = $("#nip").val()

            $.ajax({
                url: "{{ route('get-data-karyawan-by-nip') }}?nip="+nip,
                datatype: "JSON",
                type: "GET",
                success: (res) => {
                    $("#nik").val(res.data.nik)
                    $("#nama").val(res.data.nama_karyawan)
                    $("#tmp_lahir").val(res.data.tmp_lahir)
                    $("#tgl_lahir").val(res.data.tgl_lahir)
                    $("#agama").val(res.data.kd_agama)
                    $("#alamat_ktp").val(res.data.alamat_ktp)
                    $("#alamat_sek").val(res.data.alamat_sek)
                    $("#gj_penyesuaian").val(formatRupiah(res.data.gj_penyesuaian.toString()))
                    $("#gj_pokok").val(formatRupiah(res.data.gj_pokok.toString()))
                    $("#jk").val(res.data.jk)
                    $("#jkn").val(res.data.jkn)
                    $("#kd_panggol").val(res.data.kd_panggol)
                    $("#ket").val(res.data.ket)
                    $("#ket_jabatan").val(res.data.ket_jabatan)
                    $("#kewarganegaraan").val(res.data.kewarganegaraan)
                    $("#kpj").val(res.data.kpj)
                    $("#no_rek").val(res.data.no_rekening)
                    $("#npwp").val(res.data.npwp)
                    $("#skangkat").val(res.data.skangkat)
                    $("#status").val(res.data.status)
                    $("#status_jabatan").val(res.data.status_jabatan)
                    $("#status_karyawan").val(res.data.status_karyawan)
                    $("#tanggal_pengangkat").val(res.data.tanggal_pengangkat)
                    $("#tgl_lahir").val(res.data.tgl_lahir)
                    $("#tgl_mulai").val(res.data.tgl_mulai)
                    $("#jabatan").val(res.data.kd_jabatan)
                    getKantor()
                    cekStatus()

                    if(res.data_anak.length > 0){
                        dataJmlAnak = res.data_anak
                    } else{
                        dataJmlAnak = []
                    }

                    if(res.data.kd_bagian != null){
                        bagian = res.data.kd_bagian;
                    } else{
                        bagian = null
                    }

                    if(res.data.tunjangan.length > 0){
                        $("#collapseFour").empty()
                        $.each(res.data.tunjangan, (i, val) => {
                            $('#collapseFour').append(`
                                <div class="row m-0 pb-3 col-md-12">
                                <input type="hidden" name="id_tk[]" id="id_tk" value="${val.id}">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="is">Tunjangan</label>
                                                        <select name="tunjangan[]" id="tunjangan" class="form-control">
                                                            <option value="">--- Pilih ---</option>
                                                            @foreach ($tunjangan as $item)
                                                                <option value="{{ $item->id }}" ${(val.id_tunjangan == {{ $item->id }}) ? 'selected' : ''}>{{ $item->nama_tunjangan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="is_nama">Nominal</label>
                                                        <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control" value="${formatRupiah(val.nominal.toString())}">
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
                        })
                    } else{
                        $("#collapseFour").empty()
                        $('#collapseFour').append(`
                            <div class="row m-0 pb-3 col-md-12">
                                    <input type="hidden" name="id_tk[]" id="id_tk" value="">
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
                                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control">
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
                    }
                }
            })
        });

        function getKantor(){
            $.ajax({
                type: "GET",
                url: "{{ route('getKantorKaryawan') }}?nip="+nip,
                datatype: "json",
                success: function(res){
                    console.log(res.kantor);
                    if(res.kantor == "Pusat"){
                        $("#kantor").val(1)
                        kantorChange(res.div.kd_divisi)
                        if(res.subdiv != null){
                            subdiv = res.subdiv;
                        }
                        if(res.bag != null){
                            bag = res.bag.kd_bagian
                        }
                    } else if(res.kantor == "Cabang"){
                        $("#kantor").val(2).change()
                        kantorChange(res.kd_kantor)
                        if(res.bag != null){
                            bag = res.bag.kd_bagian
                        }
                    }
                }
            })
        }

        function kantorChange(kd_div){
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
                            $('#divisi').append('<option value="'+item.kd_divisi+'" '+ (kd_div === item.kd_divisi ? 'selected' : '') +'>'+item.nama_divisi+'</option>')
                        });
                        var value = $('#divisi').val();
                        divisiChange(value);

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
                            var value = $(this).val();
                            divisiChange(value);
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
                            $('#cabang').append('<option value="'+item.kd_cabang+'" '+ (kd_div === item.kd_cabang ? 'selected' : '') +'>'+item.nama_cabang+'</option>')
                        })
                        $.each(res[1], function(i, item){
                            $('#bagian').append('<option value="'+item.kd_bagian+'" '+ (bag === item.kd_bagian ? 'selected' : '') +'>'+item.nama_bagian+'</option>')
                        })
                    }
                })
            } else {
                $("#kantor_row1").empty();
                $("#kantor_row2").empty();
                $("#kantor_row3").empty();
            }
        }

        function divisiChange(divisi){
            if(divisi){
                $.ajax({
                    type: "GET",
                    url: "{{ route('get_subdivisi') }}?divisiID="+divisi,
                    datatype: "JSON",
                    success: function(res1){
                        $('#kantor_row2').show();
                        $('#sub_divisi').empty();

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

                        if(res1.length < 1) {
                            $('#kantor_row2').hide();
                            subdivChange(divisi);
                            return;
                        }

                        $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
                        $.each(res1, function(i, item){
                            $('#sub_divisi').append('<option value="'+item.kd_subdiv+'" '+ (subdiv === item.kd_subdiv ? 'selected' : '')  +'>'+item.nama_subdivisi+'</option>')
                        });
                        var val = $('#sub_divisi').val();
                        subdivChange(val, divisi)

                        $("#sub_divisi").change(function(){
                            var val = $(this).val();
                            subdivChange(val)
                        })
                    }
                })
            }
        }

        function subdivChange(kd_subdiv, divisi){
            if(kd_subdiv.length < 1) {
                updateBagianDirectly(divisi);
                return;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('getBagian') }}?kd_entitas="+kd_subdiv,
                datatype: "JSON",
                success: function(res2){
                    $('#bagian').empty();
                    $('#bagian').append('<option value="">--- Pilih Bagian ---</option>')
                    $.each(res2, function(i, item){
                        $('#bagian').append('<option value="'+item.kd_bagian+'" '+ (bag === item.kd_bagian ? 'selected' : '') +'>'+item.nama_bagian+'</option>')
                    });
                }
            })
        }

        function jabatanChange(){
            var value = $("#jabatan").val();
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
            } else if(value == ""){
                kantorChange();
            }else {
                $('#kantor').removeAttr("disabled")
            }
        }

        $("#jabatan").change(function(){
            jabatanChange()
        })

        $('#kantor').change(function(){
            kantorChange();
        });
        
        function cekStatus(){
            if(status.val() == "Kawin"){
                $('#data_is').show();

                $.ajax({
                    type: "GET",
                    url: "{{ route('getIs') }}?nip="+nip,
                    datatype: "json",
                    success: function(res){
                        if(res == null){
                        } else{
                            $('#is').val(res.enum);
                            $('#is_nama').val(res.nama);
                            $('#is_tgl_lahir').val(res.tgl_lahir);
                            $('#is_alamat').val(res.alamat);
                            $('#is_pekerjaan').val(res.pekerjaan);
                            $('#is_jml_anak').val(res.jml_anak);
                            $('#sk_tunjangan_is').val(res.sk_tunjangan);
                            $("#is_jml_anak").trigger('keyup')
                        }
                    }
                })
            }else{
                $('#data_is').hide();
            }
        }

        $("#is_jml_anak").keyup(function(){
            $("#row_anak").empty();
            var angka = $(this).val()
            if(angka > 2) angka = 2;

            for(var i = 0; i < angka; i++){
                var ket = (i == 0) ? 'Pertama' : 'Kedua';
                if(dataJmlAnak.length > 0){
                    $("#row_anak").append(`
                        <h6 class="">Data Anak `+ ket +`</h6>
                        <div class="row">
                            <input type="hidden" name="id_anak[]" value="${dataJmlAnak[i].id}">
                            <div class="col-md-6 form-group">
                                <label for="nama_anak">Nama Anak</label>
                                <input type="text" class="form-control" name="nama_anak[]" value="${dataJmlAnak[i].nama}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tanggal_lahir_anak">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir_anak[]" value="${dataJmlAnak[i].tgl_lahir}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="sk_tunjangan_anak">SK Tunjangan</label>
                                <input type="text" class="form-control" name="sk_tunjangan_anak[]" value="${dataJmlAnak[i].sk_tunjangan}">
                            </div>
                        </div>
                    `);
                } else {
                    $("#row_anak").append(`
                        <h6 class="">Data Anak `+ ket +`</h6>
                        <div class="row">
                            <input type="hidden" name="id_anak[]" value="">
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
            }
        })
        function updateBagianDirectly(divisi) {
            let kd_bagian = bagian;

            $.ajax({
                type: "GET",
                url: "/getbagian?kd_entitas="+divisi,
                datatype: "JSON",
                success: function(res2){
                    $('#bagian').empty();
                    $('#bagian').append('<option value="">--- Pilih Bagian ---</option>')
                    $.each(res2, function(i, item){
                        $('#bagian').append(`<option ${item.kd_bagian == kd_bagian ? 'selected' : ''} value="${item.kd_bagian}">${item.nama_bagian}</option>`);
                    });
                }
            })
        }
    </script>
@endsection

@push('script')
    <script>
        $('#nip').select2({
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
    </script>
@endpush