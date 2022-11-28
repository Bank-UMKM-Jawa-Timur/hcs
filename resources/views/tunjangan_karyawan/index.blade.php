@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header"></div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <a href="{{ route('tunjangan_karyawan.create') }}">
                    <button class="btn btn-primary">tambah tunjangan</button>
                </a>
                <div class="table-responsive">
                    <table class="table" id="table">
                      <thead class=" text-primary">
                        <th>
                            no
                        </th>
                        <th>
                            NIP
                        </th>
                        <th>
                            Nama Karyawan
                        </th>
                        <th>
                            Nama Tunjangan
                        </th>
                        <th>
                          Nominal
                        </th>
                        <th>
                            Aksi
                        </th>
                      </thead>
                      <tbody>
                        @php
                            $i = 1;
                            
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    @php
                                        echo($i++);
                                    @endphp
                                </td>
                                <td>
                                    {{ $item->nip }}
                                </td>
                                <td>
                                    {{ $item->nama_karyawan }}
                                </td>
                                <td>
                                    {{ $item->nama_tunjangan }}
                                </td>
                                <td>
                                    {{ $item->nominal }}
                                </td>
                                <td>
                                  <div class="row">
                                    <a href="{{ route('tunjangan_karyawan.edit', $item->id) }}">
                                      <button class="btn btn-warning">
                                        Edit
                                      </button>
                                    </a>
                                    
                                    {{-- <form action="{{ route('sub_divisi.destroy', $item->id) }}" method="POST">
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