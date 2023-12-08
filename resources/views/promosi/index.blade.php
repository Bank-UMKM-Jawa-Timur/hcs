@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Data Promosi</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a href="{{ route('promosi.index') }}">Promosi</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    @can('manajemen karyawan - pergerakan karir - data promosi - create promosi')
                    <a class="mb-3" href="{{ route('promosi.create') }}">
                        <button  class="is-btn is-primary">Tambah Promosi</button>
                    </a>
                    @endcan
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
                                            <span style="display: none;">{{ date('Ymd', strtotime($item->tanggal_pengesahan)) }}</span>
                                            {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
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
