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
        <h6>Cari Karyawan</h6>
        <form action="{{ route('history_jabatan.store') }}" method="post">
            @csrf
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="">Karyawan:</label>
                    <select name="nip" id="nip" class="form-input" required></select>
                </div>
                <div class="input-box">
                    <label for="">Status Jabatan</label>
                    <input type="text" id="status_jabatan" class="form-input-disabled" readonly>
                </div>
                <div class="input-box">
                    <label for="">Pangkat dan Golongan Sekarang</label>
                    <input type="text" id="panggol" class="form-input-disabled" readonly>
                    <input type="hidden" id="panggol_lama" name="panggol_lama" class="form-input-disabled">
                </div>
            </div>
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                <input type="hidden" id="bagian_lama" name="bagian_lama">
                <input type="hidden" id="status_jabatan_lama" name="status_jabatan_lama">
                <div class="input-box">
                    <label for="">Jabatan Sekarang</label>
                    <input type="text" class="form-input-disabled" readonly name="" id="jabatan_lama">
                    <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                </div>
                <div class="input-box">
                    <label for="">Kantor Sekarang</label>
                    <input type="text" class="form-input-disabled" readonly name="" id="kantor_lama">
                </div>
                <div class="" id="">
                </div>
            </div>
            <div class="pt-4 pb-4">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('extraScript')
<script>
    $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}'
        },
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });
    $('#nip').change(function(e) {
        const nip = $(this).val();

        $.ajax({
            url: "{{ route('getDataKaryawan') }}",
            data: {
                nip
            },
            dataType: 'JSON',
            success: (data) => {
                if (!data.success) return;
                console.log('data.karyawan')
                console.log(data)
                $('input[name=kd_entity]').val(data.karyawan.kd_entitas);
                $('#jabatan_lama').val(data.karyawan.jabatan.nama_jabatan || '');
                $('#kantor_lama').val(data.karyawan.kd_entitas);
                $('#id_jabatan_lama').val(data.karyawan.jabatan.kd_jabatan);
                $("#status_jabatan").val(data.karyawan.status_jabatan)
                $("#bagian_lama").val(data.karyawan.kd_bagian)
                $("#panggol").val(data.karyawan.kd_panggol + " - " + data.karyawan.panggol.pangkat)
                $("#panggol_lama").val(data.karyawan.kd_panggol)
                $("#status_jabatan_lama").val(data.karyawan.status_jabatan)
            }
        });

        $.ajax({
            url: '{{ route('getDataGjPromosi') }}?nip=' + nip,
            dataType: "json",
            type: "Get",
            success: function(res) {
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