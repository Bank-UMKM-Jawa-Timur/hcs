@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-header">
                <p class="card-title"><a href="/">Dashboard</a> / <a href="/">Karyawan</a> / Demosi </p>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <a href="{{ route('demosi.create') }}">
                        <button  class="btn btn-primary">Tambah</button>
                    </a>
                    <div class="table">
                        <table class="table" id="table">
                            <thead class="text-primary">
                                <th>
                                    NIP
                                </th>
                                <th>
                                    Nama Karyawan
                                </th>
                                <th>
                                    Tanggal Demosi
                                </th>
                                <th>
                                    Jabatan Lama
                                </th>
                                <th>
                                    Jabatan Baru
                                </th>
                                <th>
                                    Bukti SK
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            {{ $item->nip }}
                                        </td>
                                        <td>
                                            {{ $item->nama_karyawan }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
                                        </td>
                                        <td>
                                            {{ $item->jabatan_lama }}
                                        </td>
                                        <td>
                                            {{ $item->jabatan_baru }}
                                        </td>
                                        <td>
                                            {{ $item->bukti_sk }}
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