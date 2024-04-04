@extends('layouts.app-template')
@push('extraScript')
    <script>
        $("#pilihSemua").change(function(){
            if($(this).prop('checked')){
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', true);
                })
            } else {
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', false);
                })
            }
        })
    </script>
@endpush
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Kalkulator PPh</h2>
                <div class="breadcrumb">
                    <a href="/" class="text-sm text-gray-500">Extra</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="/" class="text-sm text-gray-500 font-bold">Kalkulator</a>
                </div>
            </div>
        </div>
    </div>
    <button class="btn-scroll-to-top btn btn-primary hidden fixed bottom-5 right-5 z-20">
        To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
    </button>

    <div class="body-pages">
        <div class="table-wrapping">
            <form
                action="{{ route('kalkulator-pph.index') }}" method="GET"
                enctype="multipart/form-data" id="form" class="form-group mb-4" >
                <div class="flex justify-between items-end">
                    <div class="flex-none w-full">
                        <div class="input-box">
                            <label for="bruto">Bruto</label>
                            <input
                                type="text" class="@error('bruto') is-invalid @enderror form-input rupiah"
                                name="bruto" id="bruto" value="{{ old('bruto', \Request::get('bruto')) }}" placeholder="Total Bruto">
                        </div>
                        @error('bruto')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="input-box mt-3">
                            <label for="ptkp">PTKP</label>
                            <select class="form-input @error('ptkp') is-invalid @enderror" id="ptkp" name="ptkp">
                                <option>-- Pilih PTKP --</option>
                                @foreach($ptkp as $item)
                                    <option value="{{ $item->kode }}" @selected(\Request::get('ptkp') == $item->kode)>{{ $item->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('ptkp')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="input-box mt-3">
                            <label for="bulan">Bulan</label>
                            <select class="form-input @error('bulan') is-invalid @enderror" id="bulan" name="bulan">
                                <option>-- Pilih Bulan --</option>
                                <option value="1" @selected(\Request::get('bulan') == 1)>Januari</option>
                                <option value="2" @selected(\Request::get('bulan') == 2)>Februari</option>
                                <option value="3" @selected(\Request::get('bulan') == 3)>Maret</option>
                                <option value="4" @selected(\Request::get('bulan') == 4)>April</option>
                                <option value="5" @selected(\Request::get('bulan') == 5)>Mei</option>
                                <option value="6" @selected(\Request::get('bulan') == 6)>Juni</option>
                                <option value="7" @selected(\Request::get('bulan') == 7)>Juli</option>
                                <option value="8" @selected(\Request::get('bulan') == 8)>Agustus</option>
                                <option value="9" @selected(\Request::get('bulan') == 9)>September</option>
                                <option value="10" @selected(\Request::get('bulan') == 10)>Oktober</option>
                                <option value="11" @selected(\Request::get('bulan') == 11)>November</option>
                                <option value="12" @selected(\Request::get('bulan') == 12)>Desember</option>
                            </select>
                        </div>
                        @error('bulan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="input-box mt-3">
                            <label for="tahun">Tahun</label>
                            <input
                                type="number" class="@error('tahun') is-invalid @enderror form-input"
                                name="tahun" id="tahun" value="{{ old('tahun', \Request::get('tahun')) }}" placeholder="Tahun">
                        </div>
                        @error('tahun')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary form-input mt-3 right-0">Cek</button>
            </form>
            <hr>
            @if($result)
                <div class="input-box mt-4">
                    <label for="">Pengali</label>
                    <input type="text" class="form-input-disabled" value="{{ $result['pengali'] }}" readonly>
                </div>
                <div class="input-box mt-2">
                    <label for="">PPH</label>
                    <input type="text" class="form-input-disabled" value="{{ $result['pph'] }}" readonly>
                </div>
                <div class="input-box mt-2">
                    <label for="">PPH (floor)</label>
                    <input type="text" class="form-input-disabled" value="{{ $result['pph_floor'] }}" readonly>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        $('#form').on('submit', function () {
            $('.preloader').show()
        })

        $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(".rupiah").val(formatRupiah(angka));
        })
    </script>
@endpush
