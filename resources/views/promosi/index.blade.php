@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Promosi</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a href="/promosi">Promosi</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('promosi.create') }}">
                        <button  class="btn btn-primary">Tambah Promosi</button>
                    </a>
                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead class="text-primary">
                                <th>
                                    #
                                </th>
                                <th>
                                    NIP
                                </th>
                                <th>
                                    Nama Karyawan
                                </th>
                                <th>
                                    Tanggal Promosi
                                </th>
                                <th>
                                    NIP Lama
                                </th>
                                <th>
                                    NIP Baru
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
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                        </td>
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
                                            {{ $item->nip_lama ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $item->nip_baru ?? '-' }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ ($item->status_jabatan_lama != null) ? $item->status_jabatan_lama.' - ' : '' }}{{ $item->jabatan_lama }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ ($item->status_jabatan_baru != null) ? $item->status_jabatan_baru.' - ' : '' }}{{ $item->jabatan_baru }}
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