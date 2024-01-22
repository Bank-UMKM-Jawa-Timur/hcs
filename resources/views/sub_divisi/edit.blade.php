@extends('layouts.app-template')

@section('content')
    {{-- <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Edit Sub Divisi</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a> > <a>Edit</a></p>
        </div>
    </div> --}}
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Edit Sub Divisi</h2>
                <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('sub_divisi.index') }}" class="text-sm text-gray-500 font-bold">Sub Divisi</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Edit</p>
                </div>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('sub_divisi.update', $data->kd_subdiv) }}" method="POST" enctype="multipart/form-data" class="form-group">
                @csrf
                @method('PUT')
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="divisi">Divisi</label>
                        <select name="divisi" id="" class="@error('divisi') is-invalid @enderror form-input">
                            <option value="-">--- Pilih Divisi ---</option>
                            @foreach ($divisi as $item)
                                <option value="{{ $item['kd_divisi'] }}" {{ ($item['kd_divisi'] == $data->kd_divisi) ? 'selected' : '' }}>{{ $item['nama_divisi'] }}</option>
                            @endforeach
                        </select>
                        @error('divisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="nama_subdivisi">Kode Sub Divisi</label>
                        <input type="text" class=" @error('kd_subdiv') @enderror form-input" name="kd_subdiv" value="{{ old('kd_subdiv', $data->kd_subdiv) }}">
                        @error('kd_subdiv')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="nama_subdivisi">Nama Sub Divisi</label>
                        <input type="text" class="@error('nama_subdivisi') @enderror form-input" name="nama_subdivisi" value="{{ old('nama_subdivisi', $data->nama_subdivisi) }}">
                        @error('nama_subdivisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="pt-5 pb-5 mt-3">
                    <button class="btn btn-primary" type="submit" value="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection