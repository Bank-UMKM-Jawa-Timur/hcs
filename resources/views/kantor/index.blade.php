@extends('layouts.template')
@section('content')
  <div class="card-header">
      <div class="card-header">
        <h5 class="card-title">Data Kantor</h5>
        <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('kantor.index') }}">Kantor</a></p>
      </div>

      <div class="card-body">
        <div class="col">
          <div class="row">
            <a class="mb-3" href="{{ route('kantor.create') }}">
              <button class="btn btn-primary">tambah kantor</button>
            </a>
            <div class="table-responsive overflow-hidden">
              <table class="table" id="table">
                <thead class=" text-primary">
                  <th>
                      Id Kantor
                  </th>
                  <th>
                      Nama Kantor
                  </th>
                  <th>
                      Aksi
                  </th>
                </thead>
                <tbody>
                  @foreach ($data as $item)
                      <tr>
                          <td>
                              {{ $item['id'] }}
                          </td>
                          <td>
                              {{ $item['nama_kantor'] }}
                          </td>
                          <td>
                            <div class="row">
                              <a href="kantor/{{ $item['id'] }}/edit">
                                <button class="btn btn-warning">
                                  Edit
                                </button>
                              </a>
                              
                              {{-- <form action="{{ route('kantor.destroy', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            
                                <button type="submit" class="btn btn-danger btn-block">Delete</button>
                              </form> --}}
                            </div>
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

@section('custom_script')
  <script>
    $(document).ready( function () {
      $('#table').DataTable();
    });
  </script>
@endsection