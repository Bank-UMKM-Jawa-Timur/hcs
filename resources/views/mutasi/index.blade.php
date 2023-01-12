@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Mutasi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/mutasi">Mutasi</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('mutasi.create') }}">
                        <button  class="btn btn-primary">Tambah Mutasi</button>
                    </a>
                    <div class="table-responsive overflow-hidden">
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
                                        Tanggal Mutasi
                                    </th>
                                    <th>
                                        Jabatan Lama
                                    </th>
                                    <th>
                                        Jabatan Baru
                                    </th>
                                    <th>
                                        Kantor Lama
                                    </th>
                                    <th>
                                        Kantor Baru
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
                                                {{ $item->kantor_lama ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->kantor_baru ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->bukti_sk }}
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

</div>
@endsection

@section('custom_script')
    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        });
    </script>
@endsection
