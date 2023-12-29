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
                        <table class="table whitespace-nowrap" id="sp-table">
                            <thead class="text-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SP</th>
                                    <th>NIP</th>
                                    <th>Nama Karyawan</th>
                                    <th>Tanggal SP</th>
                                    <th>Pelanggaran</th>
                                    @can('manajemen karyawan - reward & punishment - surat peringatan - detail')
                                        <th>Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($sps as $sp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sp->no_sp ?? '-' }}</td>
                                        <td>{{ $sp->nip }}</td>
                                        <td>{{ $sp->karyawan->nama_karyawan }}</td>
                                        <td>{{ $sp->tanggal_sp->format('d M Y') }}</td>
                                        <td>{{ $sp->pelanggaran }}</td>
                                        @can('manajemen karyawan - reward & punishment - surat peringatan - detail')
                                            <td class="d-flex">
                                                <a href="{{ route('surat-peringatan.show', $sp) }}" class="btn btn-outline-info p-1" style="min-width: 60px;">Detail</a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $('#sp-table').DataTable({
        autoWidth: false,
        dom: 'Rlfrtip',
        colReorder: {
            'allowReorder': false
        }
    });
</script>
@endpush
