@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Pengkinian Data Karyawan</h5>
        <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > Pengkinian Data</p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5">
        @can('manajemen karyawan - pengkinian data - create pengkinian data')
        <a class="mb-3" href="{{ route('pengkinian_data.create') }}">
            <button class="is-btn is-primary">Pengkinian Data</button>
        </a>
        @elsecan('manajemen karyawan - pengkinian data - import pengkinian data')
        <a class="ml-3" href="{{ route('pengkinian-data-import-index') }}">
            <button class="is-btn is-primary">Import Pengkinian</button>
        </a>
        @endcan
    </div>
</div>
<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="table-responsive overflow-hidden content-center">
                <form id="form" method="get">
                    @include('components.pagination.header')
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class="text-primary">
                            <th>No</th>
                            <th>NIP</th>
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Kantor</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                            $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                            $number = 1;
                            if ($pagination) {
                            $number = $pagination['iteration'];
                            }
                            @endphp
                            @foreach ($data as $item)
                            @php
                            $cabang = 'Pusat';
                            if ($item->nama_cabang) {
                            $cabang = $item->nama_cabang;
                            }
                            @endphp
                            <tr>
                                <td>{{$number++}}
                                </td>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama_karyawan }}</td>
                                <td>{{ $cabang }}</td>
                                <td>
                                    @php
                                    $ket = null;
                                    if($item->ket_jabatan != null){
                                    $ket = ' ('.$item->ket_jabatan.')';
                                    }
                                    $st_jabatan = DB::table('mst_jabatan')
                                    ->where('kd_jabatan', $item->kd_jabatan)
                                    ->first();

                                    $bagian = '';
                                    if ($item->kd_bagian != null) {
                                    $bagian1 = DB::table('mst_bagian')
                                    ->select('nama_bagian')
                                    ->where('kd_bagian', $item->kd_bagian)
                                    ->first();

                                    if (isset($bagian1)) {
                                    $bagian = $bagian1->nama_bagian;
                                    }
                                    }
                                    @endphp
                                    @if ($item->status_jabatan == "Penjabat")
                                    Pj.{{ $item->nama_jabatan . ' ' . $bagian.$ket }}
                                    @elseif($item->status_jabatan == "Penjabat Sementara")
                                    Pjs.{{ $item->nama_jabatan . ' ' . $bagian.$ket }}
                                    @else
                                    {{ $item->nama_jabatan . ' ' . $bagian.$ket }}
                                    @endif
                                </td>
                                <td style="min-width: 130px">
                                    @can('manajemen karyawan - pengkinian data - detail pengkinian data')
                                        <div class="container">
                                            <a href="{{ route('pengkinian_data.show', $item->nip) }}">
                                                <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                                    Detail
                                                </button>
                                            </a>
                                        </div>
                                    @else
                                    -
                                    @endcan
            </div>
            </td>
            </tr>
            @endforeach
            </tbody>
            </table>
            @include('components.pagination.table-info', [
            'obj' => $data,
            'page_length' => $pagination['page_length'],
            'start' => $pagination['start'],
            'end' => $pagination['end']
            ])
            </form>
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