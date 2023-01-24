@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Rentang Umur</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/umur">Rentang Umur</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('umur.create') }}">
                        <button class="btn btn-primary">Tambah Rentang Umur</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
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
                                            {{-- <div class="row"> --}}
                                                <a href="{{ route('umur.edit', $item->id) }}">
                                                    <button class="btn btn-warning">Edit</button>
                                                </a>
    
                                            {{-- <form action="{{ route('umur.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            
                                                <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                            </form> --}}
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