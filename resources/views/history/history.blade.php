@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title font-weight-bold">History Jabatan</h5>
            <p class="card-title"><a href="">History </a> > <a href="{{ route('history_jabatan.index') }}">Jabatan</a></p>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row m-0">
        <div class="col">
            <h6>Cari Karyawan</h6>
            <form action="{{ route('history_jabatan.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class  ="col-lg-6">
                        <div class="form-group">
                            <label for="">Karyawan:</label>
                            <select name="nip" id="nip" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Status Jabatan</label>
                            <input type="text" id="status_jabatan" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Pangkat dan Golongan Sekarang</label>
                            <input type="text" id="panggol" class="form-control" disabled>
                            <input type="hidden" id="panggol_lama" name="panggol_lama" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" id="bagian_lama" name="bagian_lama">
                    <input type="hidden" id="status_jabatan_lama" name="status_jabatan_lama">
                    <div class="" id="">
        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Jabatan Sekarang</label>
                            <input type="text" class="form-control" disabled name="" id="jabatan_lama">
                            <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Kantor Sekarang</label>
                            <input type="text" class="form-control" disabled name="" id="kantor_lama">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="is-btn is-primary-light">Tampilkan</button>
                    </div>
                </div>
            </form>
            <br>
            <hr>
            <h5 class="text-center">Data History Jabatan <br> <b> {{ $data_karyawan->nama_karyawan }}</b></h5>
            <div style="" class="table-responsive text-center">
                <table class="table text-center cell-border table-striped" id="table" style="width: 100%; white-space: nowrap; overflow-y:hidden;">
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
    </div>
@endsection

@push('script')
<script>
    // $(document).ready(function() {
    //     var table = $('#table').DataTable({
    //         autoWidth: false,
    //         scrollY: false,
    //         scrollX: true,
    //         dom: Rlfrtip,
    //         colReorder: {
    //             'allowReorder': false
    //         }
    //     });
    // })

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
</script>
@endpush

@section('custom_script')
    <script>
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
                    $("#panggol_lama").val(data.karyawan.kd_panggol)
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
@endsection