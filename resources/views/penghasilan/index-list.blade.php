@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Penghasilan Tidak Teratur</h5>
        <p class="card-title"><a href="">Penghasilan </a> > Penghasilan Tidak Teratur</p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5" >
        @can('penghasilan - import - penghasilan tidak teratur - create')
            <a class="mb-3" href="{{ route('penghasilan-tidak-teratur.input-tidak-teratur') }}">
                <button class="is-btn is-primary">Tambah</button>
            </a>
        @endcan
        @can('penghasilan - import - penghasilan tidak teratur - import')
            <a class="mb-3 ml-2" href="{{ route('penghasilan-tidak-teratur.create') }}">
                <button class="is-btn is-primary">Import</button>
            </a>
        @endcan
    </div>
</div>

<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive overflow-hidden content-center">
                    <form action="" id="form" method="get">
                        <div class="d-flex justify-content-between mb-4">
                            <div class="p-2 mt-4">
                                <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                                <select name="page_length" id="page_length"
                                    class="border px-4 py-2 cursor-pointer rounded appearance-none text-center">
                                    <option value="10"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                        10</option>
                                    <option value="20"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                        20</option>
                                    <option value="50"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                                        50</option>
                                    <option value="100"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                                        100</option>
                                </select>
                                <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                            </div>
                            <div class="p-2">
                                <label for="q">Cari</label>
                                <input type="search" name="q" id="q" placeholder="Cari disini..."
                                class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                            </div>
                        </div>
                        <table class="table" id="table" style="width: 100%">
                            <thead class=" text-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tunjangan</th>
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
                                        <td>{{ $item->total }}</td>
                                        <td>Rp {{ $item->grand_total ? number_format($item->grand_total, 0, '.', '.') : 0}}</td>
                                        <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                                        <td class="text-center">
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
                                                    class="btn btn-success p-1">Lock</a>
                                                @endcan
                                                @can('penghasilan - edit - penghasilan tidak teratur')
                                                    @php
                                                        $cant_lock_edit = true;
                                                    @endphp
                                                    <a href="{{ route('penghasilan-tidak-teratur.edit-tunjangan-tidak-teratur', [
                                                        'idTunjangan' => $item->tunjangan_id,
                                                        'tanggal' => $item->tanggal ])}}" class="btn btn-outline-warning p-1">Edit</a>
                                                @endcan
                                            @else
                                                @can('penghasilan - unlock - penghasilan tidak teratur')
                                                    @php
                                                        $cant_unlock = true;
                                                    @endphp
                                                    <a href="{{route('penghasilan-tidak-teratur.unlock')}}?id_tunjangan={{$item->tunjangan_id}}&tanggal={{ $item->tanggal }}"
                                                        class="btn btn-success p-1">Unlock</a>
                                                @endif
                                            @endif
                                            @can('penghasilan - import - penghasilan tidak teratur - detail')
                                                @php
                                                    $cant_detail = true;
                                                @endphp
                                                <a href="{{ route('penghasilan-tidak-teratur.detail') }}?idTunjangan={{ $item->tunjangan_id }}&tanggal={{ $item->tanggal }}" class="btn btn-outline-info p-1">Detail</a>
                                            @endcan
                                            @if (!$cant_detail && !$cant_lock_edit && !$cant_unlock)
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                        <div>
                            Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                        </div>
                        <div>
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::bootstrap-4') }}
                            @endif
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
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
