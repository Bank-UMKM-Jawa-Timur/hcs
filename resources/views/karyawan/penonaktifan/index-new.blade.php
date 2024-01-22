@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Penonaktifan Karyawan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a class="text-sm text-gray-500 font-bold">Pergerakan Karir</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('promosi.index') }}" class="text-sm text-gray-500 font-bold">Penonaktifan Karyawan
                        Karir</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @if (auth()->user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan'))
                    <a href="{{ route('promosi.index') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Tambah Penonaktifan
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
                    <thead class="text-primary">
                        <th>No</th>
                        <th>
                            NIP
                        </th>
                        <th>
                            NIK
                        </th>
                        <th>
                            Nama karyawan
                        </th>
                        <th>
                            Kantor Terakhir
                        </th>
                        <th>
                            Jabatan Terakhir
                        </th>
                        <th style="text-align: center">Kategori <br>Penonaktifan</th>
                        <th style="text-align: center">Tanggal Penonaktifan</th>
                    </thead>
                    <tbody>
                        @php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                            $start = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                            $end = $page == 1 ? $page_length : $start + $page_length - 1;
                            $i = $page == 1 ? 1 : $start;
                        @endphp
                        @foreach ($karyawan as $krywn)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $krywn->nip }}</td>
                                <td>{{ $krywn->nik }}</td>
                                <td>{{ $krywn->nama_karyawan }}</td>
                                <td>{{ $krywn->kantor_terakhir }}</td>
                                <td>{{ $krywn->prefix . $krywn->jabatan_result }} {{ $krywn->entitas_result }}
                                    {{ $krywn?->bagian?->nama_bagian }} {{ $krywn->ket }}</td>
                                <td>{{ $krywn->kategori_penonaktifan ?? '-' }}</td>
                                <td>{{ $krywn->tanggal_penonaktifan != null ? date('d M Y', strtotime($krywn->tanggal_penonaktifan)) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="showing">
                        Showing {{ $start }} to {{ $end }} of {{ $karyawan->total() }} entries
                    </div>
                    <div class="pagination">
                        @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $karyawan->links('pagination::tailwind-new') }}
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
