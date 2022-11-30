@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Kantor Cabang</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/cabang">Kantor Cabang </a> > Edit</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.update', $data->kd_cabang) }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="form-control" value="{{ $data->kd_cabang }}" name="kode_cabang" id="kode_cabang" disabled>

                    <label for="nama_cabang">Nama Kantor Cabang</label>
                    <input type="text" class="form-control" value="{{ $data->nama_cabang }}" name="nama_cabang" id="nama_cabang">

                    <label for="alamat_cabang">Alamat Kantor Cabang</label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="form-control">{{ $data->alamat_cabang }}</textarea>
                    <button class="btn btn-info">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection