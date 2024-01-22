@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Promosi</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a class="text-sm text-gray-500 font-bold">Pergerakan Karir</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('promosi.index') }}" class="text-sm text-gray-500 font-bold">Promosi</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan')
                    <a href="{{ route('promosi.index') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Tambah Promosi
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
                <table class="tables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>
                                NIP
                            </th>
                            <th>
                                Nama Karyawan
                            </th>
                            <th>
                                Tanggal Promosi
                            </th>
                            <th>
                                Jabatan Lama
                            </th>
                            <th>
                                Jabatan Baru
                            </th>
                            <th>
                                Kantor Lama
                            </th>
                            <th>
                                Kantor Baru
                            </th>
                            <th>
                                Bukti SK
                            </th>
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
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    {{ $item->nip }}
                                </td>
                                <td>
                                    {{ $item->nama_karyawan }}
                                </td>
                                <td>
                                    <span
                                        style="display: none;">{{ date('Ymd', strtotime($item->tanggal_pengesahan)) }}</span>
                                    {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $item->status_jabatan_lama != null ? $item->status_jabatan_lama . ' - ' : '' }}{{ $item->jabatan_lama }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $item->status_jabatan_baru != null ? $item->status_jabatan_baru . ' - ' : '' }}{{ $item->jabatan_baru }}
                                </td>
                                <td>
                                    {{ $item->kantor_lama ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->kantor_baru ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->bukti_sk }}
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

@push('extraScript')
    <script>
        $('.page_length').on('change', function() {
            $('#form').submit()
        })
    </script>
@endpush