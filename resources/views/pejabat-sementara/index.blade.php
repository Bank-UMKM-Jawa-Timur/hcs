@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Penjabat Sementara</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="{{ route('pejabat-sementara.index') }}">Penjabat Sementara</a></p>
        </div>
        <div class="card-header row mt-3 mr-8 pl-4" >
            <a href="{{ route('pejabat-sementara.create') }}" class="is-btn is-primary-light">Tambah PJS</a>
        </div>
    </div>
    <div class="card-body p-5">
        <div class="table-responsive overflow-hidden content-center">
            <table class="table whitespace-nowrap" id="pjs-table">
                <thead class="text-dark">
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
                    @foreach ($pjs as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
