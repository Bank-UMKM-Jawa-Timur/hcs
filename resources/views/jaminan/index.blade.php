@extends('layouts.template')
@php
$request = isset($request) ? $request : null;
@endphp
@section('content')
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        width: 90%;
    }
</style>

<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title font-weight-bold">Laporan JAMSOSTEK</h5>
            <p class="card-title"><a href="">Laporan </a> > <a href="{{ route('get-jamsostek') }}">Laporan JAMSOSTEK</a>
            </p>
        </div>
    </div>
</div>

<div class="card-body">
    <form action="{{ route('filter-laporan') }}" method="post">
        @csrf
        <div class="row m-0">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Kategori {{ old('kategori') }}</label>
                    <select name="kategori" class="form-control" id="kategori" required>
                        <option value="">--- Pilih Kategori ---</option>
                        <option @selected($request?->kategori == 1) value="1">Rekap Keseluruhan</option>
                        <option @selected($request?->kategori == 2) value="2">Rekap Kantor / Cabang</option>
                    </select>
                </div>
            </div>

            <div id="kantor_col" class="col-md-4">
            </div>
            <div id="cabang_col" class="col-md-4">
            </div>
        </div>
        <div class="row m-0">
            @php
            $already_selected_value = date('y');
            $earliest_year = 2024;

            if($status != null){
            $cek_data = DB::table('gaji_per_bulan')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->count('*');
            }
            @endphp
            <div class="col-md-4">
                <label for="tahun">Tahun</label>
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control" required>
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
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="Bulan">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        <option value="">--- Pilih Bulan ---</option>
                        @for($i = 1; $i <= 12; $i++) <option @selected($request?->bulan == $i) value="{{ $i }}">{{
                            getMonth($i) }}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <button class="is-btn is-primary" type="submit">Tampilkan</button>
            </div>
        </div>
    </form>
</div>
<button class="btn-scroll-to-top d-none">
    To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
</button>
<div class="card ml-3 mr-3 mb-3 mt-3 shadow">
    <div class="col-md-12">
        @if ($status != null)
        @if ($cek_data == 0)
        <h5 class="text-center align-item-center"><b>Data Ini Masih Belum Diproses ({{ getMonth($bulan) }} {{ $tahun
                }})</b></h5>
        @endif
        @if ($status == 1)
            <div class="table-responsive overflow-hidden pt-2">
                <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Kode Kantor</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Nama Kantor</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Jumlah Pegawai</th>
                            <th colspan="4" style="background-color: #CCD6A6; text-align: center;">JAMSOSTEK</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">JP(1%)</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">JP(2%)</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Total JP</th>
                        </tr>
                        <tr style="background-color: #DAE2B6">
                            <th style="text-align: center;">JKK</th>
                            <th style="text-align: center;">JHT</th>
                            <th style="text-align: center;">JKM</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_karyawan = $count_pusat;
                        @endphp
                        <tr>
                            <td>-</td>
                            <td>Kantor Pusat</td>
                            <td>{{ $count_pusat }}</td>
                            <td>{{ number_format(((0.0024 * $total_gaji_pusat)), 0, ",", ".") }}</td>
                            <td>{{ number_format(((0.057 * $total_gaji_pusat)), 0, ",", ".") }}</td>
                            <td>{{ number_format(((0.003 * $total_gaji_pusat)), 0, ",", ".") }}</td>
                            <td>{{ number_format((((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) + ((0.003 *
                                $total_gaji_pusat)), 0, ",", ".") }}</td>
                            <td>{{ number_format(array_sum($jp1_pusat), 0, ",", ".") }}</td>
                            <td>{{ number_format(array_sum($jp2_pusat), 0, ",", ".") }}</td>
                            <td>{{ number_format((array_sum($jp1_pusat) + array_sum($jp2_pusat)), 0, ",", ".") }}</td>
                        </tr>

                        @php
                            $total_jkk = array();
                            $total_jht = array();
                            $total_jkm = array();
                            $total_jamsostek = array();

                            $total_jp1 = array();
                            $total_jp2 = array();
                            $total_jp = array();

                            array_push($total_jamsostek, (((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) +
                            ((0.003 * $total_gaji_pusat)));
                            array_push($total_jkk, ((0.0024 * $total_gaji_pusat)));
                            array_push($total_jht, ((0.057 * $total_gaji_pusat)));
                            array_push($total_jkm, ((0.003 * $total_gaji_pusat)));

                            array_push($total_jp, (array_sum($jp1_pusat) + array_sum($jp2_pusat)));
                            array_push($total_jp1, array_sum($jp1_pusat));
                            array_push($total_jp2, array_sum($jp2_pusat));
                        @endphp

                        @foreach ($data_cabang as $item)
                            @php
                                $total_karyawan += count($item->karyawan);
                            @endphp
                            <tr>
                                <td>{{ $item->kd_entitas }}</td>
                                <td>{{ $item->nama_cabang->nama_cabang }}</td>
                                <td>{{ count($item->karyawan) }}</td>
                                <td>{{ number_format(((0.0024 * array_sum($item->total_gaji_cabang))), 0, ",", ".") }}</td>
                                <td>{{ number_format(((0.057 * array_sum($item->total_gaji_cabang))), 0, ",", ".") }}</td>
                                <td>{{ number_format(((0.003 * array_sum($item->total_gaji_cabang))), 0, ",", ".") }}</td>
                                <td>{{ number_format((((0.0024 * array_sum($item->total_gaji_cabang))) + ((0.057 *
                                    array_sum($item->total_gaji_cabang)))) + ((0.003 * array_sum($item->total_gaji_cabang))), 0, ",", ".") }}</td>
                                <td>{{ number_format(array_sum($item->jp1_cabang), 0, ",", ".") }}</td>
                                <td>{{ number_format(array_sum($item->jp2_cabang), 0, ",", ".") }}</td>
                                <td>{{ number_format((array_sum($item->jp1_cabang) + array_sum($item->jp2_cabang)), 0, ",", ".") }}</td>
                                @php
                                    array_push($total_jamsostek, (((0.0024 * array_sum($item->total_gaji_cabang))) + ((0.057 *
                                    array_sum($item->total_gaji_cabang)))) + ((0.003 * array_sum($item->total_gaji_cabang))));
                                    array_push($total_jkk, ((0.0024 * array_sum($item->total_gaji_cabang))));
                                    array_push($total_jht, ((0.057 * array_sum($item->total_gaji_cabang))));
                                    array_push($total_jkm, ((0.003 * array_sum($item->total_gaji_cabang))));

                                    array_push($total_jp, (array_sum($item->jp1_cabang) + array_sum($item->jp2_cabang)));
                                    array_push($total_jp1, array_sum($item->jp1_cabang));
                                    array_push($total_jp2, array_sum($item->jp2_cabang));
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="font-weight: bold">
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                Jumlah
                            </td>
                            <td style="text-align: center;">{{ $total_karyawan }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($total_jkk), 0, ",", ".") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($total_jht), 0, ",", ".") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($total_jkm), 0, ",", ".") }}</td>
                            <td style="background-color: #FED049; text-align: center;">{{
                                number_format(array_sum($total_jamsostek), 0, ",", ".") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($total_jp1), 0, ",", ".") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($total_jp2), 0, ",", ".") }}</td>
                            <td style="background-color: #FED049; text-align: center;">{{ number_format(array_sum($total_jp), 0, ",", ".") }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)
                            </td>
                            <td style="background-color: #54B435; text-align: center;">{{
                                number_format((array_sum($total_jamsostek) + array_sum($total_jp)), 0, ".", ",") }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @elseif($status == 2)
            <div class="table-responsive overflow-hidden pt-2">
                <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align:center;">NIP</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align:center;">Nama Karyawan</th>
                            <th colspan="4" style="background-color: #CCD6A6; text-align:center;">JAMSOSTEK</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align:center;">JP(1%)</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align:center;">JP(2%)</th>
                            <th rowspan="2" style="background-color: #CCD6A6; text-align:center;">Total JP</th>
                        </tr>
                        <tr style="background-color: #DAE2B6">
                            <th style="text-align: center;">JKK</th>
                            <th style="text-align: center;">JHT</th>
                            <th style="text-align: center;">JKM</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($karyawan); $i++)
                            <tr>
                                <td>
                                    {{ $karyawan[$i]->nip }}
                                </td>
                                <td>
                                    {{ $karyawan[$i]->nama_karyawan }}
                                </td>
                                <td>
                                    {{ $i >= 261 ? 0 : number_format(isset($jkk[$i]) ? $jkk[$i] : 0 , 4, ".", ",") }}
                                </td>
                                <td>
                                    {{  $i >= 261 ? 0 : number_format(isset($jht[$i]) ? $jht[$i] : 0, 2, ".", ",") }}
                                </td>
                                <td>
                                    {{  $i >= 261 ? 0 : number_format(isset($jkm[$i]) ? $jkm[$i] : 0, 2, ".", ",") }}
                                </td>
                                <td>
                                    @php
                                        if (!isset($jkk[$i])) {
                                            $jkk[$i] = 0;
                                        }
                                        if (!isset($jht[$i])) {
                                            $jht[$i] = 0;
                                        }
                                        if (!isset($jkm[$i])) {
                                            $jkm[$i] = 0;
                                        }
                                    @endphp
                                    {{  $i >= 261 ? 0 : number_format(($jkk[$i] + $jht[$i] + $jkm[$i]), 2, ".", ",") }}
                                </td>
                                <td>
                                    {{  $i >= 261 ? 0 : number_format(isset($jp1[$i]) ? $jp1[$i] : 0, 0, ".", ",") }}
                                </td>
                                <td>
                                    {{  $i >= 261 ? 0 : number_format(isset($jp2[$i]) ? $jp2[$i] : 0, 0, ".", ",") }}
                                </td>
                                <td>
                                    @php
                                        if (!isset($jp1[$i])) {
                                            $jp1[$i] = 0;
                                        }
                                        if (!isset($jp2[$i])) {
                                            $jp2[$i] = 0;
                                        }
                                    @endphp
                                    {{  $i >= 261 ? 0 : number_format(($jp1[$i] + $jp2[$i]), 0, ".", ",") }}
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot style="font-weight: bold; text-align: center;">
                        <tr>
                            <td colspan="2" style="text-align: center;">Jumlah</td>
                            <td style="text-align: center;">{{ number_format(array_sum($jkk), 2, ".", ",") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($jht), 0, ".", ",") }}</td>
                            <td style="text-align: center;">{{ number_format(array_sum($jkm), 0, ".", ",") }}</td>
                            <td style="background-color: #FED049; text-align: center;">{{ number_format((array_sum($jkk) +
                                array_sum($jht) + array_sum($jkm)), 0, ".", ",") }}</td>
                            <td style="text-align: center;">{{ number_format((array_sum($jp1)), 0, ".", ",") }}</td>
                            <td style="text-align: center;">{{ number_format((array_sum($jp2)), 0, ".", ",") }}</td>
                            <td style="background-color: #FED049; text-align: center;">{{ number_format((array_sum($jp1) +
                                array_sum($jp2)), 0, ".", ",") }}</td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)
                            </td>
                            <td style="background-color: #54B435; text-align: center;">{{ number_format((array_sum($jkk) +
                                array_sum($jht) + array_sum($jkm)) + (array_sum($jp1) + array_sum($jp2)), 0, ".", ",") }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @endif
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
    var kat = document.getElementById("kategori");
        var a = document.getElementById("bulan");
        var b = document.getElementById("tahun");
        var bulan = a.options[a.selectedIndex].text;
        var tahun = b.options[b.selectedIndex].text;
        var category = kat.options[kat.selectedIndex].text;
        
        $("#table_export").DataTable({
            dom : "Bfrtip",
            iDisplayLength: -1,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        if (selectedValueKantor === null || selectedValueKantor === undefined) {
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - ' + bulan + ' ' + tahun;
                        } else {
                            if(selectedValueCabang === null || selectedValueCabang === undefined){
                                return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' - ' + bulan + ' ' + tahun;
                            }
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' '+selectedValueCabang+' - ' + bulan + ' ' + tahun;
                        }
                    },
                    filename : function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        if (selectedValueKantor === null || selectedValueKantor === undefined) {
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - ' + bulan + ' ' + tahun;
                        } else {
                            if(selectedValueCabang === null || selectedValueCabang === undefined){
                                return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' - ' + bulan + ' ' + tahun;
                            }
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' '+selectedValueCabang+' - ' + bulan + ' ' + tahun;
                        }
                    },
                    message: 'Rekapitulasi Beban Asuransi\n kategori - '+category+' - ' + bulan + ' ' + tahun,
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Bank UMKM Jawa Timur\n Laporan Jamsostek Kategori - '+category+' - ' + bulan + ' ' + tahun,
                    filename : function() {
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();
                        if (selectedValueKantor === null || selectedValueKantor === undefined) {
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - ' + bulan + ' ' + tahun;
                        } else {
                            if(selectedValueCabang === null || selectedValueCabang === undefined){
                                return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' - ' + bulan + ' ' + tahun;
                            }
                            return 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' '+selectedValueCabang+' - ' + bulan + ' ' + tahun;
                        }
                    },
                    text:'PDF',
                    footer: true,
                    paperSize: 'A4',
                    orientation: 'landscape',
                    customize: function (doc) {
                        var now = new Date();
						var jsDate = now.getDate()+' / '+(now.getMonth()+1)+' / '+now.getFullYear();

                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        if (selectedValueKantor === null || selectedValueKantor === undefined) {
                            doc.content[0].text = ' Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - ' + bulan + ' ' + tahun;
                        } else {
                            doc.content[0].text = ' Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' '+selectedValueCabang+' - ' + bulan + ' ' + tahun;
                        }
                        
                        doc.styles.tableHeader.fontSize = 10; 
                        doc.defaultStyle.fontSize = 9;
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';
                        
                        doc.content[1].margin = [0, 0, 0, 0];
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                        doc['footer']=(function(page, pages) {
							return {
								columns: [
									{
										alignment: 'left',
										text: ['Created on: ', { text: jsDate.toString() }]
									},
									{
										alignment: 'right',
										text: ['Page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
									}
								],
								margin: 20
							}
						});

                    }
                },
                {
                    extend: 'print',
                    title: '',
                    text:'print',
                    footer: true,
                    paperSize: 'A4',
                    customize: function (win) {
                        var last = null;
                        var current = null;
                        var bod = [];

                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        var header = document.createElement('h1');
                        if (selectedValueKantor === null || selectedValueKantor === undefined) {
                            header.textContent = ' Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - ' + bulan + ' ' + tahun;
                        } else {
                            if(selectedValueCabang === null || selectedValueCabang === undefined){
                                header.textContent = 'Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' - ' + bulan + ' ' + tahun;
                            }
                            header.textContent = ' Bank UMKM Jawa Timur Laporan Jamsostek kategori - ' + category + ' - '+selectedValueKantor+' '+selectedValueCabang+' - ' + bulan + ' ' + tahun;
                        }
                        win.document.body.insertBefore(header, win.document.body.firstChild);

                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');
        
                        style.type = 'text/css';
                        style.media = 'print';
        
                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(win.document.createTextNode(css));
                        }
        
                        head.appendChild(style);

                        $(win.document.body).find('h1')
                            .css('text-align', 'center')
                            .css( 'font-size', '16pt' )
                            .css('margin-top', '20px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10pt')
                            .css('width', '1000px')
                            .css('border', '#bbbbbb solid 1px');
                        $(win.document.body).find('tr:nth-child(odd) th').each(function(index){
                            $(this).css('text-align','center');
                        });
                    }
                }
            ]
        });
        
        $(".buttons-excel").attr("class","btn btn-success mb-2");
        $(".buttons-pdf").attr("class","btn btn-success mb-2");
        $(".buttons-print").attr("class","btn btn-success mb-2");

        $("#clear").click(function(e){
            $("#row-baru").empty()
        })

        $('#kategori').change(function(e) {
            const value = $(this).val();
            $('#kantor_col').empty();
            $('#cabang_col').empty();

            if(value == 2) generateOffice();
        });

        function generateOffice() {
            const office = '{{ $request?->kantor }}';
            $('#kantor_col').append(`
                <div class="form-group">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-control" id="kantor" required>
                        <option value="">--- Pilih Kantor ---</option>
                        <option ${ office == "Pusat" ? 'selected' : '' } value="Pusat">Pusat</option>
                        <option ${ office == "Cabang" ? 'selected' : '' } value="Cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function(e) {
                var selectedValue = $(this).val();
                console.log("Selected Value: " + selectedValue);
                tes = selectedValue;
                $('#cabang_col').empty();
                if($(this).val() != "Cabang") return;
                generateSubOffice();
            });

            function generateSubOffice() {
                $('#cabang_col').empty();
                const subOffice = '{{ $request?->cabang }}';

                $.ajax({
                    type: 'GET',
                    url: "{{ route('get_cabang') }}",
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="Cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control" required>
                                    <option value="">--- Pilih Cabang ---</option>
                                </select>
                            </div>
                        `);

                        $.each(res[0], (i, item) => {
                            const kd_cabang = item.kd_cabang;
                            $('#cabang').append(`<option ${subOffice == kd_cabang ? 'selected' : ''} value="${kd_cabang}">${item.kd_cabang} - ${item.nama_cabang}</option>`);
                        });
                    }
                });
            }
        }

        $('#kategori').trigger('change');
        $('#kantor').trigger('change');
</script>
@endsection