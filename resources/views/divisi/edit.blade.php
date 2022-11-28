@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Divisi</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/divisi">Divisi </a> > Edit</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('divisi.update', $data['kd_divisi']) }}" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="kode_divisi">Kode Divisi</label>
                    <input type="text" class="form-control" name="kode_divisi" id="kode_divisi" value="{{ $data['kd_divisi'] }}">

                    <label for="nama_divisi">Nama Divisi</label>
                    <input type="text" class="form-control" name="nama_divisi" id="nama_divisi" value="{{ $data['nama_divisi'] }}">
                    <button class="btn btn-info" type="submit" value="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection