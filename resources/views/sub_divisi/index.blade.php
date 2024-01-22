@extends('layouts.app-template')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Sub Divisi</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Sub Divisi</p>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @if(auth()->user()->hasRole(['admin']))
                <a href="{{ route('sub_divisi.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Sub Divisi</a>
            @endif
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
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
                        @if(auth()->user()->hasRole(['admin']))
                            <a href="{{ route('sub_divisi.edit', $item->kd_subdiv) }}">
                            <button class="btn btn-warning-light">
                                Edit
                            </button>
                            </a>
                        @endif

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