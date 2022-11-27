@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard </a> - Sub Divisi
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <a href="{{ route('sub_divisi.create') }}">
                    <button class="btn btn-primary">tambah sub divisi</button>
                </a>
                <div class="table-responsive overflow-hidden">
                    <table class="table" id="table">
                      <thead class=" text-primary">
                        <th>
                            no
                        </th>
                        <th>
                            Kode Divisi
                        </th>
                        <th>
                            Nama Sub Divisi
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
                                    {{ $item->kd_divisi }}
                                </td>
                                <td>
                                    {{ $item->nama_subdivisi }}
                                </td>
                                <td>
                                  <div class="row">
                                    <a href="{{ route('sub_divisi.edit', $item->id) }}">
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