@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
  <div class="card-header">
    <h5 class="card-title font-weight-bold">Data Divisi</h5>
    <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('divisi.index') }}">Divisi</a></p>
  </div>
  <div class="card-header row mt-3 mr-8 pr-5">
    @if(auth()->user()->can('setting - master - divisi - create divisi'))
    <a class="mb-3" href="{{ route('divisi.create') }}">
      <button class="is-btn is-primary">Tambah Divisi</button>
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
                        Kode Divisi
                    </th>
                    <th>
                        Nama Divisi
                    </th>
                    <th>
                        Aksi
                    </th>
                  </thead>
                  @php
                      $i = 1;
                  @endphp
                  <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>
                              {{ $i++ }}
                            </td>
                            <td>
                                {{ $item['kd_divisi'] }}
                            </td>
                            <td>
                                {{ $item['nama_divisi'] }}
                            </td>
                            <td>
                              {{-- <div class="row"> --}}
                                  @if(auth()->user()->can('setting - master - divisi - edit divisi'))
                                  <a href="{{ route('divisi.edit', $item['kd_divisi']) }}">
                                    <button class="is-btn btn-warning">
                                        Edit
                                    </button>
                                  </a>
                                  @endif
  
                                {{-- <form action="{{ route('divisi.destroy', $item['kd_divisi']) }}" method="POST">
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
            <div class="row">
              <div class="col">
              </div>
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
