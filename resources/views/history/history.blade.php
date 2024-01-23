@extends('layouts.app-template')
@include('vendor.select2')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">History Jabatan</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">History</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('history_jabatan.index') }}" class="text-sm text-gray-500 font-bold">Jabatan</a>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
            <h4 class="font-bold">Cari Karyawan</h4>
            <form action="{{ route('history_jabatan.store') }}" method="post">
                @csrf
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="">Karyawan:</label>
                        <select name="nip" id="nip" class="form-input" required></select>
                    </div>
                    <div class="input-box">
                        <label for="">Status Jabatan</label>
                        <input type="text" id="status_jabatan" name="status_jawaban"
                            class="form-input-disabled" value="{{old('status_jawaban', \Request::get('status_jawaban'))}}" readonly>
                    </div>
                    <div class="input-box">
                        <label for="">Pangkat dan Golongan Sekarang</label>
                        <input type="text" id="panggol" class="form-input-disabled" readonly>
                        <input type="hidden" id="panggol_lama" name="panggol_lama"
                            class="form-input-disabled" value="{{old('panggol_lama', \Request::get('panggol_lama'))}}">
                    </div>
                </div>
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                    <input type="hidden" id="bagian_lama" name="bagian_lama"
                        value="{{old('bagian_lama', \Request::get('bagian_lama'))}}">
                    <input type="hidden" id="status_jabatan_lama" name="status_jabatan_lama"
                        value="{{old('status_jawaban_lama', \Request::get('status_jawaban_lama'))}}">
                    <div class="input-box">
                        <label for="">Jabatan Sekarang</label>
                        <input type="text" class="form-input-disabled" name="jabatan_lama" id="jabatan_lama"
                            value="{{old('jabatan_lama', \Request::get('jabatan_lama'))}}"readonly>
                        <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama"
                            value="{{old('id_jabatan_lama', \Request::get('id_jabatan_lama'))}}">
                    </div>
                    <div class="input-box">
                            <label for="">Kantor Sekarang</label>
                            <input type="text" class="form-input-disabled" name="kantor_lama" id="kantor_lama"
                                value="{{old('kantor_lama', \Request::get('kantor_lama'))}}" readonly>
                    </div>
                    <div class="" id="">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
            <br>
            <hr>
            <h5 class="text-center">Data History Jabatan <br> <b> {{ $data_karyawan->nama_karyawan }}</b></h5>
            <div style="" class="table-responsive text-center">
                <table class="tables text-center cell-border table-striped" id="table" style="width: 100%; white-space: nowrap; overflow-y:hidden;">
                    <thead class="sticky-top text-center">
                        <tr>
                            <th class="sticky-col">#</th>
                            <th class="text-nowrap text-center">
                                Tanggal Mulai
                            </th>
                            <th class="text-nowrap text-center">
                                Lama Menjabat
                            </th>
                            <th class="text-nowrap text-center">
                                Jabatan Lama
                            </th>
                            <th class="text-nowrap text-center">
                                Jabatan Baru
                            </th>
                            <th class="text-nowrap text-center">
                                Bukti SK
                            </th>
                            <th class="text-nowrap text-center">
                                keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($karyawan as $key => $item)
                            @php
                                $masaKerja = '-';
                                if ($key != 0) {
                                    $mulaKerja = new DateTime(date('d-M-Y', strtotime($item['tanggal_pengesahan'])));
                                    $waktuSekarang = new DateTime(date('d-M-Y', strtotime($karyawan[$key - 1]['tanggal_pengesahan'])));
                                    $hitung = $waktuSekarang->diff($mulaKerja);
                                    $masaKerja = $hitung->format('%y Tahun | %m Bulan | %d Hari');
                                }
                            @endphp
                            <tr>
                                <td class="sticky-col">
                                    {{ $i++ }}
                                </td>
                                <td class="text-nowrap">
                                    {{ date('d M Y', strtotime($item['tanggal_pengesahan'])) }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $masaKerja }}
                                </td>
                                <td class="">
                                    {{ $item['lama'] }}
                                </td>
                                <td class="">
                                    {{ $item['baru'] }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $item['bukti_sk'] }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $item['keterangan'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection

@push('script')
<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}'
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    nipSelect.append(`
        <option value="{{$data_karyawan?->nip}}">{{$data_karyawan?->nip}} - {{$data_karyawan?->nama_karyawan}}</option>
    `).trigger('change');

    $(document).ready( function () {
        $('#table').DataTable();
    });

    $("#nip").val("{{ $data_karyawan?->nip }}").trigger('change');
    $('#nip').change(function(e) {
        const nip = $(this).val();

        $.ajax({
            url: "{{ route('getDataKaryawan') }}",
            data: {nip},
            dataType: 'JSON',
            success: (data) => {
                if(!data.success) return;

                $('input[name=kd_entity]').val(data.karyawan.kd_entitas);
                $('#jabatan_lama').val(data.karyawan.jabatan.nama_jabatan || '');
                $('#kantor_lama').val(data.karyawan.kd_entitas);
                $('#id_jabatan_lama').val(data.karyawan.jabatan.kd_jabatan);
                $("#status_jabatan").val(data.karyawan.status_jabatan)
                $("#bagian_lama").val(data.karyawan.kd_bagian)
                $("#panggol").val(data.karyawan.kd_panggol + " - " + data.karyawan.pangkat)
                //$("#panggol_lama").val(data.karyawan.kd_panggol)
                $("#status_jabatan_lama").val(data.karyawan.status_jabatan)
            }
        });

        $.ajax({
            url: '{{ route('getDataGjPromosi') }}?nip='+nip,
            dataType: "json",
            type: "Get",
            success: function(res){
                x = res.data_tj.length
                $("#gj_pokok").removeAttr("disabled")
                $("#gj_penyesuaian").removeAttr("disabled")
                $("#gj_pokok").val(formatRupiah(res.data_gj.gj_pokok.toString()))
                $("#gj_penyesuaian").val(formatRupiah(res.data_gj.gj_penyesuaian.toString()))
                $("#tj").empty();
            }
        })
    });
</script>
@endpush