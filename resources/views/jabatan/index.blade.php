@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Jabatan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('jabatan.index') }}" class="text-sm text-gray-500 font-bold">Jabatan</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @if (auth()->user()->can('setting - master - jabatan - create jabatan'))
                    <a href="{{ route('jabatan.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Data
                        Jabatan</a>
                @endif
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
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
                                        @if (auth()->user()->can('setting - master - jabatan - edit jabatan'))
                                            <a href="{{ route('jabatan.edit', $item->kd_jabatan) }}">
                                                <button class="btn btn-warning-light">
                                                    Edit
                                                </button>
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
