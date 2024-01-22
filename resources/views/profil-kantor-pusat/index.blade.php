@extends('layouts.app-template')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Profil
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('profil-kantor-pusat.index') }}" class="text-sm text-gray-500">Kantor Pusat</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="" class="text-sm text-gray-500 font-bold">Profil</a>
            </div>
        </div>

    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col">
                <form action="{{ route('profil-kantor-pusat.update') }}" method="POST" class="form-group">
                    @csrf
                    <div class="grid grid-cols-1">
                        <div class="input-box">
                            <label for="kode_cabang">
                                Kode Kantor<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('kode_cabang') is_invalid @enderror form-input" value="{{ old('kode_cabang', $data->kd_cabang) }}" name="kode_cabang" id="kode_cabang" readonly>
                            @error('kode_cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="alamat_cabang" class="mt-2">
                                Alamat Kantor Cabang<span class="text-red-500 text-xs">*</span>
                            </label>
                            <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is_invalid @enderror form-input" required>{{ old('alamat_cabang', $data->alamat_cabang ) }}</textarea>
                            @error('alamat_cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="masa_pajak" class="mt-2">Masa Pajak</label>
                            <input type="text" class="@error('masa_pajak') is_invalid @enderror form-input" value="{{ old('masa_pajak', $data->masa_pajak) }}" name="masa_pajak" id="masa_pajak">
                            @error('masa_pajak')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="tanggal_lapor" class="mt-2">Tanggal Lapor</label>
                            <input type="date" class="@error('tanggal_lapor') is_invalid @enderror form-input" value="{{ old('tanggal_lapor', $data->tanggal_lapor) }}" name="tanggal_lapor" id="tanggal_lapor">
                            @error('tanggal_lapor')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="npwp_pemotong" class="mt-2">
                                NPWP Perusahaan Pemotong<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('npwp_pemotong') is_invalid @enderror form-input" value="{{ old('npwp_pemotong', $data->npwp_pemotong) }}" name="npwp_pemotong" id="npwp_pemotong" required>
                            @error('npwp_pemotong')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="nama_pemotong" class="mt-2">
                                Nama Perusahaan Pemotong<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('nama_pemotong') is_invalid @enderror form-input" value="{{ old('nama_pemotong', $data->nama_pemotong) }}" name="nama_pemotong" id="nama_pemotong" required>
                            @error('nama_pemotong')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="telp" class="mt-2">Telepon</label>
                            <input type="text" class="@error('telp') is_invalid @enderror form-input" value="{{ old('telp', $data->telp) }}" name="telp" id="telp">
                            @error('telp')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="email" class="mt-2">
                                Email<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="email" class="@error('email') is_invalid @enderror form-input" value="{{ old('email', $data->email) }}" name="email" id="email" required>
                            @error('email')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="npwp_pemimpin_cabang" class="mt-2">
                                NPWP Pemotong<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('npwp_pemimpin_cabang') is_invalid @enderror form-input" value="{{ old('npwp_pemimpin_cabang', $data->npwp_pemimpin_cabang) }}" name="npwp_pemimpin_cabang" id="npwp_pemimpin_cabang" required>
                            @error('npwp_pemimpin_cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="nama_pemimpin_cabang" class="mt-2">
                                Nama Pemotong<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('nama_pemimpin_cabang') is_invalid @enderror form-input" value="{{ old('nama_pemimpin_cabang', $data->nama_pemimpin_cabang) }}" name="nama_pemimpin_cabang" id="nama_pemimpin_cabang" required>
                            @error('nama_pemimpin_cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 pb-3">
                        <button class="btn btn-primary is-btn is-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
