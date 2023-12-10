@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Sub Divisi</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a></p>
        </div>
        
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('sub_divisi.create') }}">
                        <button class="btn btn-primary">tambah sub divisi</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                      <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class=" text-primary">
                            <th>
                                no
                            </th>
                            <th>
                                Kode Sub Divisi
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
                                        {{ $item->kd_subdiv }}
                                    </td>
                                    <td>
                                        {{ $item->nama_subdivisi }}
                                    </td>
                                    <td>
                                      {{-- <div class="row"> --}}
                                        <a href="{{ route('sub_divisi.edit', $item->kd_subdiv) }}">
                                          <button class="btn btn-warning">
                                            Edit
                                          </button>
                                        </a>
                                        
                                        {{-- <form action="{{ route('sub_divisi.destroy', $item->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                      
                                          <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                        </form> --}}
                                      {{-- </div> --}}
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