@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Pengkinian Data</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Pengkinian Data</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @can('manajemen karyawan - pengkinian data - create pengkinian data')
                    <a href="{{ route('pengkinian_data.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Pengkinian Data</a>
                @elsecan('manajemen karyawan - pengkinian data - import pengkinian data')
                    <a href="{{ route('pengkinian-data-import-index') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Import Pengkinian</a>
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
                        <th>NIP</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Kantor</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
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
                        @php
                            $cabang = 'Pusat';
                            if ($item->nama_cabang) {
                                $cabang = $item->nama_cabang;
                            }
                        @endphp
                        <tr>
                            <td>{{ $i++ }}
                            </td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td>{{ $cabang }}</td>
                            <td>
                                @php
                                    $ket = null;
                                    if ($item->ket_jabatan != null) {
                                        $ket = ' (' . $item->ket_jabatan . ')';
                                    }
                                    $st_jabatan = DB::table('mst_jabatan')
                                        ->where('kd_jabatan', $item->kd_jabatan)
                                        ->first();

                                    $bagian = '';
                                    if ($item->kd_bagian != null) {
                                        $bagian1 = DB::table('mst_bagian')
                                            ->select('nama_bagian')
                                            ->where('kd_bagian', $item->kd_bagian)
                                            ->first();

                                        if (isset($bagian1)) {
                                            $bagian = $bagian1->nama_bagian;
                                        }
                                    }
                                @endphp
                                @if ($item->status_jabatan == 'Penjabat')
                                    Pj.{{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                @elseif($item->status_jabatan == 'Penjabat Sementara')
                                    Pjs.{{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                @else
                                    {{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                @endif
                            </td>
                            <td class="flex justify-center">
                                @can('manajemen karyawan - pengkinian data - detail pengkinian data')
                                    <a href="{{ route('pengkinian_data.show', $item->nip) }}"
                                        class="btn btn-primary-light">
                                        Detail
                                    </a>
                                @else
                                    -
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

@push('extraScript')
    <script>
        $('.page_length').on('change', function() {
            $('#form').submit()
        })

        // Adjust pagination url
        var btn_pagination = $('.pagination').find('a')
        var page_url = window.location.href
        $('.pagination').find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endpush
