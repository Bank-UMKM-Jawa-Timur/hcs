@extends('layouts.app-template')
@include('vendor.select2')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Pajak Penghasilan</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Pajak Penghsilan</a>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="card">
        <form action="{{ route('get-penghasilan') }}" method="post" class="mt-3">
            @csrf
        <div class="grid lg:grid-cols-3 items-center gap-5 md:grid-cols-2 grid-cols-1">
            <div class="input-box">
                <label for="id_label_single">
                    Karyawan
                </label>
                <select class="select-2 select-2-tailwind"  name="nip" id="nip" >
                    <option selected>-- Pilih Nama Karyawan--</option>
                </select>
            </div>
            <div class="input-box">
                <label for="id_label_single">
                    Mode Lihat Data
                </label>
                <select class="form-input" name="mode" >
                    <option selected>-- Pilih Mode Lihat Data --</option>
                    <option value="1">Bukti Pembayaran Gaji Pajak</option>
                    <option value="2">Detail Gaji Pajak</option>
                </select>
            </div>
            @php
                $already_selected_value = date('y');
                $earliest_year = 2024;
            @endphp
            <div class="input-box">
                <label for="id_label_single">
                    Tahun
                </label>
                <select class="form-input"  name="tahun">
                    @php
                    $earliest = 2024;
                    $tahunSaatIni = date('Y');
                    $awal = $tahunSaatIni - 5;
                    $akhir = $tahunSaatIni + 5;
                @endphp
                    <option selected>-- Pilih Tahun--</option>
                    @for ($tahun = $earliest; $tahun <= $akhir; $tahun++)
                        <option {{ Request()->tahun == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                            {{ $tahun }}</option>
                    @endfor
                </select>
            </div>
            <div class="">
                <a href="penghasilan/gajipajak">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-filter"></i>Tampilkan</button>
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

@push('extraScript')
<script>
$('#nip').select2({
    ajax: {
        url: '{{ route('api.select2.karyawan') }}',
        data: function(params) {
            const is_cabang = "{{auth()->user()->hasRole('cabang')}}"
            const cabang = is_cabang ? "{{auth()->user()->kd_cabang}}" : null
            return {
                search: params.term || '',
                page: params.page || 1,
                cabang: cabang
            }
        },
    },
    templateResult: function(data) {
        if(data.loading) return data.text;
        return $(`
            <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
        `);
    }
});
</script>
@endpush
