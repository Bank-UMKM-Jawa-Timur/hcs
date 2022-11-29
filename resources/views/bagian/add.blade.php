@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Bagian</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/bagian">Bagian </a> > Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('bagian.store') }}" method="POST" enctype="multipart/form-data" name="bagian" class="form-group">
                    @csrf
                    <div class="row m-0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kantor">Nama Kantor</label>
                                <select name="kantor" class="form-control" id="kantor">
                                    <option value="">-- Pilih ---</option>
                                    <option value="1">Kantor Pusat</option>
                                    <option value="2">Kantor Cabang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col" id="kantor_row1">

                        </div>
                        <div class="col" id="kantor_row2">

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_cabang">Nama Bagian</label>
                                <input type="text" class="form-control" name="nama_bagian" id="nama_bagian">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_bagian">Kode Bagian</label>
                                <input type="text" name="kd_bagian" id="kode_bagian" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-info">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#kantor').change(function(){
            var kantor_id = $(this).val();

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
                                    <select name="kd_divisi" id="divisi" class="form-control">
                                        <option value="">--- Pilih divisi ---</option>
                                    </select>
                                </div>`
                        );
                        $.each(res, function(i, item){
                            $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
                        });

                        $("#kantor_row2").append(`
                                <div class="form-group">
                                    <label for="sub_divisi">Sub divisi</label>
                                    <select name="kd_subdiv" id="sub_divisi" class="form-control">
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
                                    success: function(res){
                                        $('#sub_divisi').empty();
                                        $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
                                        $.each(res, function(i, item){
                                            $('#sub_divisi').append('<option value="'+item.kd_subdiv+'">'+item.nama_subdivisi+'</option>')
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
                        $.each(res, function(i, item){
                            $('#cabang').append('<option value="'+item.id+'">'+item.nama_cabang+'</option>')
                        })
                    }
                })
            }
        });
    </script>
@endsection