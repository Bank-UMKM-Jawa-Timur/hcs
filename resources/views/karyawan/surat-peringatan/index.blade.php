@extends('layouts.template')

@section('content')
    <div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Surat Peringatan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="">Reward & Punishment</a> > <a href="/surat-peringatan">Surat Peringatan</a></p>
        </div>
        <div class="card-header row mt-3 mr-8 pr-5">
            @can('manajemen karyawan - reward & punishment - surat peringatan - create')
            <a href="{{ route('surat-peringatan.create') }}" class="mb-3">
                <button class="is-btn is-primary ">
                    Tambah Surat Peringatan
                </button>
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
                        <form id="form" method="get">
                            @include('components.pagination.header')
                            <table class="table whitespace-nowrap" id="sp-table">
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
                                                    <a href="{{ route('surat-peringatan.show', $sp) }}" class="btn btn-outline-info p-1" style="min-width: 60px;">Detail</a>
                                                @else
                                                    - 
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('components.pagination.table-info', [
                            'obj' => $sps,
                            'page_length' => $pagination['page_length'],
                            'start' => $pagination['start'],
                            'end' => $pagination['end']
                            ])
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
