@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Tambah Penghasilan Tanpa Pajak</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('ptkp.index') }}">Penghasilan Tanpa Pajak</a> > <a>Tambah Data</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('ptkp.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="@error('kode') is_invalid @enderror form-control" value="{{ old('kode') }}">
                            @error('kode')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ptkp_bulan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
                            @error('keterangan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ptkp_tahun">PTKP Per Tahun</label>
                            <input type="text" name="ptkp_tahun" id="kode" class="@error('ptkp_tahun') is_invalid @enderror rupiah form-control" value="{{ old('ptkp_tahun') }}">
                            @error('ptkp_tahun')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ptkp_bulan">PTKP Per bulan</label>
                            <input type="text" name="ptkp_bulan" id="kode" class="@error('ptkp_bulan') is_invalid @enderror rupiah2 form-control" value="{{ old('ptkp_bulan') }}">
                            @error('ptkp_bulan')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-4 pb-3">
                        <button class="is-btn is-primary-light" value="submit" style="submit">Simpan</button>
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
