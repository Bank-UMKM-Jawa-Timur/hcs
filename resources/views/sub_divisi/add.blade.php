@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Sub Divisi</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a> > <a>Tambah</a></p>
        </div>
    </div>
    
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('sub_divisi.store') }}" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="" class="@error('divisi') is-invalid @enderror form-control">
                                <option value="-">--- Pilih Divisi ---</option>
                                @foreach ($divisi as $item)
                                    <option {{ old('divisi') == $item['kd_divisi'] ? "selected" : "--- Pilih Divisi ---"}} value="{{ $item['kd_divisi'] }}">{{ $item['nama_divisi'] }}</option>
                                @endforeach
                            </select>
                            @error('divisi')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="kd_subdiv">Kode Sub Divisi</label>
                            <input type="text" class="@error('kd_subdiv') is-invalid @enderror form-control" name="kd_subdiv" value="{{ old('kd_subdiv') }}">
                            @error('kd_subdiv')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="nama_subdivisi">Nama Sub Divisi</label>
                            <input type="text" class="@error('nama_subdivisi') is-invalid @enderror form-control" name="nama_subdivisi" value="{{ old('nama_subdivisi') }}">
                            @error('nama_subdivisi')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-info" type="submit" value="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection