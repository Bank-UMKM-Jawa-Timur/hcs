@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Kantor Cabang</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/cabang">Kantor Cabang </a> > Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="form-control" name="kode_cabang" id="kode_cabang">

                    <label for="nama_cabang">Nama Kantor Cabang</label>
                    <input type="text" class="form-control" name="nama_cabang" id="nama_cabang">

                    <label for="alamat_cabang">Alamat Kantor Cabang</label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="form-control"></textarea>
                    <button class="btn btn-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection