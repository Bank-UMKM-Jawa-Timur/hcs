@extends('layouts.app-template')
@include('vendor.select2')

@section('content')

<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Penghasilan Tidak Teratur</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Penghasilan Tidak
                    Teratur</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @can('penghasilan - import - penghasilan tidak teratur - import')
            <a href="{{ route('penghasilan-tidak-teratur.create') }}">
                <button class="btn btn-primary-light"><i class="ti ti-file-import"></i> Import</button>
            </a>
            @endcan
            @can('penghasilan - import - penghasilan tidak teratur - create')
                <a href="{{   route('penghasilan-tidak-teratur.input-tidak-teratur') }}">
                    <button class="btn btn-primary"><i class="ti ti-plus"></i> Tambah</button>
                </a> 
            @endcan
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form action="" id="form" method="get">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="">Show</label>
                    <select name="page_length" id="page_length">
                        <option value="10" @isset($_GET['page_length']) {{ $_GET['page_length']==10 ? 'selected' : '' }}
                            @endisset>
                            10</option>
                        <option value="20" @isset($_GET['page_length']) {{ $_GET['page_length']==20 ? 'selected' : '' }}
                            @endisset>
                            20</option>
                        <option value="50" @isset($_GET['page_length']) {{ $_GET['page_length']==50 ? 'selected' : '' }}
                            @endisset>
                            50</option>
                        <option value="100" @isset($_GET['page_length']) {{ $_GET['page_length']==100 ? 'selected' : ''
                            }} @endisset>
                            100</option>
                    </select>
                    <label for="">entries</label>
                </div>
                <div class="input-search">
                    <i class="ti ti-search"></i>
                    <input type="search" placeholder="Search" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" name="q"
                        id="q">
                </div>
            </div>
        </form>
        <table class="tables">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tunjangan</th>
                    {{-- @if (auth()->user()->hasRole('cabang') != 'cabang')
                    <th>
                        Kantor
                    </th>
                    @endif --}}
                    <th>Total Data</th>
                    <th>Grand Total</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                $i = $page == 1 ? 1 : $start;
                @endphp
                @forelse ($data as $key => $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->nama_tunjangan }}</td>
                    {{-- @if (auth()->user()->hasRole('cabang') != 'cabang')
                    <td>
                        {{ $item->entitas ?? 'Pusat' }}
                    </td>
                    @endif --}}
                    <td>{{ $item->total }}</td>
                    <td>Rp {{ $item->grand_total ? number_format($item->grand_total, 0, '.', '.') : 0}}</td>
                    <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                    <td class="text-center flex justify-center">
                        @php
                        $cant_detail = false;
                        $cant_lock_edit = false;
                        $cant_unlock = false;
                        @endphp
                        @if ($item->is_lock != 1)
                        @can('penghasilan - lock - penghasilan tidak teratur')
                        @php
                        $cant_lock_edit = true;
                        @endphp
                        <a href="{{route('penghasilan-tidak-teratur.lock')}}?id_tunjangan={{$item->tunjangan_id}}&tanggal={{ $item->tanggal }}"
                            class="btn btn-success ml-1">Lock</a>
                        @endcan
                        @can('penghasilan - edit - penghasilan tidak teratur')
                        @php
                        $cant_lock_edit = true;
                        @endphp
                        <a href="{{ route('penghasilan-tidak-teratur.edit-tunjangan-tidak-teratur', [
                                                        'idTunjangan' => $item->tunjangan_id,
                                                        'tanggal' => $item->tanggal,
                                                        'kdEntitas' => $item->kd_entitas])}}"
                            class="btn btn-warning-light ml-1">Edit</a>
                        @endcan
                        @else
                        @can('penghasilan - unlock - penghasilan tidak teratur')
                        @php
                        $cant_unlock = true;
                        @endphp
                        <a href="{{route('penghasilan-tidak-teratur.unlock')}}?id_tunjangan={{$item->tunjangan_id}}&tahun={{ $item->tahun }}&bulan={{ $item->bulan }}&createdAt={{$item->created_at}}&kdEntitas={{ $item->kd_entitas }}"
                            class="btn btn-success ml-1">Unlock</a>
                        @endif
                        @endif
                        @can('penghasilan - import - penghasilan tidak teratur - detail')
                        @php
                        $cant_detail = true;
                        @endphp
                        <a href="{{ route('penghasilan-tidak-teratur.detail', $item->tunjangan_id) }}?bulan={{$item->bulan}}&createdAt={{$item->created_at}}&kd_entitas={{$item->kd_entitas}}"
                            class="btn btn-primary-light ml-1">Detail</a>
                        @endcan
                        @if (!$cant_detail && !$cant_lock_edit && !$cant_unlock)
                        -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <th colspan="7">Data Kosong</th>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer">
            <div class="showing">
                Showing {{$start}} to {{$end}} of {{$data->total()}} entries
            </div>
            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $data->links('pagination::tailwind') }}
            @endif

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#page_length').on('change', function () { 
        $('#form').submit()
    })
// $(document).ready(function() {
//     var table = $('#table').DataTable({
//         'autoWidth': false,
//         'dom': 'Rlfrtip',
//         'colReorder': {
//             'allowReorder': false
//         }
//     });
// })

$('#nip').select2({
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
</script>
@endpush