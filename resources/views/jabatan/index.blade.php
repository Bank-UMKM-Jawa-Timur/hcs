@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
  <div class="card-header">
      <h5 class="card-title font-weight-bold">Data Jabatan</h5>
      <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('jabatan.index') }}">Jabatan</a></p>
  </div>
  <div class="card-header row mt-3 mr-8 pr-5">
    @if(auth()->user()->hasRole(['admin']))
      <a class="mb-3" href="{{ route('jabatan.create') }}">
        <button class="is-btn is-primary">tambah jabatan</button>
      </a>
    @endif
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
                                No
                            </th>
                            <th>
                                Nama Jabatan
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
                                    <td>
                                        {{ $item->nama_jabatan }}
                                    </td>
                                    <td>
                                      {{-- <div class="row"> --}}
                                        @if(auth()->user()->hasRole(['admin']))
                                        <a href="{{ route('jabatan.edit', $item->kd_jabatan) }}">
                                          <button class="is-btn btn-warning">
                                            Edit
                                          </button>
                                        </a>
                                        @endif

                                        {{-- <form action="{{ route('jabatan.destroy', $item->id) }}" method="POST">
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
