@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Surat Peringatan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="">Surat Peringatan</a></p>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="d-flex">
        <a href="{{ route('surat-peringatan.create') }}" class="btn btn-primary">Tambah Surat Peringatan</a>
    </div>
    <div class="table-responsive content-center mt-2">
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
                @forelse ($sps as $sp)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sp->no_sp }}</td>
                    <td>{{ $sp->nip }}</td>
                    <td>{{ $sp->karyawan->nama_karyawan }}</td>
                    <td>{{ $sp->tanggal_sp->format('d M Y') }}</td>
                    <td>{{ $sp->pelanggaran }}</td>
                    <td class="d-flex">
                        <a href="{{ route('surat-peringatan.edit', $sp) }}" class="btn btn-outline-warning p-1 mr-1" style="min-width: 60px;">Edit</a>
                        <a href="{{ route('surat-peringatan.show', $sp) }}" class="btn btn-outline-info p-1" style="min-width: 60px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">Data masih kosong</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#sp-table').DataTable({
        'autoWidth': false,
        'dom': 'Rlfrtip',
        'colReorder': {
            'allowReorder': false
        }
    });
</script>
@endpush
