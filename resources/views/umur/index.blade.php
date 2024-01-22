@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Data Rentang Umur
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('tunjangan.index') }}" class="text-sm text-gray-500 font-bold">Rentang Umur</a>
            </div>
        </div>
        @if(auth()->user()->hasRole(['admin']))
            <a class="mb-3" href="{{ route('umur.create') }}">
                <button class="btn btn-primary is-btn is-primary">Tambah Rentang Umur</button>
            </a>
        @endif
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <div class="">
            <div class="col-lg-12">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="tables whitespace-nowrap" id="table" style="width: 100%">
                        <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Umur Awal
                            </th>
                            <th>
                                Umur Akhir
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
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->u_awal }}</td>
                                    <td>{{ $item->u_akhir }}</td>
                                    <td>
                                        @if(auth()->user()->hasRole(['admin']))
                                            <a href="{{ route('umur.edit', $item->id) }}">
                                                <button class="btn btn-warning is-btn btn-warning">Edit</button>
                                            </a>
                                        @endif
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