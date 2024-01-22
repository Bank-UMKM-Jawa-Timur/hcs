@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Tambah Penghasilan Tanpa Pajak
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('umur.index') }}" class="text-sm text-gray-500">Penghasilan Tanpa Pajak</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('umur.index') }}" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>

    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col">
                <form action="{{ route('ptkp.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-box">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="@error('kode') is_invalid @enderror form-input" value="{{ old('kode') }}">
                            @error('kode')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="ptkp_bulan">Keterangan</label>
                            <input type="text" class="form-input" name="keterangan">
                            @error('keterangan')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="ptkp_tahun">PTKP Per Tahun</label>
                            <input type="text" name="ptkp_tahun" id="kode" class="@error('ptkp_tahun') is_invalid @enderror rupiah form-input" value="{{ old('ptkp_tahun') }}">
                            @error('ptkp_tahun')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="ptkp_bulan">PTKP Per bulan</label>
                            <input type="text" name="ptkp_bulan" id="kode" class="@error('ptkp_bulan') is_invalid @enderror rupiah2 form-input" value="{{ old('ptkp_bulan') }}">
                            @error('ptkp_bulan')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-4 pb-3">
                        <button class="btn btn-primary is-btn is-primary" value="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(".rupiah").val(formatRupiah(angka));
        })
        $('.rupiah2').keyup(function(){
            var angka = $(this).val();
            $(".rupiah2").val(formatRupiah(angka));
        })
    </script>
@endpush
