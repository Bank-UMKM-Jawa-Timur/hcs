@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Tunjangan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/tunjangan">tunjangan </a> > Edit</p>
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
                    <input type="text" name="nama_tunjangan" id="nama_tunjangan" class="form-control" value="{{ $data->nama_tunjangan }}">
                    <button class="btn btn-info" value="submit" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection