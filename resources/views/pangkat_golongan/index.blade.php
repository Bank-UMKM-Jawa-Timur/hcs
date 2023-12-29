@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
  <div class="card-header">
    <h5 class="card-title font-weight-bold">Data Pangkat Dan Golongan</h5>
    <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('pangkat_golongan.index') }}">Pangkat Dan Golongan</a></p>
  </div>
  <div class="card-header row mt-3 mr-8 pr-5">
    @if(auth()->user()->can('setting - master - pangkat & golongan - create pangkat & golongan'))
      <a class="mb-3" href="{{ route('pangkat_golongan.create') }}">
        <div class="pt-2 pb-3">
        <button class="is-btn is-primary">tambah pangkat dan golongan</button>
        </div>
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
                                Pangkat
                            </th>
                            <th>
                                Golongan
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
                                        {{ $item->pangkat }}
                                    </td>
                                    <td>
                                        {{ $item->golongan }}
                                    </td>
                                    <td>
                                      {{-- <div class="row"> --}}
                                        @if(auth()->user()->can('setting - master - pangkat & golongan - edit pangkat & golongan'))
                                        <a href="{{ route('pangkat_golongan.edit', $item->golongan) }}">
                                          <button class="is-btn btn-warning">
                                            Edit
                                          </button>
                                        </a>
                                        @endif

                                        {{-- <form action="{{ route('pangkat_golongan.destroy', $item->golongan) }}" method="POST">
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
