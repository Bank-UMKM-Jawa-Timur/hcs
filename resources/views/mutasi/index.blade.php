@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Data Mutasi</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a href="{{ route('mutasi.index') }}">Mutasi</a></p>
        </div>
        <div class="card-header row mt-3 mr-8 pr-5" >
            @can('manajemen karyawan - pergerakan karir - data mutasi - create mutasi')
                <a class="mb-3" href="{{ route('mutasi.create') }}">
                    <button  class="is-btn is-primary">Tambah Mutasi</button>
                </a>
            @endcan
        </div>
</div>

<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <form id="form" method="get">
                        @include('components.pagination.header')
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
                                @php
                                    $i = 1;
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                                    if ($pagination) {
                                        $i = $pagination['iteration'];
                                    }
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
</div>

@endsection

@section('custom_script')
    <script>
         $('#page_length').on('change', function() {
            $('#form').submit()
        })

        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        console.log(page_url);
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endsection
