@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
  <div class="flex gap-5 justify-between items-center">
      <div class="heading">
          <h2 class="text-2xl font-bold tracking-tighter">Data Kantor Cabang</h2>
          <div class="breadcrumb">
           <a href="#" class="text-sm text-gray-500">Setting</a>
           <i class="ti ti-circle-filled text-theme-primary"></i>
           <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
           <i class="ti ti-circle-filled text-theme-primary"></i>
           <a href="{{ route('user.index') }}" class="text-sm text-gray-500 font-bold">Kantor Cabang</a>
          </div>
      </div>
      <div class="button-wrapper flex gap-3">
          @if(auth()->user()->can('setting - master - kantor cabang - create kantor cabang'))
            <a href="{{ route('cabang.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Cabang</a>
          @endif
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
            Nama Cabang
        </th>
        <th>
            Alamat
        </th>
        <th class="text-center">
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
                    {{ $item->nama_cabang }}
                </td>
                <td>
                    {{ $item->alamat_cabang }}
                </td>
                <td class="flex">
                  <a href="{{ route('cabang.edit', $item->kd_cabang) }}" class="btn btn-warning">Edit</a>
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