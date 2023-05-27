@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Divisi</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('divisi.index') }}">Divisi</a> > <a>Tambah</a></p>
        </div>
    </div>   
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('divisi.store') }}" method="POST" enctype="multipart/form-data" name="divisi" class="form-group">
                    @csrf
                        <label for="kode_divisi">Kode Divisi</label>
                        <input type="text" class="@error('kode_divisi') is-invalid @enderror form-control" name="kode_divisi" id="kode_divisi"  value="{{ old('kode_divisi') }}">
                        @error('kode_divisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="nama_divisi">Nama Divisi</label>
                        <input type="text" class="@error('nama_divisi') is-invalid @enderror form-control" name="nama_divisi" id="nama_divisi" value="{{ old('nama_divisi') }}">
                        @error('nama_divisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <button class="btn btn-info ">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection