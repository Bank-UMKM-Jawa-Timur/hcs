@extends('layouts.app-template')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Kantor Cabang</h2>
                <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500 font-bold">Kantor Cabang</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Tambah</p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group" >
                @csrf
                <div class="input-box">
                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="@error('kode_cabang') is-invalid @enderror form-input" name="kode_cabang" id="kode_cabang" value="{{ old('kode_cabang') }}"> 
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box">
                    <label for="nama_cabang">Nama Kantor Cabang</label>
                    <input type="text" class="@error('nama_cabang') is-invalid @enderror form-input" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}">
                    @error('nama_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box">
                    <label for="alamat_cabang">Alamat Kantor Cabang</label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is-invalid @enderror form-input" value="{{ old('alamat_cabang') }}"></textarea>
                    @error('alamat_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="pt-3 pb-3">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection