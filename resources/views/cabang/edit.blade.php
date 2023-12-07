@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Edit Kantor Cabang</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.update', $data->kd_cabang) }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="kode_cabang">
                        Kode Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('kode_cabang') is_invalid @enderror form-control" value="{{ old('kode_cabang', $data->kd_cabang) }}" name="kode_cabang" id="kode_cabang" required>
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="nama_cabang" class="mt-2">
                        Nama Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('nama_cabang') is_invalid @enderror form-control" value="{{ old('nama_cabang', $data->nama_cabang) }}" name="nama_cabang" id="nama_cabang" required>
                    @error('nama_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <label for="alamat_cabang" class="mt-2">
                        Alamat Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is_invalid @enderror form-control" required>{{ old('alamat_cabang', $data->alamat_cabang ) }}</textarea>
                    @error('alamat_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="masa_pajak" class="mt-2">Masa Pajak</label>
                        <input type="text" class="@error('masa_pajak') is_invalid @enderror form-control" value="{{ old('masa_pajak', $data->masa_pajak) }}" name="masa_pajak" id="masa_pajak">
                        @error('masa_pajak')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="tanggal_lapor" class="mt-2">Tanggal Lapor</label>
                        <input type="date" class="@error('tanggal_lapor') is_invalid @enderror form-control" value="{{ old('tanggal_lapor', $data->tanggal_lapor) }}" name="tanggal_lapor" id="tanggal_lapor">
                        @error('tanggal_lapor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        <label for="npwp_pemotong" class="mt-2">
                            NPWP Perusahaan Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('npwp_pemotong') is_invalid @enderror form-control" value="{{ old('npwp_pemotong', $data->npwp_pemotong) }}" name="npwp_pemotong" id="npwp_pemotong" required>
                        @error('npwp_pemotong')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="nama_pemotong" class="mt-2">
                            Nama Perusahaan Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('nama_pemotong') is_invalid @enderror form-control" value="{{ old('nama_pemotong', $data->nama_pemotong) }}" name="nama_pemotong" id="nama_pemotong" required>
                        @error('nama_pemotong')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="telp" class="mt-2">Telepon</label>
                        <input type="text" class="@error('telp') is_invalid @enderror form-control" value="{{ old('telp', $data->telp) }}" name="telp" id="telp">
                        @error('telp')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="email" class="mt-2">
                            Email<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('email') is_invalid @enderror form-control" value="{{ old('email', $data->email) }}" name="email" id="email" required>
                        @error('email')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="npwp_pemimpin_cabang" class="mt-2">
                            NPWP Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('npwp_pemimpin_cabang') is_invalid @enderror form-control" value="{{ old('npwp_pemimpin_cabang', $data->npwp_pemimpin_cabang) }}" name="npwp_pemimpin_cabang" id="npwp_pemimpin_cabang" required>
                        @error('npwp_pemimpin_cabang')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="nama_pemimpin_cabang" class="mt-2">
                            Nama Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('nama_pemimpin_cabang') is_invalid @enderror form-control" value="{{ old('nama_pemimpin_cabang', $data->nama_pemimpin_cabang) }}" name="nama_pemimpin_cabang" id="nama_pemimpin_cabang" required>
                        @error('nama_pemimpin_cabang')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                   <div class="pt-3 pb-3">
                    <button class="is-btn is-primary">Simpan</button>
                   </div>
                </form>
            </div>
        </div>
    </div>
@endsection