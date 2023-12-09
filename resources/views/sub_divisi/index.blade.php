@extends('layouts.template')
@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Data Sub Divisi</h5>
        <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('sub_divisi.index') }}">Sub Divisi</a></p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5">
        @can('setting - master - sub divisi - create sub divisi')
            <a class="mb-3" href="{{ route('sub_divisi.create') }}">
                <button class="is-btn is-primary">tambah sub divisi</button>
            </a>
        @endcan
    </div>
</div>

<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
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
                                    @can('setting - master - sub divisi - edit sub divisi')
                                        <a href="{{ route('sub_divisi.edit', $item->kd_subdiv) }}">
                                        <button class="is-btn btn-warning">
                                            Edit
                                        </button>
                                        </a>
                                    @endcan
    
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
