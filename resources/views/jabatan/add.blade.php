@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Jabatan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('jabatan.index') }}" class="text-sm text-gray-500 font-bold">Jabatan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold">Tambah</p>
                </div>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
                <form action="{{ route('jabatan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                        <div class="input-box">
                            <label for="nama_kantot">Kode Jabatan</label>
                            <input type="text" name="kd_jabatan" id="kd_jabatan" class="@error('kd_jabatan') is-invalid @enderror form-input" value="{{ old('kd_jabatan') }}">
                            @error('kd_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="nama_kantot">Nama Jabatan</label>
                            <input type="text" name="nama_jabatan" id="nama_jabatan" class="@error('nama_jabatan') is-invalid @enderror form-input" value="{{ old('nama_jabatan') }}">
                            @error('nama_jabatan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        <div class="pt-3 pb-3 mt-3">
                            <button class="btn btn-primary" value="submit" type="submit">Simpan</button>
                        </div>
                </form>
        </div>
    </div>
@endsection