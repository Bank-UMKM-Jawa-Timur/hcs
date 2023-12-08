@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Tambah Pangkat Dan Golongan</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('pangkat_golongan.index') }}">Pangkat Dan Golongan</a> > <a>Tambah</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
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
                        <div class="pt-4 pb-3">
                            <button class="is-btn is-primary" value="submit" type="submit">Simpan</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection