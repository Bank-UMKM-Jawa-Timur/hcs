@extends('layouts.template')
@section('content')
<div class="card-header">
    <div class="card-header">
      <h5 class="card-title">Penonaktifan Karyawan</h5>
      <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > Penonaktifan Karyawan</p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <a class="mb-3" href="{{ route('penonaktifan.create') }}">
                  <button class="btn btn-primary">tambah penonaktifan</button>
                </a>
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
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
                            $i = 1;
                        @endphp
                        @foreach ($karyawan as $krywn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $krywn->nip }}</td>
                                <td>{{ $krywn->nik }}</td>
                                <td>{{ $krywn->nama_karyawan }}</td>
                                <td>{{ ($krywn->entitas->type == 2) ?
                                    $krywn->entitas->cab->nama_cabang :
                                    'Pusat'
                                }}</td>
                                @php
                                    $prefix = match($krywn->status_jabatan) {
                                        'Penjabat' => 'Pj. ',
                                        'Penjabat Sementara' => 'Pjs. ',
                                        default => '',
                                    };

                                    $jabatan = $krywn->jabatan->nama_jabatan;

                                    $ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

                                    if(isset($krywn->entitas->subDiv)) {
                                        $entitas = $krywn->entitas->subDiv->nama_subdivisi;
                                    } else if(isset($krywn->entitas->div)) {
                                        $entitas = $krywn->entitas->div->nama_divisi;
                                    } else {
                                        $entitas = '';
                                    }

                                    if ($jabatan == "Pemimpin Sub Divisi") {
                                    $jabatan = 'PSD';
                                    } else if ($jabatan == "Pemimpin Bidang Operasional") {
                                    $jabatan = 'PBO';
                                    } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                                    $jabatan = 'PBP';
                                    } else {
                                    $jabatan = $krywn->jabatan->nama_jabatan;
                                    }
                                @endphp
                                <td>{{ $prefix . $jabatan }} {{ $entitas }} {{ $krywn?->bagian?->nama_bagian }} {{ $ket }}</td>
                                <td>{{ $krywn->kategori_penonaktifan ?? '-' }}</td>
                                <td>{{ $krywn->tanggal_penonaktifan != null ? date('d M Y', strtotime($krywn->tanggal_penonaktifan)) : '-' }}</td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_script')
  <script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
    });
  </script>
@endsection