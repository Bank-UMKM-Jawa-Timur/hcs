@extends('layouts.app-template')
@section('content')
@php
$profilKantor = \DB::table('mst_profil_kantor')->select('id','kd_cabang')->find($_GET['profil_kantor']);
@endphp
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Pengurangan Bruto</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                @if ($profilKantor)
                    @if ($profilKantor->kd_cabang == '000')
                        <i class="ti ti-circle-filled text-theme-primary"></i>
                        <a href="#" class="text-sm text-gray-500 font-bold">Kantor Pusat</a>
                        <i class="ti ti-circle-filled text-theme-primary"></i>
                        <a href="" class="text-sm text-gray-500 font-bold">Pengurangan Bruto</a>
                    @else
                        <i class="ti ti-circle-filled text-theme-primary"></i>
                        <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                        <i class="ti ti-circle-filled text-theme-primary"></i>
                        <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500 font-bold">Kantor Cabang</a>
                        <i class="ti ti-circle-filled text-theme-primary"></i>
                        <p class="text-sm text-gray-500 font-bold">Pengurangan Bruto</p>
                    @endif
                @endif
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @can('setting - kantor pusat - pengurangan bruto - create pengurangan bruto')
            <a href="{{ route('pengurangan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor'] }}"
                class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Data
                Bagian</a>
            @endcan
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <table class="tables-stripped border-none" id="table" style="width: 100%">
            <thead class=" text-primary">
                <tr>
                    <th rowspan="2" style="text-align: center">No</th>
                    <th rowspan="2" style="text-align: center">Nama Cabang</th>
                    <th rowspan="2" style="text-align: center">DPP(%)</th>
                    <th colspan="3" class="text-center" style="text-align: center">JP(%)</th>
                    <th rowspan="2" class="text-center" style="text-align: center">Status</th>
                    <th rowspan="2" class="text-center" style="text-align: center">Aksi</th>
                </tr>
                <tr>
                    <th class="text-center" style="text-align: center">(%)</th>
                    <th class="text-center" style="text-align: center">Januari - Februari(Rp)</th>
                    <th class="text-center" style="text-align: center">Maret - Desember(Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_cabang }}</td>
                    <td>{{ $item->dpp }}</td>
                    <td class="text-center">{{ $item->jp }}</td>
                    <td class="text-center">{{ number_format($item->jp_jan_feb, 0, '.', '.') }}</td>
                    <td class="text-center">{{ number_format($item->jp_mar_des, 0, '.', '.') }}</td>
                    <td class="text-center">
                        <input type="checkbox" name="check" id="check" @if($item->active) checked @endif>
                    </td>
                    <td class="text-center flex gap-2">
                        @can('setting - kantor pusat - pengurangan bruto - edit pengurangan bruto')
                        <a href="{{ route('pengurangan-bruto.edit', $item->id) }}">
                            <button class="btn btn-warning-light">
                                Edit
                            </button>
                        </a>
                        @endcan
                        @can('setting - kantor pusat - pengurangan bruto - delete pengurangan bruto')
                        <form action="{{ route('pengurangan-bruto.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id_profil_kantor" value="{{$item->id_profil_kantor}}">
                            <button type="submit" class="btn btn-danger-light">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<style>
    .text-center{
        text-align: center;
    }
</style>

@push('extraScript')
<script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
    });

    function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

</script>
@endpush