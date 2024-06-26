@extends('layouts.template')
@include('penghasilan-teratur.modal.modal-excel-vitamin')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Data Penghasilan Teratur</h5>
        <p class="card-title"><a href="/">Penghasilan</a> > Penghasilan Teratur</p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5" >
        @can('penghasilan - import - penghasilan teratur - import')
            <a class="ml-3" href="{{ route('penghasilan.import-penghasilan-teratur.create') }}">
                <button class="is-btn is-primary">Import</button>
            </a>
        @endcan
        @can('penghasilan - import - penghasilan teratur - download vitamin')
            @if ($data->total() > 0)
                <a class="ml-3">
                    <button type="button" class="is-btn is-primary ml-2" data-toggle="modal" data-target="#modal-cetak-vitamin">
                        Download Vitamin
                    </button>
                </a>
            @endif
        @endcan
    </div>
</div>
<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        <div class="d-flex justify-content-between mb-4">
                            <div class="p-2 mt-4">
                            <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                            <select name="page_length" id="page_length"
                                class="border px-4 py-2 cursor-pointer rounded appearance-none text-center">
                                <option value="10"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                    10</option>
                                <option value="20"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                    20</option>
                                <option value="50"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                                    50</option>
                                <option value="100"
                                    @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                                    100</option>
                            </select>
                            <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                            </div>
                            <div class="p-2">
                            <label for="q">Cari</label>
                            <input type="search" name="q" id="q" placeholder="Cari disini..."
                                class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                            </div>
                        </div>
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>
                                    Tunjangan
                                </th>
                                @if (auth()->user()->hasRole('cabang') != 'cabang')
                                    <th>
                                        Kantor
                                    </th>
                                @endif
                                <th>
                                    Total Data
                                </th>
                                <th>
                                    Grand Nominal
                                </th>
                                <th>
                                    Tanggal
                                </th>
                                <th>
                                    Aksi
                                </th>
                            </thead>
                            <tbody>
                                @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                                    $i = $page == 1 ? 1 : $start;
                                @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->nama_tunjangan }}</td>
                                        @if (auth()->user()->hasRole('cabang') != 'cabang')
                                            <td>
                                                {{ $item->entitas ?? 'Pusat' }}
                                            </td>
                                        @endif
                                        <td>{{ $item->total_data }}</td>
                                        <td>{{ number_format($item->total_nominal, 0, ",", ".") }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            @php
                                                $cant_detail = false;
                                                $cant_lock_edit = false;
                                                $cant_unlock = false;
                                            @endphp
                                            @if ($item->gajiPerBulan == null)
                                                @if ($item->is_lock != 1)
                                                    @can('penghasilan - lock - penghasilan teratur')
                                                        @php
                                                            $cant_lock_edit = true;
                                                        @endphp
                                                        <a href="{{route('penghasilan.lock')}}?id_tunjangan={{$item->id_transaksi_tunjangan}}&tanggal={{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&created_at={{$item->created_at}}"
                                                            class="btn btn-success p-1">Lock</a>
                                                    @endcan
                                                    @can('penghasilan - edit - penghasilan teratur')
                                                        @php
                                                            $cant_lock_edit = true;
                                                        @endphp
                                                        <a href="{{ route('penghasilan.edit-tunjangan')}}?idTunjangan={{$item->id_transaksi_tunjangan}}&bulan={{$item->bulan}}&tanggal={{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&createdAt={{$item->created_at}}&entitas={{ $item->kd_entitas }}" class="btn btn-outline-warning p-1">Edit</a>
                                                    @endcan
                                                @else
                                                    @can('penghasilan - unlock - penghasilan teratur')
                                                        @php
                                                            $cant_unlock = true;
                                                        @endphp
                                                        <a href="{{route('penghasilan.unlock')}}?id_tunjangan={{$item->id_transaksi_tunjangan}}&tanggal={{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&created_at={{$item->created_at}}"
                                                            class="btn btn-success p-1">Unlock</a>
                                                    @endcan
                                                @endif
                                            @endif
                                            @can('penghasilan - import - penghasilan teratur - detail')
                                                @php
                                                    $cant_detail = true;
                                                @endphp
                                                <a href="{{ route('penghasilan.details', $item->id_transaksi_tunjangan)}}?tanggal={{Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&createdAt={{$item->created_at}}" class="btn btn-outline-info p-1">Detail</a>
                                            @endcan
                                            @if (!$cant_detail && !$cant_lock_edit && !$cant_unlock)
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <div>
                            Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                            </div>
                            <div>
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::bootstrap-4') }}
                            @endif
                            </div>
                        </div>
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
        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
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
