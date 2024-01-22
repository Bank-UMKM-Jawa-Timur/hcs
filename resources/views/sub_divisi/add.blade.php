@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Sub Divisi</h2>
                <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('sub_divisi.index') }}" class="text-sm text-gray-500 font-bold">Sub Divisi</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Tambah</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('sub_divisi.store') }}" method="POST" enctype="multipart/form-data" class="form-group">
                @csrf
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="divisi">Divisi</label>
                        <select name="divisi" id="" class="@error('divisi') is-invalid @enderror form-input">
                            <option value="-">--- Pilih Divisi ---</option>
                            @foreach ($divisi as $item)
                                <option {{ old('divisi') == $item['kd_divisi'] ? "selected" : "--- Pilih Divisi ---"}} value="{{ $item['kd_divisi'] }}">{{ $item['nama_divisi'] }}</option>
                            @endforeach
                        </select>
                        @error('divisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <label for="kd_subdiv">Kode Sub Divisi</label>
                        <input type="text" class="@error('kd_subdiv') is-invalid @enderror form-input" name="kd_subdiv" value="{{ old('kd_subdiv') }}">
                        @error('kd_subdiv')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <label for="nama_subdivisi">Nama Sub Divisi</label>
                        <input type="text" class="@error('nama_subdivisi') is-invalid @enderror form-input" name="nama_subdivisi" value="{{ old('nama_subdivisi') }}">
                        @error('nama_subdivisi')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="pt-3 pb-3 mt-3">
                    <button class="btn btn-primary" type="submit" value="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection