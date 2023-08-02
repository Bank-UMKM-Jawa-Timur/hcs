@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Penghasilan Tanpa Pajak</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('ptkp.index') }}">Rentang Umur</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('ptkp.create') }}">
                        <button class="btn btn-primary">Tambah Data</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                            <thead class="text-primary">
                                <th>
                                    No
                                </th>
                                <th>
                                    Kode
                                </th>
                                <th>
                                    PTKP Per Tahun
                                </th>
                                <th>
                                    PTKP Per Bulan
                                </th>
                                <th>
                                    Keterangan
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
                                        <td>{{ $item->kode }}</td>
                                        <td> @currency($item->ptkp_tahun) </td>
                                        <td> @currency($item->ptkp_bulan) </td>
                                        <td>{{ $item->keterangan }}</td>
                                        {{-- @dd($item->kode) --}}
                                        <td>
                                            {{-- <div class="row"> --}}
                                            <div class="d-flex ">
                                                <div class="col">
                                                <a href="{{ route('ptkp.edit', $item->id) }}">
                                                    <button class="btn btn-warning">Edit</button>
                                                </a>

                                            </div>
                                            {{-- <div class="col">
                                                @if ($item->deleted_at == null)
                                                <form action="{{ route('ptkp.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-danger">Delete</button>
                                                </form>
                                                @else
                                                <form action="{{ route('ptkp.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-danger">Delete</button>
                                                </form>
                                                @endif
                                            </div> --}}
                                            </div>
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
