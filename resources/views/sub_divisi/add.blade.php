@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard </a> - <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a> - Tambah </p>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('sub_divisi.store') }}" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="" class="form-control">
                                <option value="-">--- Pilih Divisi ---</option>
                                @foreach ($divisi as $item)
                                    <option value="{{ $item['kd_divisi'] }}">{{ $item['nama_divisi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_subdivisi">Kode Sub Divisi</label>
                            <input type="text" class="form-control" name="kd_subdiv">
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_subdivisi">Nama Sub Divisi</label>
                            <input type="text" class="form-control" name="nama_subdivisi">
                        </div>
                    </div>
                    <button class="btn btn-info" type="submit" value="submit">tambah</button>
                </form>
            </div>
        </div>
    </div>
@endsection