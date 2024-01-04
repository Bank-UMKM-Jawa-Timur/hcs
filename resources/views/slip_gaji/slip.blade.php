@extends('layouts.template')
@include('vendor.select2')
@include('slip_gaji.scripts.slip')
@push('style')
    <style>
        table th {
            border: 1px solid #e3e3e3 !important;
        }
        .table-title {
            font-size: 14px;
            font-weight: 700;
        }
        </style>
    <style>
        @media print {
            .modal-header,
            .modal-footer {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title">Gaji</h5>
            <p class="card-title">Gaji > <a href="{{route('slip.index')}}">Slip Gaji</a></p>
        </div>
    </div>
    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
                        <form id="form" method="get">
                            <div class="row">
                                @if (!auth()->user()->hasRole('user'))
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Karyawan</label>
                                            <select name="nip" id="nip"
                                                class="form-control select2">
                                                <option value="0">-- Pilih Semua Karyawan --</option>
                                            </select>
                                            @error('nip')
                                                <small class="text-danger">{{ucfirst($message)}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Tahun<span class="text-danger">*</span></label>
                                        <select name="tahun" id="tahun"
                                            class="form-control" required>
                                            <option value="0">-- Pilih tahun --</option>
                                            @php
                                                $sekarang = date('Y');
                                                $awal = $sekarang - 5;
                                                $akhir = $sekarang + 5;
                                            @endphp
                                            @for($i=$awal;$i<=$akhir;$i++)
                                                <option value="{{$i}}" @if(\Request::get('tahun') == $i) selected @endif>{{$i}}</option>
                                            @endfor
                                        </select>
                                        @error('tahun')
                                            <small class="text-danger">{{ucfirst($message)}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end my-3">
                                <input type="submit" value="Tampilkan" class="is-btn is-primary">
                            </div>
                            @if ((!auth()->user()->hasRole('user') && \Request::has('nip') && \Request::has('tahun')) || (auth()->user()->hasRole('user') && \Request::has('tahun')))
                                <h5>Slip Gaji {{auth()->user()->nama_karyawan}} Tahun {{\Request::get('tahun')}}.</h5>
                                <div class="table-responsive">
                                    @include('slip_gaji.tables.slip', ['data' => $data])
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL  --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rincian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between" id="print-slip">
                    <div class="d-flex flex-row">
                        <div class="img-logo">
                            <img src="{{ asset('style/assets/img/logo.png') }}" width="100px" class="img-fluid" style="margin-left: 1rem">
                        </div>
                        <div class="pl-2" style="margin-left: 2rem;">
                            <h4 class="text-bold my-0">SLIP GAJI PEGAWAI</h4>
                            <h6 class="text-bold mt-2 mb-0 periode">Periode</h6>
                            <h6 class="text-bold mt-2 mb-0">Bank BPR Jatim</h6>
                        </div>
                    </div>
                    <div class="p-2" id="">
                        <a href="" class="is-btn is-primary" id="print-gaji">Cetak Slip Gaji</a>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="row">
                        <div class="col-lg-12 mt-3">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">NIP</td>
                                    <td>:</td>
                                    <td id="data-nip"></td>
                                    <td class="fw-bold">Jabatan</td>
                                    <td>:</td>
                                    <td id="data-jabatan"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Karyawan</td>
                                    <td>:</td>
                                    <td id="nama"></td>
                                    <td class="fw-bold">Tanggal Bergabung</td>
                                    <td>:</td>
                                    <td id="tanggal-bergabung"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No Rekening</td>
                                    <td>:</td>
                                    <td id="no_rekening"></td>
                                    <td class="fw-bold">Lama Kerja</td>
                                    <td>:</td>
                                    <td id="lama-kerja"></td>
                                </tr>
                            </table>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 m-0">
                            <table class="table table-bordered m-0" style=" border:1px solid #e3e3e3" id="table-tunjangan">
                                <thead style="background-color: #da271f !important; color: white !important;">
                                    <tr>
                                        <th class="text-center px-3">Pendapatan</th>
                                        <th class="text-center px-3">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot id="table-tunjangan-total">

                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered m-0" style="border:1px solid #e3e3e3" id="table-potongan">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="text-center px-3">Potongan</th>
                                        <th class="text-center px-3">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot id="table-total-potongan">

                                </tfoot>
                            </table>
                        </div>
                        <br>
                        <div class="col-md-12 mt-3">
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-total-diterima">
                                <thead>

                                </thead>

                            </table>
                        </div>
                    </div>
                    <div class="row mt-4" id="footer-1">
                        <div class="col">
                            *) Keterangan: Pajak PPh 21 ditanggung perusahaan.
                        </div>
                    </div>
                    <div class="row mt-2 d-none" id="footer-2">
                        <div class="col">
                            Dicetak dengan <b>{{env('APP_NAME')}}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection