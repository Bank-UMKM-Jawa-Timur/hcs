@extends('layouts.template')
@include('gaji_perbulan.modal.proses')
@include('gaji_perbulan.modal.perbarui')
@include('gaji_perbulan.modal.modal-upload')
@include('gaji_perbulan.script.index')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold">Proses Penghasilan Bulanan</h5>
                    <p class="card-title"><a href="">Penghasilan </a> > Proses Penghasilan Bulanan</p>
                </div>
                @can('penghasilan - proses penghasilan')
                    <div>
                        <button type="button" class="is-btn is-primary btn-proses">Proses</button>
                    </div>
                @endcan
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
            <div class="col-l-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border" id="home-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="true">
                            Proses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border" id="final-tab" data-bs-toggle="tab" data-bs-target="#final" type="button" role="tab" aria-controls="final" aria-selected="false">
                            Final
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" colspan="3">Total</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Bruto</th>
                                        <th class="text-center">Potongan</th>
                                        <th class="text-center">Netto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                                        $total_bruto = 0;
                                        $total_potongan = 0;
                                        $total_netto = 0;
                                    @endphp
                                    @forelse ($proses_list as $item)
                                        @php
                                            $total_bruto += $item->bruto;
                                            $total_potongan += $item->total_potongan;
                                            $total_netto += $item->netto;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                                            <td class="text-center">{{date('d-m-y', strtotime($item->tanggal_input))}}</td>
                                            <td class="text-center">dummy.xlsx</td>
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
                                            <td class="text-center">
                                                @if($item->status == 'proses')
                                                    @if ($item->tanggal_cetak != null)
                                                        @if ($item->file == null)
                                                            <a class="btn btn-outline-primary p-1" href="#" id="uploadFile"  data-toggle="modal" data-target="#modalUploadfile" data-batch_id="{{ $item->id }}">Upload File</a>
                                                        @endif
                                                    @else
                                                        <a class="btn btn-outline-primary p-1 btn-download-pdf" href="{{ route('cetak.penghasilanPerBulan',$item->id) }}">Download PDF</a>
                                                    @endif
                                                    @if($item->total_penyesuaian > 0)
                                                        <a href="#" class="btn btn-outline-warning p-1 btn-perbarui"
                                                            data-batch_id="{{$item->id}}">Perbarui</a>
                                                    @else
                                                        <a href="#" class="btn btn-outline-success p-1 btn-final"
                                                            data-batch_id="{{$item->id}}">Proses Final</a>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Belum ada penghasilan yang diproses.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($proses_list)
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="5">Total</th>
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
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" colspan="3">Total</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Bruto</th>
                                        <th class="text-center">Potongan</th>
                                        <th class="text-center">Netto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total_bruto = 0;
                                        $total_potongan = 0;
                                        $total_netto = 0;
                                    @endphp
                                    @forelse ($final_list as $item)
                                        @php
                                            $total_bruto += $item->bruto;
                                            $total_potongan += $item->total_potongan;
                                            $total_netto += $item->netto;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                                            <td class="text-center">{{date('d-m-y', strtotime($item->tanggal_input))}}</td>
                                            <td class="text-center">dummy.xlsx</td>
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada penghasilan yang telah selesai diproses.</td>
                                        </tr>
                                    @endforelse
                                    @if ($final_list)
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="5">Total</th>
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
                                        </tr>
                                    </tfoot>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form-final" action="{{route('gaji_perbulan.proses_final')}}" method="post">
        <input type="hidden" name="_token" id="token">
        <input type="hidden" name="batch_id" id="batch_id">
    </form>
@endsection