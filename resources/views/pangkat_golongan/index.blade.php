@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Pangkat Dan Golongan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('pangkat_golongan.index') }}" class="text-sm text-gray-500 font-bold">Pangkat Dan
                        Golongan</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @if (auth()->user()->can('setting - master - pangkat & golongan - create pangkat & golongan'))
                    <a href="{{ route('pangkat_golongan.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i>
                        Tambah Pangkat
                        dan Golongan</a>
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
                                @if (auth()->user()->can('setting - master - pangkat & golongan - edit pangkat & golongan'))
                                    <a href="{{ route('pangkat_golongan.edit', $item->golongan) }}">
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
