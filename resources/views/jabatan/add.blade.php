@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Tambah Jabatan</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="/jabatan">Jabatan</a> > <a>Tambah</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('jabatan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="nama_kantot">Kode Jabatan</label>
                            <input type="text" name="kd_jabatan" id="kd_jabatan" class="@error('kd_jabatan') is-invalid @enderror form-control" value="{{ old('kd_jabatan') }}">
                            @error('kd_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_kantot">Nama Jabatan</label>
                            <input type="text" name="nama_jabatan" id="nama_jabatan" class="@error('nama_jabatan') is-invalid @enderror form-control" value="{{ old('nama_jabatan') }}">
                            @error('nama_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        <button class="btn btn-info" value="submit" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection