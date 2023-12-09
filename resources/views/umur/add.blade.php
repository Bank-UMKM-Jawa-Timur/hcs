@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Tambah Rentang Umur</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('umur.index') }}">Rentang Umur</a> > <a>Tambah</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('umur.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label for="umur_awal">Umur Awal</label>
                            <input type="number" name="umur_awal" id="umur_awal" class="@error('umur_awal') is_invalid @enderror form-control" value="{{ old('umur_awal') }}">
                            @error('umur_awal')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2" style="text-align: center; margin-top: 32px; font-size: 16px; font-weight: bold">
                            <label for="">Sampai Dengan</label>
                        </div>
                        <div class="col-md-5">
                            <label for="umur_akhir">Umur Akhir</label>
                            <input type="number" name="umur_akhir" id="umur_akhir" class="@error('umur_akhir') is_invalid @enderror form-control" value="{{ old('umur_akhir') }}">
                            @error('umur_akhir')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-3 pb-3">
                        <button class="is-btn is-primary" value="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection