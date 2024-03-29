@extends('layouts.app-template')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <div class="text-2xl font-bold tracking-tighter">
                    Log Aktivitas
                </div>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Log Aktivitas</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="/" class="text-sm text-gray-500 font-bold">List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <form id="form" method="get">
                <div class="layout-component">
                    <div class="shorty-table">
                        <label for="page_length">Show</label>
                        <select name="page_length" class="mr-3 text-sm text-neutral-400 page_length" id="page_length">
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
                        <label for="page_length">entries</label>
                    </div>
                    <div class="input-search">
                        <i class="ti ti-search"></i>
                        <input type="search" placeholder="Search" name="q" id="q"
                               value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                    </div>
                </div>
                <table class="tables-slip table-fixed">
                    <thead>
                        <tr>
                            <th width="100px">No.</th>
                            <th width="200px">User</th>
                            <th>Aktivitas</th>
                            <th width="200px">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $start = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                        $end = $page == 1 ? $page_length : $start + $page_length - 1;
                        $i = $page == 1 ? 1 : $start;
                    @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center max-w-sm">{{ $item->user_id ? $item->user : $item->karyawan }}</td>
                            <td title="{{ $item->activity }}" class="text-justify max-w-sm">
                                @if(strlen($item->activity) > 150)
                                    {{ substr($item->activity, 0, 150) }}...
                                @else
                                    {{ $item->activity }}
                                @endif
                            </td>
                            <td class="text-center" title="{{ $item->created_at }}">{{ $item->created_at_human_readable }}</td>
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
