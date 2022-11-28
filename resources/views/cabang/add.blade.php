@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Kantor Cabang</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/cabang">Kantor Cabang </a> > Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="nama_cabang">Nama Kantor Cabang</label>
                            <input type="text" class="form-control" name="nama_cabang" id="nama_cabang">
                        </div>
                        <div class="col-lg-6">
                            <label for="alamat_cabang">Alamat Kantor Cabang</label>
                            <textarea name="alamat_cabang" id="alamat_cabang" class="form-control"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-info">submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection