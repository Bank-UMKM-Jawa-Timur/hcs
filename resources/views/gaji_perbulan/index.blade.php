@extends('layouts.app-template')

@section('modal')
    @include('gaji_perbulan.modal.new.proses')
    @include('gaji_perbulan.modal.new.penghasilan-kantor')
    @include('gaji_perbulan.modal.new.modal-upload')
    @include('gaji_perbulan.modal.new.perbarui')
    @include('gaji_perbulan.modal.new.hapus')
    @include('gaji_perbulan.modal.new.kembalikan')
    {{--
    --}}
    @include('gaji_perbulan.script.index')
    @include('gaji_perbulan.modal.new.rincian')
    @include('gaji_perbulan.modal.new.payroll')
    @include('gaji_perbulan.modal.new.lampir-gaji')
@endsection
@push('style')
    <style>
        table.dataTable>thead>tr>th{
            text-align: center;
        }
    </style>
@endpush

@section('content')
<div class="head mt-5">
    <div class="lg:flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Proses Penggajian</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penggajian</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Proses Penggajian</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3 lg:mt-0 mt-5">
            @if (auth()->user()->hasRole('kepegawaian'))
                <button class="btn btn-primary-light lg:text-base text-xs penghasilan-all-kantor" data-modal-id="penghasilan-kantor-modal" data-modal-toggle="modal"><i class="ti ti-file-import"></i> Penghasilan Semua Kantor</button>
            @endif
            @can('penghasilan - proses penghasilan - proses')
                <button class="btn btn-primary btn-proses lg:text-base text-xs" data-modal-id="proses-modal" data-modal-toggle="modal"><i class="ti ti-plus"></i> Proses Penggajian</button>
            @endcan
        </div>
    </div>
</div>
@php
    $already_selected_value = date('Y');
    $earliest_year = 2022;
@endphp
<div class="p-5">
    <div id="alert-additional-content-1" class="p-4 mb-4 text-blue-500 border border-blue-300 rounded-lg bg-blue-50  " role="alert">
    <div class="flex items-center">
        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <h3 class="text-lg font-medium">Pemberitahuan</h3>
    </div>
        <div class="mt-2 mb-4 text-sm">
            Pastikan data <b>penghasilan teratur sudah ter-upload semua</b>, karena akan berpengaruh pada pembentukan pajak.
        </div>
    </div>
</div>
<div class="body-pages">
    <form id="form-filter" action="{{route('gaji_perbulan.index')}}" method="get">
        <input type="hidden" name="tab" id="tab" value="{{\Request::has('tab') ? \Request::get('tab') : 'proses'}}">
        <div class="tab-wrapper nav-tabs">
            <button type="button" class="btn-tab @if(!\Request::has('tab')) active-tab @endif @if (\Request::has('tab'))
            @if(\Request::get('tab') == 'proses') active-tab @endif
            @endif" data-tab="proses">Proses</button>
            <button type="button" class="btn-tab @if(\Request::get('tab') == 'final') active-tab @else  @endif" data-tab="final">Final</button>
            @if (auth()->user()->hasRole('admin'))
                <button type="button" class="btn-tab @if(\Request::get('tab') == 'sampah') active-tab @else  @endif" data-tab="sampah">Sampah</button>
            @endif
        </div>
        <div id="myTabContent">
            <div class="tab-content table-wrapping @if(!\Request::has('tab')) block @endif @if (\Request::has('tab'))
                @if(\Request::get('tab') == 'proses') active show @else hidden @endif
                @endif" id="proses">
                <div class="layout-component">
                    <div class="shorty-table">
                        <label for="">Show</label>
                    <select name="page_length_proses" id="page_length_proses" class="page_length page_length_proses">
                        <option value="10"
                        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 10 ? 'selected' : '' }} @endisset>
                        10</option>
                    <option value="20"
                        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 20 ? 'selected' : '' }} @endisset>
                        20</option>
                    <option value="50"
                        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 50 ? 'selected' : '' }} @endisset>
                        50</option>
                    <option value="100"
                        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 100 ? 'selected' : '' }} @endisset>
                        100</option>
                        </select>
                        <label for="">entries</label>
                    </div>
                    <div class="input-search">
                        <i class="ti ti-search"></i>
                         <input type="search" class="q-proses" placeholder="Search" name="q_proses" id="q"
                            value="{{ isset($_GET['q_proses']) ? $_GET['q_proses'] : '' }}">
                    </div>
                </div>
                <table class="tables-stripped">
                    @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                    @endphp
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            @if (auth()->user()->hasRole('admin'))
                                <th rowspan="2">Kantor</th>
                            @endif
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Bulan</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">File</th>
                            <th colspan="4">Total</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Bruto</th>
                            <th>Potongan</th>
                            <th>Netto</th>
                            <th>PPH21</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 1;
                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                        $total_bruto = 0;
                        $total_potongan = 0;
                        $total_netto = 0;
                        $total_pph = 0;
                    @endphp
                    @forelse ($proses_list as $item)
                        @php
                            $total_bruto += $item->bruto;
                            $total_potongan += $item->grand_total_potongan;
                            $total_netto += $item->netto;
                            $total_pph += $item->total_pph;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            @if (auth()->user()->hasRole('admin'))
                                <td class="text-center">{{ $item->kantor }}</td>
                            @endif
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                            <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
                            <td class="text-center border-none flex gap-2 justify-center">
                                @can('penghasilan - proses penghasilan - rincian')
                                    <a href="#" data-modal-toggle="modal" data-modal-id="rincian-modal" class="btn btn-warning btn-rincian"
                                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Rincian</a>
                                @endcan
                                @can('penghasilan - proses penghasilan - payroll')
                                    <a href="#" class="btn btn-success btn-payroll"
                                    data-modal-toggle="modal" data-modal-id="payroll-modal"
                                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Payroll</a>
                                @endcan
                            </td>
                            @if ($item->bruto == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->bruto, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->grand_total_potongan == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->grand_total_potongan, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->netto < 0)
                                <td class="text-right">
                                    Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                                </td>
                            @elseif ($item->netto == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->netto, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->total_pph < 0)
                                <td class="text-right">
                                    Rp ({{number_format(str_replace('-', '', $item->total_pph), 0, ',', '.')}})
                                </td>
                            @elseif ($item->total_pph == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->total_pph, 0, ',', '.')}}
                                </td>
                            @endif
                            <td class="text-center border-none  justify-center flex">
                                @if($item->status == 'proses')
                                    @if($item->total_penyesuaian > 0)
                                        @can('penghasilan - proses penghasilan - proses')
                                            <a href="#" class="btn btn-warning btn-perbarui"
                                                data-modal-id="penyesuaian-modal" data-modal-toggle="penyesuaian-modal"
                                                data-batch_id="{{$item->id}}">Perbarui</a>
                                        @endcan
                                    @else
                                        @can('penghasilan - proses penghasilan - proses')
                                            @if ($item->tanggal_cetak != null && $item->tanggal_upload == null)
                                                @can('penghasilan - proses penghasilan - lampiran gaji - upload')
                                                    @if ($item->file == null)
                                                        <a class="btn btn-primary" data-modal-id="modalUploadfile" data-modal-toggle="modal" href="#" id="uploadFile"  data-toggle="modal" data-target="#modalUploadfile" data-batch_id="{{ $item->id }}">
                                                            <i class="ti ti-circle-check"></i>Finalisasi
                                                        </a>
                                                    @endif
                                                @endcan
                                            @endif
                                        @endcan
                                    @endif
                                @else
                                    -
                                @endif
                                @if (auth()->user()->hasRole('admin'))
                                    <a href="#" class="btn btn-danger d-flex justify-center btn-delete"
                                        data-batch_id="{{$item->id}}"
                                        data-kantor="{{$item->kantor}}"
                                        data-bulan="{{$item->bulan}}"
                                        data-tahun="{{$item->tahun}}"
                                        >Hapus</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('admin') ? 12 : 11 }}" class="text-center">Belum ada penghasilan yang diproses.</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                        <th class="text-center" colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}">Total</th>
                        @if ($total_bruto > 0)
                            <th class="text-right">
                                RP {{number_format($total_bruto, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_potongan > 0)
                            <th class="text-right">
                                RP {{number_format($total_potongan, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_netto > 0)
                            <th class="text-right">
                                RP {{number_format($total_netto, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_pph > 0)
                            <th class="text-right">
                                RP {{number_format($total_pph, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        <th></th>
                    </tfoot>
                </table>
                <div class="table-footer">
                    <div class="showing">
                        Showing {{$start}} to {{$end}} of {{$proses_list->total()}} entries
                    </div>
                    <div>
                        @if ($proses_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $proses_list->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="tab-content table-wrapping @if(\Request::get('tab') == 'final') block @else hidden @endif" id="final" id="final">
                <div class="layout-component">
                    <div class="shorty-table">
                        <label for="">Show</label>
                        <select  name="page_length_final" id="page_length_final" class="page_length page_length_final">
                        <option value="10"
                        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 10 ? 'selected' : '' }} @endisset>
                        10</option>
                    <option value="20"
                        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 20 ? 'selected' : '' }} @endisset>
                        20</option>
                    <option value="50"
                        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 50 ? 'selected' : '' }} @endisset>
                        50</option>
                    <option value="100"
                        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 100 ? 'selected' : '' }} @endisset>
                        100</option>
                        </select>
                        <label for="">entries</label>
                    </div>
                    <div class="input-search">
                        <i class="ti ti-search"></i>
                        <input type="search" class="q-final" placeholder="Search" name="q_final" id="q" value="{{ isset($_GET['q_final']) ? $_GET['q_final'] : '' }}">
                    </div>
                </div>
                @php
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                @endphp
                <table class="tables-stripped" id="table_lampiran_gaji">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            @if (auth()->user()->hasRole('admin'))
                                <th rowspan="2">Kantor</th>
                            @endif
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Bulan</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">File</th>
                            <th colspan="4">Total</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Bruto</th>
                            <th>Potongan</th>
                            <th>Netto</th>
                            <th>PPH21</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $total_bruto = 0;
                            $total_potongan = 0;
                            $total_netto = 0;
                            $total_pph = 0;
                        @endphp
                        @forelse ($final_list as $item)
                            @php
                            $total_bruto += $item->bruto;
                            $total_potongan += $item->grand_total_potongan;
                            $total_netto += $item->netto;
                            $total_pph += $item->total_pph;
                            @endphp
                            <tr>
                                <td class="text-center">{{$i++}}</td>
                                @if (auth()->user()->hasRole('admin'))
                                    <td class="text-center">{{ $item->kantor }}</td>
                                @endif
                                <td class="text-center">{{ $item->tahun }}</td>
                                <td class="text-center">{{ $months[$item->bulan] }}</td>
                                <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
                                <td class="text-center flex justify-center gap-5">
                                    <a href="#" data-modal-id="rincian-modal" data-modal-toggle="modal" class="btn btn-warning btn-rincian"
                                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Rincian</a>
                                    <a href="#" data-modal-id="payroll-modal" data-modal-toggle="modal" class="btn btn-success btn-payroll"
                                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Payroll</a>
                                </td>
                                {{-- bruto --}}
                                @if ($item->bruto == 0)
                                    <td class="text-center">-</td>
                                @else
                                    <td class="text-right">
                                        Rp {{number_format($item->bruto, 0, ',', '.')}}
                                    </td>
                                @endif
                                {{-- potongan --}}
                                @if ($item->grand_total_potongan == 0)
                                    <td class="text-center">-</td>
                                @else
                                    <td class="text-right">
                                        Rp {{number_format($item->grand_total_potongan, 0, ',', '.')}}
                                    </td>
                                @endif
                                {{-- netto --}}
                                @if ($item->netto < 0)
                                    <td class="text-right">
                                        Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                                    </td>
                                @elseif ($item->netto == 0)
                                    <td class="text-center">-</td>
                                @else
                                    <td class="text-right">
                                        Rp {{number_format($item->netto, 0, ',', '.')}}
                                    </td>
                                @endif
                                {{-- pph --}}
                                @if ($item->total_pph < 0)
                                    <td class="text-right">
                                        Rp ({{number_format(str_replace('-', '', $item->total_pph), 0, ',', '.')}})
                                    </td>
                                @elseif ($item->total_pph == 0)
                                    <td class="text-center">-</td>
                                @else
                                    <td class="text-right">
                                        Rp {{number_format($item->total_pph, 0, ',', '.')}}
                                    </td>
                                @endif
                                {{-- aksi --}}
                                @if (auth()->user()->hasRole('admin'))
                                    <td>
                                        <a href="#" class="btn btn-danger d-flex justify-center btn-delete"
                                            data-batch_id="{{$item->id}}"
                                            data-kantor="{{$item->kantor}}"
                                            data-bulan="{{$item->bulan}}"
                                            data-tahun="{{$item->tahun}}">
                                            Hapus
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                            <td colspan="{{auth()->user()->hasRole('admin') ? 11 : 10}}" class="text-center">Belum ada penghasilan yang telah difinal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <th class="text-center" colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}">Total</th>
                        @if ($total_bruto > 0)
                            <th class="text-right">
                                RP {{number_format($total_bruto, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_potongan > 0)
                            <th class="text-right">
                                RP {{number_format($total_potongan, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_netto > 0)
                            <th class="text-right">
                                RP {{number_format($total_netto, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        @if ($total_pph > 0)
                            <th class="text-right">
                                RP {{number_format($total_pph, 0, ',', '.')}}
                            </th>
                        @else
                            <th class="text-center">-</th>
                        @endif
                        <th></th>
                    </tfoot>
                </table>
                <div class="table-footer">
                    <div class="showing">
                        Showing {{$start}} to {{$end}} of {{$final_list->total()}} entries
                        </div>
                        <div>
                        @if ($final_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $final_list->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </div>
            @if (auth()->user()->hasRole('admin'))
                <div class="tab-content table-wrapping @if(\Request::get('tab') == 'sampah') block @else hidden @endif" id="sampah" id="sampah">
                    <div class="layout-component">
                        <div class="shorty-table">
                            <label for="">Show</label>
                            <select  name="page_length_sampah" id="page_length_sampah" class="page_length">
                            <option value="10"
                            @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                            10</option>
                        <option value="20"
                            @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 20 ? 'selected' : '' }} @endisset>
                            20</option>
                        <option value="50"
                            @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 50 ? 'selected' : '' }} @endisset>
                            50</option>
                        <option value="100"
                            @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 100 ? 'selected' : '' }} @endisset>
                            100</option>
                            </select>
                            <label for="">entries</label>
                        </div>
                        <div class="input-search">
                            <i class="ti ti-search"></i>
                            <input type="search" class="q-sampah" placeholder="Search" name="q_sampah" id="q" value="{{ isset($_GET['q_sampah']) ? $_GET['q_sampah'] : '' }}">
                        </div>
                    </div>
                    @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length_sampah']) ? $_GET['page_length_sampah'] : 10;
                        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                    @endphp
                    <table class="tables-stripped">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                @if (auth()->user()->hasRole('admin'))
                                    <th rowspan="2">Kantor</th>
                                @endif
                                <th rowspan="2">Tahun</th>
                                <th rowspan="2">Bulan</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">File</th>
                                <th colspan="4">Total</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Bruto</th>
                                <th>Potongan</th>
                                <th>Netto</th>
                                <th>PPH21</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                            $total_bruto = 0;
                            $total_potongan = 0;
                            $total_netto = 0;
                            $total_pph = 0;
                        @endphp
                        @forelse ($sampah as $item)
                        @php
                        $total_bruto += $item->bruto;
                        $total_potongan += $item->grand_total_potongan;
                        $total_netto += $item->netto;
                        $total_pph += $item->total_pph;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            @if (auth()->user()->hasRole('admin'))
                                <td class="text-center">{{ $item->kantor }}</td>
                            @endif
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                            <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
                            <td class="text-center flex justify-center gap-5">
                                <a href="#" data-modal-id="rincian-modal" data-modal-toggle="modal" class="btn btn-warning btn-rincian"
                                    data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Rincian</a>
                                <a href="#" data-modal-id="payroll-modal" data-modal-toggle="modal" class="btn btn-success btn-payroll"
                                    data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Payroll</a>
                            </td>
                            @if ($item->bruto == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->bruto, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->grand_total_potongan == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->grand_total_potongan, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->netto < 0)
                                <td class="text-right">
                                    Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                                </td>
                            @elseif ($item->netto == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->netto, 0, ',', '.')}}
                                </td>
                            @endif
                            @if ($item->total_pph < 0)
                                <td class="text-right">
                                    Rp ({{number_format(str_replace('-', '', $item->total_pph), 0, ',', '.')}})
                                </td>
                            @elseif ($item->total_pph == 0)
                                <td class="text-center">-</td>
                            @else
                                <td class="text-right">
                                    Rp {{number_format($item->total_pph, 0, ',', '.')}}
                                </td>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                                <td>
                                    <a href="#" class="btn btn-danger d-flex justify-center btn-restore"
                                        data-batch_id="{{$item->id}}"
                                        data-kantor="{{$item->kantor}}"
                                        data-bulan="{{$item->bulan}}"
                                        data-tahun="{{$item->tahun}}"
                                        >Kembalikan</a>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{auth()->user()->hasRole('admin') ? 11 : 10}}" class="text-center">Belum ada penghasilan yang telah dihapus.</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="table-footer">
                        <div class="showing">
                        Showing {{$start}} to {{$end}} of {{$sampah->total()}} entries
                        </div>
                        <div>
                        @if ($sampah instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $sampah->links('pagination::tailwind') }}
                        @endif
                    </div>
                    </div>
                </div>
            @endif
        </div>
    </form>


</div>
<form id="form-final" action="{{route('gaji_perbulan.proses_final')}}" method="post">
    <input type="hidden" name="_token" id="token">
    <input type="hidden" name="batch_id" id="batch_id">
</form>
@endSection

@push('extraScript')
<script>
    $(document).ready(function() {
        $('#page_length_proses, #page_length_final, #page_length_sampah').on('change', function(e) {
            e.preventDefault();
            // Set the active tab value before submitting the form
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();
        });
        let tab = $('.btn-tab.active-tab').data('tab');
        if (tab == 'final') {
            $('.page_length_proses').prop('disabled', 'disabled');
            $('#page_length_final').prop('disabled', false);
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);
        }else if (tab == 'proses') {
            $('.page_length_proses').prop('disabled', false);
            $('#page_length_final').prop('disabled', 'disabled');
            $('#page_length_sampah').prop('disabled', 'disabled');
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);
        }else{
            $('.page_length_proses').prop('disabled', 'disabled');
            $('#page_length_final').prop('disabled', 'disabled');
            $('#page_length_sampah').prop('disabled', false);
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);

        }
        $('.q-proses').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })
        $('.q-final').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })
        $('.q-sampah').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })

        $('#form-filter').on('submit', function() {
            $('.loader-wrapper').css('display: none;')
            $('.loader-wrapper').addClass('d-block')
            $(".loader-wrapper").fadeOut("slow");
        })

        $(".btn-tab").on("click", function (e) {
            $('#tab').val($(this).data('tab'))
            let tab = $(this).data('tab');
            if (tab == 'final') {
                $('.page_length_proses').prop('disabled', 'disabled');
                $('#page_length_final').prop('disabled', false);
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                // $('#form-filter').submit();
                refreshPagination();
            }else if (tab == 'proses') {
                $('.page_length_proses').prop('disabled', false);
                $('#page_length_final').prop('disabled', 'disabled');
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                refreshPagination();
                // $('#form-filter').submit();
            }else{
                $('.page_length_proses').prop('disabled', 'disabled');
                $('#page_length_final').prop('disabled', 'disabled');
                $('#page_length_sampah').prop('disabled', false);
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                refreshPagination();
            }
        })
        refreshPagination();
        // Adjust pagination url
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }

    });
        function refreshPagination() {
            var btn_pagination = $("#myTabContent .block .pagination").find("a");
            var page_url = window.location.href;
            console.log(btn_pagination);

            // Your custom query parameter and its value
            let tab = $('#tab').val();
            var customParam = "";
            customParam += "&tab=" + $('#tab').val();
            if (tab == 'proses') {
                customParam += "&page_length_proses=" + $('.page_length_proses').val();
                customParam += "&q_proses=" + $('#q').val();
            } else if(tab == 'final'){
                customParam += "&page_length_final=" + $('.page_length_final').val();
                customParam += "&q_final=" + $('#q').val();
            } else {
                customParam += "&page_length_final=" + $('.page_length_sampah').val();
                customParam += "&q_sampah=" + $('#q').val();

            }

            btn_pagination.each(function (i, obj) {
                // Clone the original href to avoid modifying the original link
                var href = $(this).attr("href");

                // Check if the href already contains a question mark
                var separator = href.includes("?") ? "&" : "?";

                // Append the custom query parameter and its value
                var updatedHref = href + separator + customParam;
                updatedHref = updatedHref.replaceAll('&&', '&')

                // Update the href attribute of the pagination link
                $(this).attr("href", updatedHref);
            });
        }

    $(`.btn-delete`).on('click', function(){
        const target = '#delete-modal';
        const kantor = $(this).data("kantor");
        const bulan = $(this).data("bulan");
        const tahun = $(this).data("tahun");
        const id = $(this).data("batch_id");
        console.log(id);

        $(`${target} #kantor`).html(kantor);
        $(`${target} #bulan`).html(bulan);
        $(`${target} #tahun`).html(tahun);
        $(`${target} #id`).val(id);
        $(`${target}`).removeClass('hidden');
    })
    $(`.btn-restore`).on('click', function(){
        const target = '#restore-modal';
        const kantor = $(this).data("kantor");
        const bulan = $(this).data("bulan");
        const namaBulan = new Date(2022, bulan - 1, 1).toLocaleString('id-ID', { month: 'long' });
        const tahun = $(this).data("tahun");
        const id = $(this).data("batch_id");

        $(`${target} #kantor`).html(kantor);
        $(`${target} #bulan`).html(namaBulan);
        $(`${target} #tahun`).html(tahun);
        $(`${target} #id`).val(id);
        $(`${target}`).removeClass('hidden');
    })
</script>
@endpush
