@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Tambah Tunjangan
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('tunjangan.index') }}" class="text-sm text-gray-500">Tunjangan</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('tunjangan.index') }}" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>

    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('tunjangan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end p-4">
                    @csrf
                    <div class="input-box">
                        <label for="nama_kantot">Nama Tunjangan</label>
                        <input type="text" name="nama_tunjangan" placeholder="Nama Tunjangan" id="nama_tunjangan" class="@error('nama_tunjangan') is-invalid @enderror form-input" value="{{ old('nama_tunjangan') }}">
                        @error('nama_tunjangan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="nama_kantot">Kategori</label>
                        <select name="kategori" id="" class="@error('kategori') is-invalid @enderror form-input">
                            <option value="">Pilih Kategori</option>
                            <option value="teratur" {{ old('kategori') == 'teratur' ? 'selected' : ''  }}>Teratur</option>
                            <option value="tidak teratur" {{ old('kategori') == 'tidak teratur' ? 'selected' : '' }}>Tidak Teratur</option>
                            <option value="Bonus" {{ old('kategori') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        </select>
                        @error('kategori')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box w-fit">
                        <button class="btn btn-primary is-btn is-primary" value="submit" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
