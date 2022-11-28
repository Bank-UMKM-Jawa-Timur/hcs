@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Jabatan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/jabatan">jabatan </a> > Edit</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="container">
                    <form action="{{ route('jabatan.update', $data->id) }}" class="form-group" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label for="nama_kantot">Nama jabatan</label>
                        <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" value="{{ $data->nama_jabatan }}">
                        <button class="btn btn-info" value="submit" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection