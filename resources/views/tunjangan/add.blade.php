@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/tunjangan">Tunjangan </a> > Tambah</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="container">
                    <form action="{{ route('tunjangan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="nama_kantot">Nama Tunjangan</label>
                        <input type="text" name="nama_tunjangan" id="nama_tunjangan" class="form-control">
                        <button class="btn btn-info" value="submit" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection