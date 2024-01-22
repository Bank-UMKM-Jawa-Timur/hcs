@extends('layouts.app-template')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Tambah Penambahan Bruto
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500">Kantor Cabang</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>
    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col">
                <form action="{{ route('penambahan-bruto.store') }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group" >
                    @csrf
                    <input type="hidden" name="id_profil_kantor" value="{{ $_GET['profil_kantor'] }}">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="input-box">
                            <label for="kode_cabang">Kode Kantor Cabang</label>
                            <input type="text" class="form-input" name="kode_cabang" id="kode_cabang"
                                value="{{ old('kode_cabang', $kd_cabang) }}" readonly>
                            @error('kode_cabang')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="jkk" class="mt-2">
                                JKK(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('jkk') is-invalid @enderror only-angka form-input" name="jkk" id="jkk"
                                value="{{ old('jkk') }}" onkeyup="hitungTotal()" required maxlength="5">
                            @error('jkk')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="jht" class="mt-2">
                                JHT(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('jht') is-invalid @enderror only-angka form-input" name="jht" id="jht" value="{{ old('jht') }}" required maxlength="5">
                            @error('jht')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="jkm" class="mt-2">
                                JKM(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('jkm') is-invalid @enderror only-angka form-input" name="jkm" id="jkm"
                                value="{{ old('jkm') }}" onkeyup="hitungTotal()" required maxlength="5">
                            @error('jkm')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="kesehatan" class="mt-2">
                                Kesehatan(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('kesehatan') is-invalid @enderror only-angka form-input" name="kesehatan" id="kesehatan"
                                value="{{ old('kesehatan') }}" onkeyup="hitungTotal()" required maxlength="5">
                            @error('kesehatan')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="kesehatan_batas_atas" class="mt-2">
                                Batas Atas(Rp)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('kesehatan_batas_atas') is-invalid @enderror rupiah form-input" name="kesehatan_batas_atas" id="kesehatan_batas_atas" value="{{ old('kesehatan_batas_atas') }}" required maxlength="10">
                            @error('kesehatan_batas_atas')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="kesehatan_batas_bawah" class="mt-2">
                                Batas Bawah(Rp)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('kesehatan_batas_bawah') is-invalid @enderror rupiah form-input" name="kesehatan_batas_bawah" id="kesehatan_batas_bawah" value="{{ old('kesehatan_batas_bawah') }}" required maxlength="10">
                            @error('kesehatan_batas_bawah')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="jp" class="mt-2">
                                JP(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('jp') is-invalid @enderror only-angka form-input" name="jp" id="jp"
                                value="{{ old('jp') }}" onkeyup="hitungTotal()" required maxlength="5">
                            @error('jp')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="total" class="mt-2">
                                Total(%)<span class="text-red-500 text-xs">*</span>
                            </label>
                            <input type="text" class="@error('total') is-invalid @enderror form-input" name="total" id="total"
                                value="{{ old('total') }}" onkeyup="hitungTotal()" readonly>
                            @error('total')
                                <div class="mt-2 text-red-500 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <div class="pt-3 pb-3">
                        <button class="btn btn-primary is-btn is-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            hitungTotal();
        })
        function hitungTotal() {
            console.log('hitung total')
            const jkk = !$('#jkk').val() ? 0.00 : parseFloat($('#jkk').val())
            const jht = !$('#jht').val() ? 0.00 : parseFloat($('#jht').val())
            const jkm = !$('#jkm').val() ? 0.00 : parseFloat($('#jkm').val())
            const kesehatan = !$('#kesehatan').val() ? 0.00 : parseFloat($('#kesehatan').val())
            const jp = !$('#jp').val() ? 0.00 : parseFloat($('#jp').val())
            const total = jkk + jht + jkm + kesehatan + jp
            $("#total").val(total)
        }

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
