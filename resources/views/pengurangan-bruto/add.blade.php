@extends('layouts.app-template')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Tambah Pengurangan Bruto</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500 font-bold">Kantor Cabang</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Pengurangan Bruto</p>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Tambah</p>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('pengurangan-bruto.store') }}" method="POST" class="form-group">
            @csrf
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <input type="hidden" name="id_profil_kantor" value="{{ $_GET['profil_kantor'] }}">
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
                        DPP(%)<span class="text-red-600">*</span>
                    </label>
                    <input type="text" class="@error('dpp') is-invalid @enderror only-angka form-input" name="dpp"
                        id="dpp" value="{{ old('dpp') }}" onkeyup="hitungTotal()" required maxlength="5">
                    @error('dpp')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <label for="jp" class="mt-2">
                        JP(%)<span class="text-red-600">*</span>
                    </label>
                    <input type="text" class="@error('jp') is-invalid @enderror only-angka form-input" name="jp" id="jp"
                        value="{{ old('jp') }}" required maxlength="5">
                    @error('jp')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <label for="jp_jan_feb" class="mt-2">
                        Januari - Februari(Rp)<span class="text-red-600">*</span>
                    </label>
                    <input type="text" class="@error('jp_jan_feb') is-invalid @enderror rupiah form-input"
                        name="jp_jan_feb" id="jp_jan_feb" value="{{ old('jp_jan_feb') }}" required maxlength="10">
                    @error('jp_jan_feb')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <label for="jp_mar_des" class="mt-2">
                        Maret - Desember(Rp)<span class="text-red-600">*</span>
                    </label>
                    <input type="text" class="@error('jp_mar_des') is-invalid @enderror rupiah form-input"
                        name="jp_mar_des" id="jp_mar_des" value="{{ old('jp_mar_des') }}" required maxlength="10">
                    @error('jp_mar_des')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="pt-3 pb-3 mt-3">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
    $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(this).val(formatRupiah(angka));
        })

        $(document).ready(function() {
                // Selector untuk input dengan kelas "angka-saja"
            var inputElement = $('.only-angka');

            // Tambahkan event listener untuk memfilter input
            inputElement.on('input', function() {
                // Hapus semua karakter non-digit
                var sanitizedValue = $(this).val().replace(/[^\d.]/g, '');
                // Perbarui nilai input dengan angka yang telah difilter
                $(this).val(sanitizedValue);
            });
        });
</script>
@endpush