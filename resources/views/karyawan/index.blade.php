@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('karyawan.create') }}">
                      <button class="btn btn-primary">tambah karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('import') }}">
                      <button class="btn btn-primary">import karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('klasifikasi_karyawan') }}">
                      <button class="btn btn-primary">Export Karyawan</button>
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
                              Kantor
                            </th>
                            <th>
                              Jabatan
                            </th>
                            <th>
                                Aksi
                            </th>
                          </thead>
                          <tbody>
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

                                        $ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

                                        if(isset($krywn->entitas->subDiv)) {
                                            $entitas = $krywn->entitas->subDiv->nama_subdivisi;
                                        } else if(isset($krywn->entitas->div)) {
                                            $entitas = $krywn->entitas->div->nama_divisi;
                                        } else {
                                            $entitas = '';
                                        }
                                    @endphp
                                    <td>{{ $prefix . $krywn->jabatan->nama_jabatan }} {{ $entitas }} {{ $krywn?->bagian?->nama_bagian }} {{ $ket }}</td>
                                    <td style="min-width: 130px">
                                      <div class="container">
                                        <div class="row">
                                          <a href="{{ route('karyawan.edit', $krywn->nip) }}">
                                            <button class="btn btn-outline-warning p-1 mr-2" style="min-width: 60px">
                                              Edit
                                            </button>
                                          </a>

                                          <a href="{{ route('karyawan.show', $krywn->nip) }}">
                                            <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                              Detail
                                            </button>
                                          </a>
                                        </div>
                                      </div>

                                        {{-- <form action="{{ route('karyawan.destroy', $krywn->nip) }}" method="POST">
                                          @csrf
                                          @method('DELETE')

                                          <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                        </form> --}}
                                    </td>
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
