@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Penjabat Sementara</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('demosi.index') }}" class="text-sm text-gray-500 font-bold">Penjabat Sementara</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @can('manajemen karyawan - tambah penjabat sementara')
                    <a href="{{ route('demosi.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Tambah PJS
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
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            {{-- <th>Jabatan Asli</th> --}}
                            <th>Jabatan PJS</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status</th>
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
                        @foreach ($pjs as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->nip }}</td>
                                <td>{{ $data->karyawan->nama_karyawan }}</td>
                                {{-- <td>{{ jabatanLengkap($data->karyawan) }}</td> --}}
                                <td>{{ jabatanLengkap($data) }}</td>
                                <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                                <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                                <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                                <td class="d-flex justify-content-center">
                                    @if (!$data->tanggal_berakhir)
                                        <a href="#" data-toggle="modal" data-id="{{ $data->id }}"
                                            data-target="#exampleModal-{{ $data->id }}"
                                            class="btn btn-info">nonaktifkan</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="showing">
                        Showing {{ $start }} to {{ $end }} of {{ $pjs->total() }} entries
                    </div>
                    <div class="pagination">
                        @if ($pjs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $pjs->links('pagination::tailwind') }}
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
