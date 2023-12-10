@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Tambah Pengurangan Bruto</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</a> > <a>Tambah</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('pengurangan-bruto.store') }}" method="POST" class="form-group" >
                    @csrf
                    <input type="hidden" name="id_profil_kantor" value="{{ $_GET['profil_kantor'] }}">

                    <label for="kode_cabang">Kode Kantor Cabang</label>
                    <input type="text" class="form-control" name="kode_cabang" id="kode_cabang"
                        value="{{ old('kode_cabang', $kd_cabang) }}" readonly>
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="dpp" class="mt-2">
                        DPP(%)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('dpp') is-invalid @enderror only-angka form-control" name="dpp" id="dpp"
                        value="{{ old('dpp') }}" onkeyup="hitungTotal()" required maxlength="5">
                    @error('dpp')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp" class="mt-2">
                        JP(%)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp') is-invalid @enderror only-angka form-control" name="jp" id="jp" value="{{ old('jp') }}" required maxlength="5">
                    @error('jp')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp_jan_feb" class="mt-2">
                        Januari - Februari(Rp)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp_jan_feb') is-invalid @enderror rupiah form-control" name="jp_jan_feb" id="jp_jan_feb" value="{{ old('jp_jan_feb') }}" required maxlength="10">
                    @error('jp_jan_feb')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="jp_mar_des" class="mt-2">
                        Maret - Desember(Rp)<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('jp_mar_des') is-invalid @enderror rupiah form-control" name="jp_mar_des" id="jp_mar_des" value="{{ old('jp_mar_des') }}" required maxlength="10">
                    @error('jp_mar_des')
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
