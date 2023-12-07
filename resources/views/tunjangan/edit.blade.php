@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Tunjangan</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('tunjangan.index') }}">Tunjangan</a> > <a>Edit</a></p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('tunjangan.update', $data->id) }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="nama_kantot">Nama tunjangan</label>
                    <input type="text" name="nama_tunjangan" id="nama_tunjangan" class="@error('nama_tunjangan') is-invalid @enderror form-control" value="{{ old('nama_tunjangan', $data->nama_tunjangan) }}">
                    @error('nama_tunjangan')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mt-3">
                        <label for="nama_kantot">Kategori</label>
                        <select name="kategori" id="" class="@error('kategori') is-invalid @enderror form-control">
                            <option value="">Pilih Kategori</option>
                            <option value="teratur" {{ old('kategori',$data->kategori) == 'teratur' ? 'selected' : ''  }}>Teratur</option>
                            <option value="tidak teratur" {{ old('kategori',$data->kategori) == 'tidak teratur' ? 'selected' : '' }}>Tidak Teratur</option>
                            <option value="Bonus" {{ old('kategori',$data->kategori) == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        </select>
                        @error('kategori')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-info" value="submit" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
