@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Kantor Cabang</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="/cabang">Kantor Cabang</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.update', $data->kd_cabang) }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="@error('kode_cabang') is_invalid @enderror form-control" value="{{ old('kode_cabang', $data->kd_cabang) }}" name="kode_cabang" id="kode_cabang">
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="nama_cabang">Nama Kantor Cabang</label>
                    <input type="text" class="@error('nama_cabang') is_invalid @enderror form-control" value="{{ old('nama_cabang', $data->nama_cabang) }}" name="nama_cabang" id="nama_cabang">
                    @error('nama_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <label for="alamat_cabang">Alamat Kantor Cabang</label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is_invalid @enderror form-control">{{ old('alamat_cabang', $data->alamat_cabang ) }}</textarea>
                    @error('alamat_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button class="btn btn-info">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection