@extends('layouts.app-template')
@include('penghasilan-teratur.modal.modal-excel-vitamin')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Penghasilan Teratur</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Penghasilan Teratur</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @can('penghasilan - import - penghasilan teratur - import')
            <a href="{{ route('penghasilan.import-penghasilan-teratur.create') }}" class="btn btn-primary-light">
                <i class="ti ti-file-import"></i> Import</a>
            @endcan
            @can('penghasilan - import - penghasilan teratur - download vitamin')
            @if ($data->total() > 0)
            <button data-modal-id="modal-cetak-vitamin" data-modal-toggle="modal" class="btn btn-primary">
                <i class="ti ti-download"></i> Download Vitamin
            </button>
            @endif
            @endcan
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form id="form" method="get">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="page_length">Show</label>
                    <select name="page_length" class="mr-3 text-sm text-neutral-400 page_length" id="page_length">
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
                    <label for="page_length">entries</label>
                </div>
                <div class="input-search">
                    <i class="ti ti-search"></i>
                    <input type="search" placeholder="Search" name="q" id="q"
                        value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                </div>
            </div>
            <table class="tables">
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
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->nama_tunjangan }}</td>
                        @if (auth()->user()->hasRole('cabang') != 'cabang')
                            <td>
                                {{ $item->nama_cabang }}
                            </td>
                        @endif
                        <td>{{ $item->total_data }}</td>
                        <td>{{ number_format($item->total_nominal, 0, ",", ".") }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="flex justify-center">
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
                                            class="btn btn-success">Lock</a>
                                    @endcan
                                    @can('penghasilan - edit - penghasilan teratur')
                                        @php
                                            $cant_lock_edit = true;
                                        @endphp
                                        <a href="{{ route('penghasilan.edit-tunjangan')}}?idTunjangan={{$item->id_transaksi_tunjangan}}&tanggal={{Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&createdAt={{$item->created_at}}&kdEntitas={{ $item->kd_entitas }}&bulan={{$item->bulan}}"
                                            class="btn btn-warning-light">Edit</a>
                                    @endcan
                                @else
                                    @if (!isset($item->status) || $item->status != 'final')
                                        @can('penghasilan - unlock - penghasilan teratur')
                                            @php
                                                $cant_unlock = true;
                                            @endphp
                                            <a href="{{route('penghasilan.unlock')}}?id_tunjangan={{$item->id_transaksi_tunjangan}}&tanggal={{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&created_at={{$item->created_at}}"
                                                class="btn btn-success-light">Unlock</a>
                                        @endcan
                                    @endif
                                @endif
                            @endif
                            @can('penghasilan - import - penghasilan teratur - detail')
                                @php
                                    $cant_detail = true;
                                @endphp
                                <a href="{{ route('penghasilan.details', $item->id_transaksi_tunjangan)}}?tanggal={{Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y-m-d')}}&createdAt={{$item->created_at}}&kdEntitas={{ $item->kd_entitas }}"
                                    class="btn btn-primary-light ml-2">
                                    Detail
                                </a>
                            @endcan
                            @if (!$cant_detail && !$cant_lock_edit && !$cant_unlock)
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <th colspan="7">Data Kosong</th>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="table-footer">
                <div class="showing">
                    Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                </div>
                <div class="pagination">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>
        </form>
</div>
@endsection

@push('extraScript')
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
@endpush
