@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Surat Peringatan
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="/" class="text-sm text-gray-500">Reward & Punishment</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Surat Peringatan</a>
            </div>
        </div>
        @can('manajemen karyawan - reward & punishment - surat peringatan - create')
        <a href="{{ route('surat-peringatan.create') }}" class="mb-3">
            <button class="btn btn-primary is-btn is-primary ">
                Tambah Surat Peringatan
            </button>
        </a>
        @endcan
    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
                        <form id="form" method="get">
                            @include('components.pagination.header')
                            <table class="tables table whitespace-nowrap" id="sp-table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor SP</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal SP</th>
                                        <th>Pelanggaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                                    $number = 1;
                                    if ($pagination) {
                                        $number = $pagination['iteration'];
                                    }
                                    @endphp
                                    @foreach ($sps as $sp)
                                        <tr>
                                            <td>{{ $number++ }}</td>
                                            <td>{{ $sp->no_sp ?? '-' }}</td>
                                            <td>{{ $sp->nip }}</td>
                                            <td>{{ $sp->karyawan->nama_karyawan }}</td>
                                            <td>{{ $sp->tanggal_sp->format('d M Y') }}</td>
                                            <td>{{ $sp->pelanggaran }}</td>
                                            <td class="d-flex">
                                                @can('manajemen karyawan - reward & punishment - surat peringatan - detail')
                                                    <a href="{{ route('surat-peringatan.show', $sp) }}" class="btn btn-primary-light" style="min-width: 60px">Detail</a>
                                                @else
                                                    -
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="flex justify-between">
                            @include('components.pagination.table-info', [
                            'obj' => $sps,
                            'page_length' => $pagination['page_length'],
                            'start' => $pagination['start'],
                            'end' => $pagination['end']
                            ])
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
</script>
@endpush
