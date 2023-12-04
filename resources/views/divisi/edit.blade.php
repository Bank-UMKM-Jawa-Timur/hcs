@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Edit Divisi</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('divisi.index') }}">Divisi</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('divisi.update', $data['kd_divisi']) }}" name="divisi" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="kode_divisi">Kode Divisi</label>
                    <input type="text" class="@error('kode_divisi') is-invalid @enderror form-control" name="kode_divisi" id="kode_divisi" value="{{ old('kode_divisi', $data->kd_divisi) }}">
                    @error('kode_divisi')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <label for="nama_divisi">Nama Divisi</label>
                    <input type="text" class="@error('nama_divisi') is-invalid @enderror form-control" name="nama_divisi" id="nama_divisi" value="{{ old('nama_divisi', $data->nama_divisi) }}">
                    @error('nama_divisi')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="pt-3 pb-3">
                        <button class="is-btn is-primary-light" type="submit" value="submit">Update</button>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
@endsection