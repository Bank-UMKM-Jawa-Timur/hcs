@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-title">
        <h5 class="card-title">Pejabat Sementara</h5>
        <p class="card-title"><a href="/">Dashboard </a> > <a href="">Pejabat Sementara</a></p>
    </div>
</div>
<div class="card-body">
    <div class="table-responsive overflow-hidden content-center">
        <a href="{{ route('pejabat-sementara.create') }}" class="btn btn-primary">Tambah PJS</a>
        <table class="table whitespace-nowrap" id="pjs-table">
            <thead class="text-primary">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
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
                    <td>{{ $data->jabatan->nama_jabatan }}</td>
                    <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                    <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                    <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                    <td class="d-flex">
                        <form action="{{ route('pejabat-sementara.destroy', $data) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-info p-1 mr-1" style="min-width: 60px;">
                                nonaktifkan
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#pjs-table').DataTable();
</script>
@endpush
