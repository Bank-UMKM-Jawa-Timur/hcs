@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Edit Divisi</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('divisi.index') }}" class="text-sm text-gray-500 font-bold">Divisi</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold">Edit</p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('divisi.update', $data['kd_divisi']) }}" name="divisi" method="POST" enctype="multipart/form-data" class="form-group">
                @csrf
                @method('PUT')
                <div class="input-box">
                    <label for="kode_divisi">Kode Divisi</label>
                    <input type="text" class="@error('kode_divisi') is-invalid @enderror form-input" name="kode_divisi" id="kode_divisi" value="{{ old('kode_divisi', $data->kd_divisi) }}">
                    @error('kode_divisi')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="input-box mt-5">
                    <label for="nama_divisi">Nama Divisi</label>
                    <input type="text" class="@error('nama_divisi') is-invalid @enderror form-input" name="nama_divisi" id="nama_divisi" value="{{ old('nama_divisi', $data->nama_divisi) }}">
                    @error('nama_divisi')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="pt-3 pb-3 mt-3">
                    <button class="btn btn-primary" type="submit" value="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection