@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Data Tunjangan
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('tunjangan.index') }}" class="text-sm text-gray-500 font-bold">Tunjangan</a>
            </div>
        </div>
        @if(auth()->user()->hasRole(['admin']))
            <a class="mb-3" href="{{ route('tunjangan.create') }}">
            <button class="btn btn-primary is-btn is-primary">tambah tunjangan</button>
            </a>
        @endif
    </div>
</div>

<div class="body-pages p-3">
    <div class="table-wrapping">
        <div class="">
            <div class="col-lg-12">
            <div class="table-responsive overflow-hidden content-center">
                <table class="table whitespace-nowrap" id="table" style="width: 100%">
                    <thead class=" text-primary">
                    <th>
                        No
                    </th>
                    <th>
                        Nama Tunjangan
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
                                {{ $item->nama_tunjangan }}
                            </td>
                            <td>
                                {{-- <div class="row"> --}}
                                @if(auth()->user()->hasRole(['admin']))
                                <a href="{{ route('tunjangan.edit', $item->id) }}">
                                    <button class="btn is-btn btn-warning">
                                    Edit
                                    </button>
                                </a>
                                @endif

                                {{-- <form action="{{ route('tunjangan.destroy', $item->id) }}" method="POST">
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