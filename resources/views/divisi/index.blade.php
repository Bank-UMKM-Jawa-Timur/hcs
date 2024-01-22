@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Divisi</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('divisi.index') }}" class="text-sm text-gray-500 font-bold">Divisi</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a href="{{ route('divisi.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Divisi</a>
            </div>
        </div>
    </div>

    <div class="body-pages">
      <div class="table-wrapping">
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
                          @if (auth()->user()->can('setting - master - divisi - edit divisi'))
                              <a href="{{ route('divisi.edit', $item['kd_divisi']) }}">
                                  <button class="btn btn-warning">
                                      Edit
                                  </button>
                              </a>
                          @endif

                          {{-- <form action="{{ route('divisi.destroy', $item['kd_divisi']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            
                                <button type="submit" class="btn btn-danger btn-block">Delete</button>
                              </form> --}}
                                                      {{--
                            </div> --}}
                      </td>
                  </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
@endsection

@push('extraScript')
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
@endpush
