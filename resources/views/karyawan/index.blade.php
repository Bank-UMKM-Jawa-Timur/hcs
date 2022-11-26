@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/karyawan">Karyawan </a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <a href="{{ route('karyawan.create') }}">
                  <button class="btn btn-primary">tambah karyawan</button>
                </a>
                <a href="{{ route('import') }}">
                  <button class="btn btn-primary">import karyawan</button>
                </a>
                <div class="table-responsive overflow-hidden">
                    <table class="table" id="table">
                      <thead class=" text-primary">
                        <th>
                            No
                        </th>
                        <th>NIP</th>
                        <th>
                            Nama karyawan
                        </th>
                        <th>
                            Aksi
                        </th>
                      </thead>
                      @php
                          $no = 1;
                      @endphp
                      <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    {{ $no++ }}
                                </td>
                                <td>{{ $item->nip }}</td>
                                <td>
                                    {{ $item->nama_karyawan }}
                                </td>
                                <td>
                                  <div class="row">
                                    <a href="{{ route('karyawan.edit', $item->nip) }}">
                                      <button class="btn btn-warning">
                                        Edit
                                      </button>
                                    </a>
                                    
                                    {{-- <form action="{{ route('karyawan.destroy', $item->nip) }}" method="POST">
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
@endsection

@section('custom_script')
  <script>
    $(document).ready( function () {
      $('#table').DataTable();
    });
  </script>
@endsection 