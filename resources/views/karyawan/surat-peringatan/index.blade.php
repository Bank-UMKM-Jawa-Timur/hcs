@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Surat Peringatan</h5>
                <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="">Reward & Punishment</a> > <a href="/surat-peringatan">Surat Peringatan</a></p>
            </div>
            <div class="mt-3 pt-4 pb-4">
                <a href="{{ route('surat-peringatan.create') }}" class="is-btn is-primary ">Tambah Surat Peringatan</a>
            </div>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    @can('manajemen karyawan - reward & punishment - surat peringatan - create')
                    <a href="{{ route('surat-peringatan.create') }}" class="btn btn-primary mr-3">Tambah Surat Peringatan</a>
                    @endcan
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
                                    <th>Aksi</th>
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
                                        <td class="d-flex">
                                            @can('manajemen karyawan - reward & punishment - surat peringatan - detail')
                                            <a href="{{ route('surat-peringatan.show', $sp) }}" class="btn btn-outline-info p-1" style="min-width: 60px;">Detail</a>
                                            @endcan
                                        </td>
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
