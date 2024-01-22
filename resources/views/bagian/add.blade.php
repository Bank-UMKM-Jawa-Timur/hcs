@extends('layouts.app-template')
@section('content')
    {{-- <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Tambah Bagian</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('bagian.index') }}">Bagian</a> > <a>Tambah</a></p>
        </div>
    </div> --}}
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Bagian</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('bagian.index') }}" class="text-sm text-gray-500 font-bold">Bagian</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold">Tambah</p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('bagian.store') }}" method="POST" enctype="multipart/form-data" name="bagian" class="form-group">
                @csrf
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="kantor">Nama Kantor</label>
                        <select name="kantor" class="@error('kantor') is-invalid @enderror form-input" id="kantor">
                            <option {{ old('kantor') == "-" ? 'selected' : '' }} value="-">-- Pilih ---</option>
                            <option {{ old('kantor') == 1 ? 'selected' : '' }} value="1">Kantor Pusat</option>
                            <option {{ old('kantor') == 2 ? 'selected' : '' }} value="2">Kantor Cabang</option>
                        </select>
                        @error('kantor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box hidden" id="kantor_row1">

                    </div>
                    <div class="input-box hidden" id="kantor_row2">

                    </div>
                    <div class="input-box">
                        <label for="nama_bagian">Nama Bagian</label>
                        <input type="text" class="@error('nama_bagian') is-invalid @enderror form-input" name="nama_bagian" id="nama_bagian" value="{{ old('nama_bagian') }}">
                        @error('nama_bagian')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="kd_bagian">Kode Bagian</label>
                        <input type="text" name="kd_bagian" id="kd_bagian" class="@error('kd_bagian') is-invalid @enderror form-input" value="{{ old('kd_bagian') }}">
                        @error('kd_bagian')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="pt-4 pb-4 mt-3">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#kantor').change(function(){
            var kantor_id = $(this).val();
            console.log(kantor_id);

            if(kantor_id == 1){
                $.ajax({
                    type: "GET",
                    url: "{{ route('get_divisi') }}",
                    datatype: 'JSON',
                    success: function(res){
                        $("#kantor_row1").removeClass('hidden');
                        $("#kantor_row2").removeClass('hidden');
                        $("#kantor_row1").empty();
                        $("#kantor_row1").append(`
                            <label for="divisi">Divisi</label>
                            <select name="kd_divisi" id="divisi" class="form-input">
                                <option value="">--- Pilih divisi ---</option>
                            </select>`
                        );
                        $.each(res, function(i, item){
                            $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
                        });

                        $("#kantor_row2").append(`
                            <label for="sub_divisi">Sub divisi</label>
                            <select name="kd_subdiv" id="sub_divisi" class="form-input">
                                <option value="">--- Pilih sub divisi ---</option>
                            </select>`
                        );
        
                        $("#divisi").change(function(){
                            var divisi = $(this).val();

                            if(divisi){
                                $.ajax({
                                    type: "GET",
                                    url: "{{ route('get_subdivisi') }}?divisiID="+divisi,
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
                    url: "{{ route('get_cabang') }}",
                    datatype: 'JSON',
                    success: function(res){
                        $("#kantor_row1").empty();
                        $("#kantor_row2").empty();
                        $("#kantor_row1").append(`
                                <div class="form-group">
                                    <label for="kd_cabang">Cabang</label>
                                    <select name="kd_cabang" id="cabang" class="form-control">
                                        <option value="">--- Pilih Cabang ---</option>
                                    </select>
                                </div>`
                        );
                        $.each(res[0], function(i, item){
                            $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.nama_cabang+'</option>')
                        })
                    }
                })
            } else {
                $("#kantor_row1").empty();
                $("#kantor_row2").empty();
            }
        });
    </script>
@endsection