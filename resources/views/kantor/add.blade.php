@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Tambah Kantor</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('kantor.index') }}">Kantor </a> > Tambah</p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <div class="container">
                    <form action="{{ route('kantor.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="nama_kantot">Nama Kantor</label>
                        <input type="text" name="nama_kantor" id="nama_kantor" class="form-control">
                        <button class="btn btn-info" value="submit" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection