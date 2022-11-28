@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Sub Divisi</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a> > Edit </p>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('sub_divisi.update', $data->kd_subdiv) }}" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="" class="form-control">
                                <option value="-">--- Pilih Divisi ---</option>
                                @foreach ($divisi as $item)
                                    <option value="{{ $item['kd_divisi'] }}" {{ ($item['kd_divisi'] == $data->kd_divisi) ? 'selected' : '' }}>{{ $item['nama_divisi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_subdivisi">Kode Sub Divisi</label>
                            <input type="text" class="form-control" name="kd_subdiv" value="{{ $data->kd_subdiv }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="nama_subdivisi">Nama Sub Divisi</label>
                            <input type="text" class="form-control" name="nama_subdivisi" value="{{ $data->nama_subdivisi }}">
                        </div>
                    </div>
                    <button class="btn btn-info" type="submit" value="submit">edit</button>
                </form>
            </div>
        </div>
    </div>
@endsection