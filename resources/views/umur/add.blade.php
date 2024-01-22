@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Tambah Rentang Umur
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('umur.index') }}" class="text-sm text-gray-500">Rentang Umur</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('umur.index') }}" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>

    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col">
                <form action="{{ route('umur.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex align-middle items-center w-full">
                        <div class="w-1/2 input-box">
                            <label for="umur_awal">Umur Awal</label>
                            <input type="number" name="umur_awal" id="umur_awal" class="@error('umur_awal') is_invalid @enderror form-input" value="{{ old('umur_awal') }}">
                            @error('umur_awal')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-60 align-middle items-center text-center" >
                            <label for="" class="w-full font-bold text-xs">Sampai Dengan</label>
                        </div>
                        <div class="w-1/2 input-box">
                            <label for="umur_akhir">Umur Akhir</label>
                            <input type="number" name="umur_akhir" id="umur_akhir" class="@error('umur_akhir') is_invalid @enderror form-input" value="{{ old('umur_akhir') }}">
                            @error('umur_akhir')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-3 pb-3">
                        <button class="btn btn-primary is-btn is-primary" value="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
