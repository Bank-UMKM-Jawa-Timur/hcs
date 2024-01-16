@extends('layouts.template')
@include('gaji_perbulan.modal.proses')
@include('gaji_perbulan.modal.perbarui')
@include('gaji_perbulan.modal.modal-upload')
@include('gaji_perbulan.modal.penghasilan-kantor')
@include('gaji_perbulan.script.index')
@include('gaji_perbulan.modal.perbarui')
@include('gaji_perbulan.modal.rincian')
@include('gaji_perbulan.modal.payroll')
@include('gaji_perbulan.modal.lampir-gaji')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold">Proses Penghasilan Bulanan</h5>
                    <p class="card-title"><a href="">Penghasilan </a> > Proses Penghasilan Bulanan</p>
                </div>
                <div>
                    @if (auth()->user()->hasRole('kepegawaian'))
                        <button type="button" class="is-btn is-primary btn-show">Penghasilan Semua Kantor</button>
                    @endif
                    @can('penghasilan - proses penghasilan')
                        <button type="button" class="is-btn is-primary btn-proses">Proses</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    <div class="row">
        <div class="col">
            <div class="alert alert-info mx-3" role="alert">
                Harap cek kembali data tunjangan sebelum melakukan proses tunjangan.
            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="card-body">
            <form id="form-filter" action="{{route('gaji_perbulan.index')}}" method="get">
                <input type="hidden" name="tab" id="tab" value="{{\Request::has('tab') ? \Request::get('tab') : 'proses'}}">
                <div class="col-l-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" data-tab="proses">
                            <button class="nav-link border @if(!\Request::has('tab')) active @endif @if(\Request::get('tab') == 'proses') active @endif"
                                id="home-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="true">
                                Proses
                            </button>
                        </li>
                        <li class="nav-item" role="presentation" data-tab="final">
                            <button class="nav-link border @if(\Request::get('tab') == 'final') active @endif" id="final-tab" data-bs-toggle="tab" data-bs-target="#final" type="button" role="tab" aria-controls="final" aria-selected="false">
                                Final
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <input type="hidden" name="page" value="{{\Request::get('page') ? \Request::get('page') : 1}}">
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
                            {{--  <div class="p-2">
                                <label for="q">Cari</label>
                                <input type="search" name="q" id="q" placeholder="Cari disini..."
                                    class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}"
                                    style="width: 300px;">
                            </div>  --}}
                        </div>
                        <div class="tab-pane fade @if(!\Request::has('tab')) active show @endif @if(\Request::get('tab') == 'proses') active show @endif" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                            <div class="table-responsive overflow-hidden">
                                @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                                @endphp
                                <table class="table table-bordered" id="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                            @if (auth()->user()->hasRole('admin'))
                                                <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Kantor</th>
                                            @endif
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" colspan="4">Total</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Bruto</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Potongan</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Netto</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">PPH21</th>
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
                                                $total_potongan += $item->total_potongan;
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
                                                <td class="text-center">
                                                    @can('penghasilan - proses penghasilan - rincian')
                                                        <a href="#" class="btn btn-outline-warning p-1 btn-rincian"
                                                            data-batch_id="{{$item->id}}">Rincian</a>
                                                    @endcan
                                                    @can('penghasilan - proses penghasilan - payroll')
                                                        <a href="#" class="btn btn-outline-success p-1 btn-payroll"
                                                            data-batch_id="{{$item->id}}">Payroll</a>
                                                    @endcan
                                                    @if ($item->tanggal_cetak != null && $item->tanggal_upload == null)
                                                        @can('penghasilan - proses penghasilan - lampiran gaji - upload')
                                                            @if ($item->file == null)
                                                                <a class="btn btn-outline-primary p-1" href="#" id="uploadFile"  data-toggle="modal" data-target="#modalUploadfile" data-batch_id="{{ $item->id }}">Upload Lampiran Gaji</a>
                                                            @endif
                                                        @endcan
                                                    @elseif ($item->tanggal_upload != null && $item->tanggal_cetak != null)
                                                        @can('penghasilan - proses penghasilan - lampiran gaji')
                                                            <a href="#" class="btn btn-outline-primary p-1 btn-lampiran-gaji" data-id="{{$item->id}}">Lampiran Gaji</a>
                                                        @endcan
                                                    @else
                                                        @can('penghasilan - proses penghasilan - lampiran gaji')
                                                            <a href="#" class="btn btn-outline-primary p-1 btn-lampiran-gaji" data-id="{{$item->id}}">Lampiran Gaji</a>
                                                        @endcan
                                                    @endif
                                                </td>
                                                @if ($item->bruto == 0)
                                                    <td class="text-center">-</td>
                                                @else
                                                    <td class="text-right">
                                                        Rp {{number_format($item->bruto, 0, ',', '.')}}
                                                    </td>
                                                @endif
                                                @if ($item->total_potongan == 0)
                                                    <td class="text-center">-</td>
                                                @else
                                                    <td class="text-right">
                                                        Rp {{number_format($item->total_potongan, 0, ',', '.')}}
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
                                                <td class="text-center">
                                                    @if($item->status == 'proses')
                                                        @if($item->total_penyesuaian > 0)
                                                            @can('penghasilan - proses penghasilan')
                                                                <a href="#" class="btn btn-outline-warning p-1 btn-perbarui"
                                                                    data-batch_id="{{$item->id}}">Perbarui</a>
                                                            @endcan
                                                        @else
                                                            @can('penghasilan - proses penghasilan')
                                                                @if ($item->tanggal_cetak != null)
                                                                    @if ($item->file != null)
                                                                        @php
                                                                            $now = date('Y-m-d');
                                                                        @endphp
                                                                        {{--  @if ($item->tanggal_input == $now)  --}}
                                                                            <a href="#" class="btn btn-outline-success p-1 btn-final"
                                                                                data-batch_id="{{$item->id}}">Proses Final</a>
                                                                        {{--  @endif  --}}
                                                                    @endif
                                                                @endif
                                                            @endcan
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->hasRole('admin') ? 12 : 11 }}" class="text-center">Belum ada penghasilan yang diproses.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($proses_list)
                                        <tfoot>
                                            <tr>
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
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        Showing {{$start}} to {{$end}} of {{$proses_list->total()}} entries
                                    </div>
                                    <div>
                                        @if ($proses_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $proses_list->links('pagination::bootstrap-4') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if(\Request::get('tab') == 'final') active show @endif" id="final" role="tabpanel" aria-labelledby="final-tab">
                            <div class="table-responsive overflow-hidden">
                                @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                                @endphp
                                <table class="table table-bordered" id="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                            @if (auth()->user()->hasRole('admin'))
                                                <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Kantor</th>
                                            @endif
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center" colspan="4">Total</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Bruto</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Potongan</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">Netto</th>
                                            <th style="border: 1px solid #dee2e6;" class="text-center">PPH21</th>
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
                                                $total_potongan += $item->total_potongan;
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
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-outline-warning p-1 btn-rincian"
                                                        data-batch_id="{{$item->id}}">Rincian</a>
                                                    <a href="#" class="btn btn-outline-success p-1 btn-payroll"
                                                        data-batch_id="{{$item->id}}">Payroll</a>
                                                </td>
                                                @if ($item->bruto == 0)
                                                    <td class="text-center">-</td>
                                                @else
                                                    <td class="text-right">
                                                        Rp {{number_format($item->bruto, 0, ',', '.')}}
                                                    </td>
                                                @endif
                                                @if ($item->total_potongan == 0)
                                                    <td class="text-center">-</td>
                                                @else
                                                    <td class="text-right">
                                                        Rp {{number_format($item->total_potongan, 0, ',', '.')}}
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
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{auth()->user()->hasRole('admin') ? 11 : 10}}" class="text-center">Belum ada penghasilan yang telah selesai diproses.</td>
                                            </tr>
                                        @endforelse
                                        @if ($final_list)
                                        <tfoot>
                                            <tr>
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
                                            </tr>
                                        </tfoot>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        Showing {{$start}} to {{$end}} of {{$final_list->total()}} entries
                                    </div>
                                    <div>
                                        @if ($final_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $final_list->links('pagination::bootstrap-4') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form id="form-final" action="{{route('gaji_perbulan.proses_final')}}" method="post">
        <input type="hidden" name="_token" id="token">
        <input type="hidden" name="batch_id" id="batch_id">
    </form>
@endsection
