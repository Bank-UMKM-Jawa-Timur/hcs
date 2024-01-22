@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Bagian</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('bagian.index') }}" class="text-sm text-gray-500 font-bold">Bagian</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @if(auth()->user()->hasRole(['admin']))
            <a href="{{ route('bagian.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Data
                Bagian</a>
            @endif
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <table class="table whitespace-nowrap" id="table" style="width: 100%">
            <thead class=" text-primary">
                <th>No</th>
                <th>Kode Bagian</th>
                <th>Nama Bagian</th>
                <th>Kode Kantor</th>
                <th>Aksi</th>
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
                        {{ $item->kd_bagian }}
                    </td>
                    <td>
                        {{ $item->nama_bagian }}
                    </td>
                    <td>
                        @php
                        $data1 = DB::table('mst_divisi')
                        ->where('kd_divisi', $item->kd_entitas)
                        ->first();
                        $data2 = DB::table('mst_sub_divisi')
                        ->where('kd_subdiv', $item->kd_entitas)
                        ->first();
                        $data3 = DB::table('mst_cabang')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->first();

                        if (isset($data1)) {
                        $kantor = 'Pusat';
                        } else if (isset($data2)) {
                        $kantor = 'Pusat';
                        } else if (isset($data3)) {
                        $kantor = 'Cabang';
                        } else if ($item->kd_entitas == '2' ) {
                        $kantor = 'Cabang';
                        }
                        @endphp
                        {{ $kantor }}
                    </td>
                    <td>
                        {{-- <div class="row"> --}}
                            @if(auth()->user()->hasRole(['admin']))
                            <a href="{{ route('bagian.edit', $item->kd_bagian) }}">
                                <button class="btn btn-warning-light">
                                    Edit
                                </button>
                            </a>
                            @endif

                            {{-- <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
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
<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive overflow-hidden content-center">
                    
                </div>
            </div>
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
