@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Promosi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/promosi">Promosi</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('promosi.store') }}" method="POST" enctype="multipart/form-data" name="divis" class="form-group">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" name="nip" id="karyawan" class="@error('nip') is-invalid @enderror form-control" value="{{ old('nip') }}">
                        @error('nip')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="" id="nama_karyawan_show" disabled class="form-control" value="{{ old('nama_karyawan') }}">
                        <input type="hidden" name="nama_karyawan" id="nama_karyawan" value="{{ old('nama_karyawan') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" disabled class="form-control" name="" id="jabatan_show" value="{{ old('jabatan_lama') }}">
                        <input type="hidden" name="jabatan_lama" id="jabatan_lama" value="{{ old('jabatan_lama') }}">
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Kantor Lama</label>
                        <input type="text" disabled class="form-control" name="" id="kantor_lama_show" value="{{ old('kantor_lama') }}">
                        <input type="hidden" name="kantor_lama" id="kantor_lama" value="{{ old('kantor_lama') }}">
                    </div>
                </div>   
            </div>
            <hr>
            <div class="row" id="#kantor_row">
                <div class="col-lg-12">
                    <h6>Jabatan Baru</h6>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jabatan</label>
                        <select name="jabatan_baru" id="jabatan_baru" class="@error('jabatan_baru') is-invalid @enderror form-control">
                            <option value="-">--- Pilih ---</option>
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        @error('jabatan_baru')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kantor">Kantor</label>
                        <select name="kantor" class="@error('kantor') is-invalid @enderror form-control" id="kantor">
                            <option value="-">-- Pilih ---</option>
                            <option value="1">Kantor Pusat</option>
                            <option value="2">Kantor Cabang</option>
                        </select>
                        @error('kantor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4" id="kantor_row1">

                </div>
                <div class="col-md-4"  id="kantor_row2">
                    
                </div>
                <div class="col-md-4"  id="kantor_row3">
                    
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pengesahan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="form-control" name="@error('tanggal_pengesahan') @enderror tanggal_pengesahan" id="" value="{{ old('tanggal_pengesahan') }}">
                        @error('tanggal_pengesahan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class= "col-md-6">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <input type="text" class="@error('bukti_sk') @enderror form-control" name="bukti_sk" id="inputGroupFile01" value="{{ old('bukti_sk') }}">
                        @error('bukti_sk')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Keterangan Jabatan</label>
                        <textarea name="ket_jabatan" class="form-control" id="ket_jabatan"></textarea>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#karyawan').change(function(e){
            var nip = $(this).val();
            $.ajax({
            type: "GET",
            url: "/getdatapromosi?nip="+nip,
            datatype: "json",
            success: function(res){
                $("#nama_karyawan_show").val(res.nama_karyawan)
                $("#nama_karyawan").val(res.nama_karyawan)
                $("#jabatan_lama").val(res.kd_jabatan)
                $("#jabatan_show").val(res.nama_jabatan)
                $("#kantor_lama_show").val(res.kd_entitas)
                $("#kantor_lama").val(res.kd_entitas)
            }
           }) 
        });

        let kantor = $('#kantor_row');
        $('#kantor').attr("disabled", "disabled");
        var x =1;

        function kantorChange(){
            var kantor_id = $("#kantor").val();

            if(kantor_id == 1){
                $.ajax({
                    type: "GET",
                    url: '/getdivisi',
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
                                    url: "/getsubdivisi?divisiID="+divisi,
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
                                                url: "/getbagian?kd_entitas="+$(this).val(),
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
                    url: '/getcabang',
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

        $("#jabatan_baru").change(function(){
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
    </script>
@endsection