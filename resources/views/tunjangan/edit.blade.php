@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Tunjangan</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="/tunjangan">Tunjangan</a> > <a>Edit</a></p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('tunjangan.update', $data->id) }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="nama_kantot">Nama tunjangan</label>
                    <input type="text" name="nama_tunjangan" id="nama_tunjangan" class="@error('nama_tunjangan') is-invalid @enderror form-control" value="{{ old('nama_tunjangan', $data->nama_tunjangan) }}">
                    @error('nama_tunjangan')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button class="btn btn-info" value="submit" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection