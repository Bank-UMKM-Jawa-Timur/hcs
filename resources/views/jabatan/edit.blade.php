@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Jabatan</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('jabatan.index') }}">Jabatan</a> > <a>Edit</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('jabatan.update', $data->kd_jabatan) }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="nama_kantot">Kode Jabatan</label>
                            <input type="text" name="kd_jabatan" id="kd_jabatan" class="@error('kd_jabatan') is-invalid @enderror form-control" value="{{ old('kd_jabatan', $data->kd_jabatan) }}">
                            @error('kd_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_kantot">Nama Jabatan</label>
                            <input type="text" name="nama_jabatan" id="nama_jabatan" class="@error('nama_jabatan') @enderror form-control" value="{{ old('nama_jabatan', $data->nama_jabatan) }}">
                            @error('nama_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        <div class="pt-4 pb-3">
                            <button class="is-btn is-primary-light" value="submit" type="submit">Update</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection