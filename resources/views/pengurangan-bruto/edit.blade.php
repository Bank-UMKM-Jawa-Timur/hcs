@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Edit Pengurangan Bruto</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('pengurangan-bruto.update', $data->id) }}" method="POST" class="form-group" >
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_profil_kantor" value="{{ $data->id_profil_kantor }}">

                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="form-control" name="kode_cabang" id="kode_cabang"
                        value="{{ old('kode_cabang', $kd_cabang) }}" readonly> 
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="dpp" class="mt-2">
                        DPP(%)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('dpp') is-invalid @enderror form-control" name="dpp" id="dpp" value="{{ old('dpp', $data->dpp) }}" required>
                    @error('dpp')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp" class="mt-2">
                        JP(%)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp') is-invalid @enderror form-control" name="jp" id="jp"
                        value="{{ old('jp', $data->jp) }}" onkeyup="hitungTotal()" required>
                    @error('jp')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp_jan_feb" class="mt-2">
                        Januari - Februari(Rp)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp_jan_feb') is-invalid @enderror form-control" name="jp_jan_feb" id="jp_jan_feb" value="{{ old('jp_jan_feb', $data->jp_jan_feb) }}" maxlength="10" required>
                    @error('jp_jan_feb')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp_mar_des" class="mt-2">
                        Maret - Desember(Rp)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp_mar_des') is-invalid @enderror form-control" name="jp_mar_des" id="jp_mar_des" value="{{ old('jp_mar_des', $data->jp_mar_des) }}" maxlength="10" required>
                    @error('jp_mar_des')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button class="is-btn is-primary-btn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection