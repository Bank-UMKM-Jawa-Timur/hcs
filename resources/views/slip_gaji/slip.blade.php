@extends('layouts.app-template')
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
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .table td,
        .table th {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .table-sm td,
        .table-sm th {
            padding: 0.3rem;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px;
        }
        .table-borderless tbody + tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0;
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
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <div class="text-2xl font-bold tracking-tighter">
                    Gaji
                </div>
                <div class="flex gap-3">
                    <a href="#" class="text-sm text-gray-500">Gaji</a>
                    <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                    <a href="{{ route('slip.index') }}" class="text-sm text-gray-500 font-bold">Slip Gaji</a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="table-responsive overflow-hidden content-center">
                <form id="form" method="get">
                    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
                        @if (!auth()->user()->hasRole('user'))
                            <div class="input-box">
                                <div class="form-group">
                                    <label for="">Karyawan</label>
                                    @if ($role_cabang == 1)
                                        <select class="form-input select2" name="nip" id="nip_per_cabang">
                                            <option value="0">-- Pilih Semua Karyawan --</option>
                                            @foreach ( $data_karyawan as $item)
                                                <option {{Request()->nip == $item->nip ? 'selected' : ''}} value="{{$item->nip}}">{{$item->nip}} - {{$item->nama_karyawan}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="nip" id="nip"
                                            class="form-input select2">
                                            <option value="0">-- Pilih Semua Karyawan --</option>
                                        </select>
                                        @error('nip')
                                            <small class="text-red-500">{{ucfirst($message)}}</small>
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="input-box">
                            <div class="form-group">
                                <label for="">Tahun<span class="text-red-500">*</span></label>
                                <select name="tahun" id="tahun"
                                    class="form-input" required>
                                    <option value="">Pilih Tahun</option>
                                    @php
                                        $earliest = 2024;
                                        $tahunSaatIni = date('Y');
                                        $awal = $tahunSaatIni - 5;
                                        $akhir = $tahunSaatIni + 5;
                                    @endphp

                                    @for ($tahun = $earliest; $tahun <= $akhir; $tahun++)
                                        <option {{ Request()->tahun == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                                            {{ $tahun }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <small class="text-red-300">{{ucfirst($message)}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end my-3">
                        <input type="submit" value="Tampilkan" class="btn btn-primary is-btn is-primary">
                    </div>
                    @if ((!auth()->user()->hasRole('user') && \Request::has('nip') && \Request::has('tahun')) || (auth()->user()->hasRole('user') && \Request::has('tahun')))
                        <h5 class="font-bold mb-3">Slip Gaji {{auth()->user()->nama_karyawan}} Tahun {{\Request::get('tahun')}}.</h5>
                        <div class="table-responsive">
                            @include('slip_gaji.tables.slip', ['data' => $data])
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    {{-- MODAL  --}}
    <div class="modal-layout no-backdrop-click hidden" id="exampleModal" data-modal-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="relative p-4 w-full max-w-4xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Rincian
                    </h3>
                    <button type="button" class="close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex justify-between" id="print-slip">
                        <div class="flex flex-row">
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
                            <a href="" class="btn btn-primary is-btn is-primary" id="print-gaji">Cetak Slip Gaji</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="">
                            <div class="col-lg-12 mt-3">
                                <table class="tables text-left table-borderless">
                                    <tr>
                                        <td class="fw-bold text-start">NIP</td>
                                        <td>:</td>
                                        <td id="data-nip"></td>
                                        <td class="fw-bold text-start">Jabatan</td>
                                        <td>:</td>
                                        <td id="data-jabatan">{{ array_key_exists(0, $jabatan->toArray()) ? $jabatan[0]->display_jabatan : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start">Nama Karyawan</td>
                                        <td>:</td>
                                        <td id="nama"></td>
                                        <td class="fw-bold text-start">Tanggal Bergabung</td>
                                        <td>:</td>
                                        <td id="tanggal-bergabung"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start">No Rekening</td>
                                        <td>:</td>
                                        <td id="no_rekening"></td>
                                        <td class="fw-bold text-start">Lama Kerja</td>
                                        <td>:</td>
                                        <td id="lama-kerja"></td>
                                    </tr>
                                </table>
                                <hr>
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-start">
                            <div class="col-lg-6 m-0">
                                <table class="tables-slip table-bordered m-0" id="table-tunjangan">
                                    <thead class="rounded-lg">
                                        <tr class="rounded-lg">
                                            <th class="text-center px-3 bg-red">Pendapatan</th>
                                            <th class="text-center px-3 bg-red">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot id="table-tunjangan-total">

                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="tables-slip text-left table-bordered m-0" style="border:1px solid #e3e3e3" id="table-potongan">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center px-3 bg-red">Potongan</th>
                                            <th class="text-center px-3 bg-red">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot id="table-total-potongan">

                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-span-2">
                                <table class="tables text-left table-borderless m-0" style="border:1px solid #e3e3e3" id="table-total-diterima">
                                    <thead>

                                    </thead>

                                </table>
                            </div>
                        </div>
                        <div class="row mt-4" id="footer-1">
                            <div class="col">
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
