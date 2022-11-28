@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Mutasi</h5>
            <p class="card-title"><a href="/">Dashboard</a> / <a href="/mutasi">Mutasi</a></p>
        </div>
    </div>
    <div class="card-body">
        <div class="col">
            <div class="row">
                <a class="mb-3" href="{{ route('mutasi.create') }}">
                    <button  class="btn btn-primary">Tambah Mutasi</button>
                </a>
                <div class="table-responsive overflow-hidden">
                    <table class="table" id="table">
                        <thead class="text-primary">
                            <th>
                                Id Mutasi
                            </th>
                            <th>
                                Nama Karyawan
                            </th>
                            <th>
                                Tanggal Mutasi
                            </th>
                            <th>
                                Bukti SK
                            </th>
                            <th>
                                Keterangan
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->nama_karyawan }}
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
                                    </td>
                                    <td>
                                        {{ $item->bukti_sk }}
                                    </td>
                                    <td>
                                        {{ $item->keterangan }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        });
    </script>
@endsection