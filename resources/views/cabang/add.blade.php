@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Tambah Kantor Cabang</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</a> > <a>Tambah</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group" >
                    @csrf
                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="@error('kode_cabang') is-invalid @enderror form-control" name="kode_cabang" id="kode_cabang" value="{{ old('kode_cabang') }}"> 
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="nama_cabang">Nama Kantor Cabang</label>
                    <input type="text" class="@error('nama_cabang') is-invalid @enderror form-control" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}">
                    @error('nama_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="alamat_cabang">Alamat Kantor Cabang</label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is-invalid @enderror form-control" value="{{ old('alamat_cabang') }}"></textarea>
                    @error('alamat_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="pt-3 pb-3">
                        <button class="is-btn is-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection