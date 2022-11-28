@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Divisi</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/divisi">Divisi </a> > Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('divisi.store') }}" method="POST" enctype="multipart/form-data" name="divisi" class="form-group">
                    @csrf
                    <label for="kode_divisi">Kode Divisi</label>
                    <input type="text" class="form-control" name="kode_divisi" id="kode_divisi">

                    <label for="nama_divisi">Nama Divisi</label>
                    <input type="text" class="form-control" name="nama_divisi" id="nama_divisi">
                    <button class="btn btn-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection