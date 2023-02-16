@extends('layouts.template')
@section('content')
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
    </style>

    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Slip Jurnal</h5>
            <p class="card-title"><a href="">Gaji</a> > Slip Jurnal</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col-md-12">
                @php
                    $already_selected_value = date('y');
                    $earliest_year = 2022;
                @endphp
                <form action="{{ route('getSlip') }}" method="post" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="kategori" class="form-control" id="">
                                    <option value="">--- Pilih Kategori ---</option>
                                    <option value="1" @selected($request?->kategori == 1)>Gaji Pegawai</option>
                                    <option value="2" @selected($request?->kategori == 2)>Pengganti Vitamin</option>
                                    <option value="3" @selected($request?->kategori == 3)>Tunjangan Hari Raya</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Kantor</label>
                                <select name="kantor" id="kantor" class="form-control">
                                    <option value="">--- Pilih Kantor ---</option>
                                    <option value="pusat">Pusat</option>
                                    <option value="cabang">Cabang</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4" id="cabang_col">
                            
                        </div> --}}
                        <div class="col-md-4">
                            <label for="tahun">Tahun</label>
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">--- Pilih Tahun ---</option>
                                    @foreach (range(date('Y'), $earliest_year) as $x)
                                        <option @selected($request?->tahun == $x) value="{{ $x }}">{{ $x }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="-">--- Pilih Bulan ---</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option @selected($request?->bulan == $i) value="{{ $i }}">{{ getMonth($i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-info">Tampilkan</button>
                </form>
            </div>
        </div>

        {{-- @php
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
        @endphp --}}
        <div class="card ml-3 mr-3 mb-3 mt-4 shadow" id="reportPrinting">
            <div class="col-md-12"> 
                @if ($data != null)
                    {{-- <div class="table-responsive overflow-hidden">
                        <table class="table" id="table">
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
                                @if ($request->kategori == 1)
                                    @for ($i = 0; $i < count($data['item']); $i++)
                                        <tr>
                                            <td>
                                                {{ $j++ }}
                                            </td>
                                            <td>{{ $data['item'][$i] }}</td>
                                            <td>{{ $data['kode_rekening'][$i] }}</td>
                                            @if ($i == 0)
                                                <td>{{ rupiah($data[$i]) }}</td>
                                                <td>-</td>
                                            @else
                                                <td>-</td>
                                                <td>{{ rupiah($data[$i]) }}</td>
                                            @endif
                                        </tr>
                                    @endfor
                                @elseif($request->kategori == 2)
                                    <tr>
                                        <td>
                                            {{ $j++ }}
                                        </td>
                                        <td>Non Operasional Lainnya</td>
                                        <td>53705</td>
                                        <td>{{ rupiah($data['tj_vitamin']) }}</td>
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
                                        <td>{{ rupiah($data['tj_vitamin']) }}</td>
                                        @php
                                            $jmlKanan += $data['tj_vitamin'];
                                        @endphp
                                    </tr>
                                @elseif($request->kategori == 3)
                                    <tr>
                                        <td>{{ $j++ }}</td>
                                        <td>Biaya Dibayar Dimuka - Lainnya</td>
                                        <td>18310</td>
                                        <td>{{ rupiah($data['thr']) }}</td>
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
                                        <td>{{ rupiah($data['thr']) }}</td>
                                        @php
                                            $jmlKanan += $data['thr'];
                                        @endphp
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total</td>
                                    @if ($request->kategori == 1)
                                        <td>{{ rupiah($data[0]) }}</td>
                                        <td>{{ rupiah($data[0]) }}</td>
                                    @elseif($request->kategori == 2)
                                        <td>{{ rupiah($data['tj_vitamin']) }}</td>
                                        <td>{{ rupiah($data['tj_vitamin']) }}</td>
                                    @elseif($request->kategori == 3)
                                        <td>{{ rupiah($data['thr']) }}</td>
                                        <td>{{ rupiah($data['thr']) }}</td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div> --}}
    
                    @if ($request->kategori == 1)
                        <div class="container">
                            <div class="image">
                                <img src="{{ asset('style/assets/img/logo.png') }}">
                            </div>
                            <div class="text">
                                <p>BANK BPR JATIM<br>BANK UMKM JAWA TIMUR</p>
                            </div>
                        </div>

                        <div class="card-body ml-0 mr-0 mt-0 mb-2">
                            <div class="row m-0 mt-1">
                                <div class="col-sm-12 text-right">
                                    <input type="button" class="btn btn-success" id="printPageButton" style="margin-bottom: 5px" value="Print" onClick="printReport()">
                                </div>
                                <p class="col-sm-12 text-center" style="font-size: 18px; font-weight: bold"><u>SLIP - JURNAL</u></p>
                                <p class="col-sm-12 text-center" style="font-size: 12px; margin-top: -17px">Tanggal: 25 Januari 2022</p>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endfor
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="3">
                                            Total
                                        </td>
                                        <td>24.284.163</td>
                                        <td>24.284.163</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="card-body mb-2">
                            <div class="row">
                                <div class="col">
                                    <div class="row mt-1">
                                        <p class="col-sm-12 text-center" style="font-size: 14px">Mengetahui</p>
                                        <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>FARIDA FIRDIANSYAH</u></p>
                                        <p class="col-sm-12 text-center" style="font-size: 13px; margin-top: -17px">Pimpinan Cabang</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="row mt-1">
                                        <p class="col-sm-12 text-center" style="font-size: 14px">Dibuat</p>
                                        <p class="col-sm-12 text-center" style="font-size: 14px; font-weight: bold; margin-top: 60px"><u>KOES RACHMAWATI</u></p>
                                        <p class="col-sm-12 text-center" style="font-size: 13px; margin-top: -17px">Penyelia Umum</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
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
        $("#table").DataTable({

        })
        // $("#kantor").change(function(){
        //     var value = $(this).val()
        //     $("#cabang_col").empty()
        //     if(value == 'cabang'){
        //         $.ajax({
        //             type: 'GET',
        //             url: '/getcabang',
        //             dataType: 'JSON',
        //             success: (res) => {
        //                 $('#cabang_col').append(`
        //                     <div class="form-group">
        //                         <label for="Cabang">Cabang</label>
        //                         <select name="cabang" id="cabang" class="form-control">
        //                             <option value="">--- Pilih Cabang ---</option>
        //                         </select>
        //                     </div>
        //                 `);

        //                 $.each(res[0], function(i, item){
        //                     $("#cabang").append(`
        //                         <option value="`+ item.kd_cabang +`">`+ item.kd_cabang +` - `+ item.nama_cabang +`</option>
        //                     `)
        //                 })
        //             }
        //         });
        //     }
        // })

        function printReport()
        {
            var prtContent = document.getElementById("reportPrinting");
            var mywindow = window.open();

            mywindow.document.write('<html><head><title></title>');
            mywindow.document.write('<link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />');
            mywindow.document.write('<link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />');
            mywindow.document.write('<link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />');
            mywindow.document.write(`<style> 
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
                </style>`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(prtContent.innerHTML);
            mywindow.document.write('</body></html>');

            setTimeout(function () {
            mywindow.print();
            mywindow.close();
            }, 1000)
            return true;

            return true;
        }
    </script>
@endsection