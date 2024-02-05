@extends('layouts.app-template')
@section('loader')
    @include('components.preloader.loader')
@endsection
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Bonus
            </div>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Bonus</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @can('penghasilan - import - bonus - import')
                <a href="{{ route('bonus.import-data') }}" class="btn btn-primary-light">
                    <i class="ti ti-file-import"></i> Import
                </a>
            @endcan
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form id="form" method="get">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="page_length" >show</label>
                    <select name="page_length" id="page_length"
                        class="mr-3 text-sm text-neutral-400 page_length">
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
                    <label for="" class="">entries</label>
                </div>
                <div class="input-search">
                    <i class="ti ti-search"></i>
                    <input type="search" name="q" id="q" placeholder="Cari disini..."
                        value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                </div>
            </div>
            <table class="tables whitespace-nowrap" id="table" style="width: 100%">
                <thead class="text-primary">
                    <th>No</th>
                    <th>Tunjangan</th>
                    @if (auth()->user()->hasRole('cabang') != 'cabang')
                        <th>
                            Kantor
                        </th>
                    @endif
                    <th>Total Data</th>
                    <th>Grand Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                        $i = $page == 1 ? 1 : $start;
                    @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->nama_tunjangan }}</td>
                            @if (auth()->user()->hasRole('cabang') != 'cabang')
                                <td>
                                    {{ $item->nama_cabang }}
                                </td>
                            @endif
                            <td>{{ $item->total_data }}</td>
                            <td>{{ number_format($item->jumlah_nominal, 0,',','.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->new_date)->translatedFormat('d F Y') }}</td>
                            <td class="flex justify-center gap-2">
                                @if ($item->is_lock != 1)
                                    @if (auth()->user()->hasRole('kepegawaian'))
                                            @can('penghasilan - lock - bonus')
                                                <a href="{{route('bonus-lock')}}?id_tunjangan={{$item->id_tunjangan}}&tanggal={{ \Carbon\Carbon::parse($item->new_date)->translatedFormat('Y-m-d') }}&entitas={{$item->kd_entitas}}"
                                                    class="btn btn-success">Lock</a>
                                            @endcan
                                            @can('penghasilan - edit - bonus')
                                                <a href="{{ route('edit-tunjangan-bonus-new', $item->id_tunjangan) }}?idTunjangan={{$item->id_tunjangan}}&tanggal={{$item->new_date}}&entitas={{$item->kd_entitas}}" class="btn btn-warning-light">Edit</a>
                                            @endcan
                                    @else
                                        {{-- Cabang --}}
                                        @can('penghasilan - lock - bonus')
                                            <a href="{{route('bonus-lock')}}?id_tunjangan={{$item->id_tunjangan}}&tanggal={{ \Carbon\Carbon::parse($item->new_date)->translatedFormat('Y-m-d') }}&entitas={{$item->kd_entitas}}"
                                                class="btn btn-success">Lock</a>
                                        @endcan
                                        @can('penghasilan - edit - bonus')
                                            <a href="{{ route('edit-tunjangan-bonus-new', $item->id_tunjangan) }}?idTunjangan={{$item->id_tunjangan}}&tanggal={{$item->new_date}}&entitas={{$item->kd_entitas}}" class="btn btn-warning-light">Edit</a>
                                        @endcan
                                    @endif
                                @else
                                    @can('penghasilan - unlock - bonus')
                                        <a href="{{route('bonus-unlock')}}?id_tunjangan={{$item->id_tunjangan}}&tanggal={{ \Carbon\Carbon::parse($item->new_date)->translatedFormat('Y-m-d') }}&entitas={{$item->kd_entitas}}"
                                            class="btn btn-success-light">Unlock</a>
                                    @endcan
                                @endif
                                @can('penghasilan - import - bonus - detail')
                                    <a href="{{ route('bonus.detail', $item->id_tunjangan) }}?tanggal={{$item->new_date}}&entitas={{$item->kd_entitas}}" class="btn btn-primary-light">Detail</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-footer">
                <div class="showing">
                    Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                </div>
                <div class="pagination">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $data->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endsection
