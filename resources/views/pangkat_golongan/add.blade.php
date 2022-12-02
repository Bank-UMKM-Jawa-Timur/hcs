@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Tambah Pangkat Dan Golongan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/pangkat_golongan">Pangkat Dan Golongan </a> > Tambah</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                    <form action="{{ route('pangkat_golongan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="pangkat">Pangkat</label>
                                <input type="text" name="pangkat" id="pangkat" class="@error('pangkat') is-invalid @enderror form-control" value="{{ old('pangkat') }}">
                                @error('pangkat')
                                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="Golongan">Golongan</label>
                                <input type="text" name="golongan" id="golongan" class="@error('golongan') is-invalid @enderror form-control" value="{{ old('golongan') }}">
                                @error('golongan')
                                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-info" value="submit" type="submit">Simpan</button>
                    </form>
            </div>
        </div>
    </div>
@endsection