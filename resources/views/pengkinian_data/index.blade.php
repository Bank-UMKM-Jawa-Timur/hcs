@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Pengkinian Data Karyawan</h5>
            <p class="card-title"><a href="">Pengkinian Data</a> > <a href="">Karyawan</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('pengkinian_data.create') }}">
                      <button class="btn btn-primary">Pengkinian Data</button>
                    </a>
                    <a class="ml-3" href="/pengkinian_data/import">
                      <button class="btn btn-primary">Import Karyawan</button>
                    </a>
                    <a class="ml-3" href="/pengkinian_data/history">
                      <button class="btn btn-primary">History Pengkinian Data</button>
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
                            <tr>
                                <td>1</td>
                                <td>Gatau</td>
                                <td>Gata</td>
                                <td>Gatau</td>
                                <td>Gatau</td>
                                <td>Gatau</td>
                                <td style="min-width: 130px">
                                  <div class="container">
                                    <div class="row">
                                      <a href="/pengkinian_data/update">
                                        <button class="btn btn-outline-warning p-1 mr-2" style="min-width: 60px">
                                          Edit
                                        </button>
                                      </a>

                                      <a href="/pengkinian_data/detail">
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
