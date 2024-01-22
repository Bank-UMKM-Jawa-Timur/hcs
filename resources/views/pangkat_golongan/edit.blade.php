@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Edit Pangkat Dan Golongan</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('pangkat_golongan.index') }}" class="text-sm text-gray-500 font-bold">Pangkat Dan
                    Golongan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Edit</p>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('pangkat_golongan.update', $data->golongan) }}" class="form-group" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="pangkat">Pangkat</label>
                    <input type="text" name="pangkat" id="pangkat"
                        class="@error('pangkat') is-invalid @enderror form-input"
                        value="{{ old('pangkat', $data->pangkat) }}">
                    @error('pangkat')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <label for="Golongan">Golongan</label>
                    <input type="text" name="golongan" id="golongan"
                        class="@error('golongan') is-invalid @enderror form-input"
                        value="{{ old('golongan', $data->golongan) }}">
                    @error('golongan')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="pt-4 pb-3 mt-3">
                <button class="btn btn-primary" value="submit" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection