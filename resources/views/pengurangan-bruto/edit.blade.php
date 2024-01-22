@extends('layouts.app-template')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Edit Pengurangan Bruto</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500 font-bold">Kantor Cabang</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold">Pengurangan Bruto</p>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold">Edit</p>
                </div>
            </div>
        </div>
        <div class="body-pages">
            <div class="table-wrapping">
                <form action="{{ route('pengurangan-bruto.update', $data->id) }}" method="POST" class="form-group" >
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_profil_kantor" value="{{ $data->id_profil_kantor }}">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                        <div class="input-box">
                            <label for="kode_cabang">Kode Kantor Cabang</label>
                            <input type="text" class="form-input" name="kode_cabang" id="kode_cabang"
                                value="{{ old('kode_cabang', $kd_cabang) }}" readonly> 
                            @error('kode_cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="input-box">
                            <label for="dpp">
                                DPP(%)<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="@error('dpp') is-invalid @enderror form-input" name="dpp" id="dpp" value="{{ old('dpp', $data->dpp) }}" required>
                            @error('dpp')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="input-box">
                            <label for="jp" class="mt-2">
                                JP(%)<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="@error('jp') is-invalid @enderror form-input" name="jp" id="jp"
                                value="{{ old('jp', $data->jp) }}" onkeyup="hitungTotal()" required>
                            @error('jp')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="input-box">
                            <label for="jp_jan_feb" class="mt-2">
                                Januari - Februari(Rp)<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="@error('jp_jan_feb') is-invalid @enderror form-input" name="jp_jan_feb" id="jp_jan_feb" value="{{ old('jp_jan_feb', $data->jp_jan_feb) }}" maxlength="10" required>
                            @error('jp_jan_feb')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="input-box">
                            <label for="jp_mar_des" class="mt-2">
                                Maret - Desember(Rp)<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="@error('jp_mar_des') is-invalid @enderror form-input" name="jp_mar_des" id="jp_mar_des" value="{{ old('jp_mar_des', $data->jp_mar_des) }}" maxlength="10" required>
                            @error('jp_mar_des')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary mt-5">Simpan</button>
                </form>
        </div>
    </div>
@endsection