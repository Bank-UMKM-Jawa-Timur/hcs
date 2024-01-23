@extends('layouts.app-template')
@section('content')
{{-- <link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" /> --}}

    <style>
    .container {
        display: flex;
        align-items: left;
        justify-content: left;
        margin-top: 20px;
        margin-bottom: -85px;
        margin-left: -20px;
    }

    .image {
        max-width: 60px;
        max-height: 60px;
    }

    .text {
        margin-top: 10px;
        font-weight: bold;
        padding-left: 12px;
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
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <div class="text-2xl font-bold tracking-tighter">
                    Slip Jurnal
                </div>
                <div class="flex gap-3">
                    <a href="#" class="text-sm text-gray-500">Gaji</a>
                    <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                    <a href="/" class="text-sm text-gray-500 font-bold">Slip Jurnal</a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col-md-12">
                @php
                    $already_selected_value = date('y');
                    $earliest_year = 2024;
                @endphp
                <form action="{{ route('getSlip') }}" method="post" class="form-group">
                    @csrf
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
                        <div class="input-box">
                            <label for="">Kategori</label>
                            <select name="kategori" class="form-input" id="">
                                <option value="">--- Pilih Kategori ---</option>
                                <option value="1" @selected($request?->kategori == 1)>Gaji Pegawai</option>
                                <option value="2" @selected($request?->kategori == 2)>Pengganti Vitamin</option>
                                <option value="3" @selected($request?->kategori == 3)>Tunjangan Hari Raya</option>
                            </select>

                        </div>

                        <div class="input-box">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-input">
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
                        </div>
                        <div class="input-box">
                            <label for="Bulan">Bulan</label>
                            <select name="bulan" id="bulan" class="form-input">
                                <option value="-">--- Pilih Bulan ---</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option @selected($request?->bulan == $i) value="{{ $i }}">{{ getMonth($i) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                   <div class="pt-4 pb-4">
                    <button class="px-6 rounded-md flex gap-3 items-center py-2.5 font-semibold rounded-mdn transition-colors bg-theme-primary text-white hover:bg-blue-900 hover:text-white">Tampilkan</button>
                   </div>
                </form>
            </div>
        </div>

        @php
            $j = 1;
            $total_gj = 0;
            $total_penyesuaian = 0;
            $totalTj = [];
            $jmlKanan = 0;
            $jmlKiri = 0;

            function rupiah($angka)
            {
                $hasil_rupiah = number_format($angka, 0, ",", ".");
                return $hasil_rupiah;
            }
        @endphp
        @if ($data != null)
        <div class="table-wrapping my-4 shadow" id="reportPrinting">
            <div class="col-md-12">
                @if ($request->kategori == 1)
                    <div class="card-body ml-0 mr-0 mt-0 pb-5">
                        <div class="flex justify-between m-0 mt-1">
                            <div class="flex w-fit">
                                <div class="image">
                                    <img src="{{ asset('style/assets/img/logo.png') }}">
                                </div>
                                <div class="text">
                                    <p>BANK BPR JATIM<br>BANK UMKM JAWA TIMUR</p>
                                </div>
                            </div>

                            <div class="w-50">
                                <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold"><u>SLIP - JURNAL</u></p>
                                <p class="col-sm-12 text-center mt-2" style="font-size: 12px;">Tanggal: 25 Januari 2022</p>
                            </div>
                            <div class="col-sm-12 text-right">
                                <input type="button" class="btn btn-success" id="printPageButton" style="margin-bottom: 5px" value="Print" onClick="printReport()">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive overflow-hidden" style="align-content: center">
                        <table class="table text-center table-bordered" style="border: 1px solid #ddd !important;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Kode Rekening</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($data['item']); $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    @if ($data['item'][$i] == "Biaya Pegawai")
                                        <td style="text-align: left">{{ $data['item'][$i] }}</td>
                                    @else
                                        <td style="text-align: center;">{{ $data['item'][$i] }}</td>
                                    @endif
                                    <td>{{ $data['kode_rekening'][$i] }}</td>
                                    @if ($i == 0)
                                        <td class="text-end">{{ ($data[$i] != 0) ? rupiah($data[$i]) : '-' }}</td>
                                        <td class="text-end">-</td>
                                    @else
                                        <td class="text-end">-</td>
                                        <td class="text-end">{{ ($data[$i] != 0) ? rupiah($data[$i]) : '-' }}</td>
                                    @endif
                                </tr>
                                @endfor
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="3">
                                        Total
                                    </td>
                                    <td class="text-end">{{ rupiah($data[0]) }}</td>
                                    <td class="text-end">{{ rupiah($data[0]) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card-body mb-2">
                        <div class="grid grid-cols-2">
                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Mengetahui</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>FARIDA FIRDIANSYAH</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Pimpinan Cabang</p>
                                </div>
                            </div>

                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Dibuat</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>KOES RACHMAWATI</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Penyelia Umum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($request->kategori == 2)
                    <div class="card-body ml-0 mr-0 mt-0 pb-5">
                        <div class="flex justify-between m-0 mt-1">
                            <div class="flex w-fit">
                                <div class="image">
                                    <img src="{{ asset('style/assets/img/logo.png') }}">
                                </div>
                                <div class="text">
                                    <p>BANK BPR JATIM<br>BANK UMKM JAWA TIMUR</p>
                                </div>
                            </div>

                            <div class="w-50">
                                <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold"><u>SLIP - JURNAL</u></p>
                                <p class="col-sm-12 text-center mt-2" style="font-size: 12px;">Tanggal: 25 Januari 2022</p>
                            </div>
                            <div class="col-sm-12 text-right">
                                <input type="button" class="btn btn-success" id="printPageButton" style="margin-bottom: 5px" value="Print" onClick="printReport()">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive overflow-hidden" style="align-content: center">
                        <table class="table text-center table-bordered" style="border: 1px solid #ddd !important;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Kode Rekening</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $j++ }}
                                    </td>
                                    <td>Non Operasional Lainnya</td>
                                    <td>53705</td>
                                    <td>{{ ($data['tj_vitamin'] != 0) ? rupiah($data['tj_vitamin']) : '-' }}</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>
                                        {{$j++}}
                                    </td>
                                    <td>
                                        Tabungan Sikemas
                                    </td>
                                    <td>
                                        20102
                                    </td>
                                    <td>-</td>
                                    <td>{{ ($data['tj_vitamin'] != 0) ? rupiah($data['tj_vitamin']) : '-' }}</td>
                                </tr>
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="3">
                                        Total
                                    </td>
                                    <td>{{ ($data['tj_vitamin'] != 0) ? rupiah($data['tj_vitamin']) : '-' }}</td>
                                    <td>{{ ($data['tj_vitamin'] != 0) ? rupiah($data['tj_vitamin']) : '-' }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card-body mb-2">
                        <div class="grid grid-cols-2">
                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Mengetahui</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>FARIDA FIRDIANSYAH</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Pimpinan Cabang</p>
                                </div>
                            </div>

                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Dibuat</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>KOES RACHMAWATI</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Penyelia Umum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($request->kategori == 3)
                    <div class="card-body ml-0 mr-0 mt-0 pb-5">
                        <div class="flex justify-between m-0 mt-1">
                            <div class="flex w-fit">
                                <div class="image">
                                    <img src="{{ asset('style/assets/img/logo.png') }}">
                                </div>
                                <div class="text">
                                    <p>BANK BPR JATIM<br>BANK UMKM JAWA TIMUR</p>
                                </div>
                            </div>

                            <div class="w-50">
                                <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold"><u>SLIP - JURNAL</u></p>
                                <p class="col-sm-12 text-center mt-2" style="font-size: 12px;">Tanggal: 25 Januari 2022</p>
                            </div>
                            <div class="col-sm-12 text-right">
                                <input type="button" class="btn btn-success" id="printPageButton" style="margin-bottom: 5px" value="Print" onClick="printReport()">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive overflow-hidden" style="align-content: center">
                        <table class="table text-center table-bordered" style="border: 1px solid #ddd !important;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Kode Rekening</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $j++ }}
                                    </td>
                                    <td>Biaya Dibayar Dimuka - Lainnya</td>
                                    <td>18310</td>
                                    <td>{{ ($data['thr'] != 0) ? rupiah($data['thr']) : '-' }}</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>
                                        {{$j++}}
                                    </td>
                                    <td>
                                        Tabungan Sikemas
                                    </td>
                                    <td>
                                        20102
                                    </td>
                                    <td>-</td>
                                    <td>{{ ($data['thr'] != 0) ? rupiah($data['thr']) : '-' }}</td>
                                </tr>
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="3">
                                        Total
                                    </td>
                                    <td>{{ ($data['thr'] != 0) ? rupiah($data['thr']) : '-' }}</td>
                                    <td>{{ ($data['thr'] != 0) ? rupiah($data['thr']) : '-' }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card-body mb-2">
                        <div class="grid grid-cols-2">
                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Mengetahui</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>FARIDA FIRDIANSYAH</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Pimpinan Cabang</p>
                                </div>
                            </div>

                            <div class="col">
                                <div class="flex flex-col mt-1">
                                    <p class="col-sm-12 text-center" style="font-size: 14px">Dibuat</p>
                                    <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>KOES RACHMAWATI</u></p>
                                    <p class="col-sm-12 text-center mt-2" style="font-size: 13px;">Penyelia Umum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script>
        $("#table").DataTable({})

        function printReport()
        {
            var prtContent = document.getElementById("reportPrinting");
            var mywindow = window;
            var content = `
                <html>
                    <head>
                        <title></title>
                        <link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
                        <link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />
                        <link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />
                        <style>
                            .table-responsive {
                                -ms-overflow-style: none;
                                scrollbar-width: none;
                            }

                            .table-responsive::-webkit-scrollbar {
                                overflow-y: hidden;
                                overflow-x: scroll;
                            }

                            #printPageButton {
                                display: none;
                            }

                            .container {
                                display: flex;
                                align-items: left;
                                justify-content: left;
                                margin-top: 20px;
                                margin-bottom: -85px;
                                margin-left: -20px;
                            }

                            .image {
                                max-width: 60px;
                                max-height: 60px;
                            }

                            .text {
                                margin-top: 10px;
                                font-weight: bold;
                                padding-left: 12px;
                            }
                        </style>
                    </head>
                    <body>
                        ${prtContent.innerHTML}
                    </body>
                </html>`;

            mywindow.document.write(content)
            setTimeout(function () {
                mywindow.print();
            }, 1000);

            mywindow.onafterprint = function(){
                setTimeout(function () {
                    mywindow.close();
                    location.reload()
                }, 1000)
            }
            return true;
        }
    </script>
@endsection
