@extends('layouts.template')

@section('content')
    <div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Penjabat Sementara</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="{{ route('pejabat-sementara.index') }}">Penjabat Sementara</a></p>
        </div>
        <div class="card-header row mt-3 mr-8 pl-4" >
            @can('manajemen karyawan - tambah penjabat sementara')
                <a href="{{ route('pejabat-sementara.create') }}" class="mb-3">
                    <button class="is-btn is-primary">Tambah PJS</button>
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
                            <table class="table whitespace-nowrap" id="pjs-table">
                                <thead class="text-primary">
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
                                        $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                                        $number = 1;
                                        if ($pagination) {
                                        $number = $pagination['iteration'];
                                        }
                                    @endphp
                                    @foreach ($pjs as $data)
                                        <tr>
                                            <td>{{ $number++ }}</td>
                                            <td>{{ $data->nip }}</td>
                                            <td>{{ $data->karyawan->nama_karyawan }}</td>
                                            {{-- <td>{{ jabatanLengkap($data->karyawan) }}</td> --}}
                                            <td>{{ jabatanLengkap($data) }}</td>
                                            <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                                            <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                                            <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                                            <td class="d-flex justify-content-center">
                                                @if(!$data->tanggal_berakhir)
                                                    <a href="#" data-toggle="modal" data-id="{{ $data->id }}" data-target="#exampleModal-{{ $data->id }}" class="btn btn-info">nonaktifkan</a>
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('components.pagination.table-info', [
                            'obj' => $pjs,
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
@include('pejabat-sementara.popup-tgl_berakhir')
@endsection

@push('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#pjs-table').DataTable();

    $('#pjs-table').on('click', '.btn-nonaktif', function(e) {
        e.preventDefault();

        Swal.fire({
            icon: 'question',
            title: 'Apakah anda yakin?',
        })
        .then((result) => {
            if(result.isConfirmed) {
                $(this).parent().submit();
            }
        });
    });
</script>
@endpush
