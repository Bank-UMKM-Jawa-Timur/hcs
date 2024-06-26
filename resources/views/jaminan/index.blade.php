@extends('layouts.app-template')
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
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Laporan JAMSOSTEK</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Laporan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Laporan JAMSOSTEK</a>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <form action="{{ route('filter-laporan') }}" method="post">
        @csrf
        <div class="card space-y-5">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 m-0">
                <div class="col-md-4">
                    <div class="input-box">
                        <label for="">Kategori {{ old('kategori') }}</label>
                        <select name="kategori" class="form-input" id="kategori" required>
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
            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
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
                    <div class="input-box">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="form-input" required>
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
                    <div class="input-box">
                        <label for="Bulan">Bulan</label>
                        <select name="bulan" id="bulan" class="form-input" required>
                            <option value="">--- Pilih Bulan ---</option>
                            @for($i = 1; $i <= 12; $i++) <option @selected($request?->bulan == $i) value="{{ $i }}">{{
                                getMonth($i) }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </div>
        </div>
    </form>
    @if ($status != null)
        <div class="table-wrapping mt-10">
            <div class="col-md-12">
                @if ($cek_data == 0)
                <h5 class="text-center align-item-center"><b>Data Ini Masih Belum Diproses ({{ getMonth($bulan) }} {{ $tahun }})</b></h5>
                @endif
                @if ($status == 1)
                    @if (count($data) > 0)
                        <div class="table-wrapping">
                            <table class="tables-stripped" id="table_export" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">Kode Kantor</th>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">Nama Kantor</th>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">Jumlah Pegawai</th>
                                        <th colspan="4" class="bg-theme-primary" style="text-align: center;">JAMSOSTEK</th>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">JP(1%)</th>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">JP(2%)</th>
                                        <th rowspan="2" class="bg-theme-primary" style="text-align: center;">Total JP</th>
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
                                            $grand_karyawan = 0;
                                            $total_jkk = 0;
                                            $total_jht = 0;
                                            $total_jkm = 0;
                                            $total_jp1 = 0;
                                            $total_jp2 = 0;
                                        @endphp
                                    @foreach ($data as $item)
                                        @php
                                            $nama_cabang = DB::table('mst_cabang')
                                                        ->where('kd_cabang', $item->kd_entitas)
                                                        ->first();
                                            $total_jkk += $item->perhitungan_jkk;
                                            $total_jht += $item->perhitungan_jht;
                                            $total_jkm += $item->perhitungan_jkm;
                                            $total_jp1 += $item->jp1;
                                            $total_jp2 += $item->jp2;
                                            $grand_karyawan += $item->total_karyawan;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->kd_entitas == '000' ? '-' : $item->kd_entitas }}</td>
                                            <td>{{ $item->kd_entitas == '000' ? 'Kantor Pusat' : $nama_cabang->nama_cabang }}</td>
                                            <td>{{ $item->total_karyawan }}</td>
                                            {{-- jamsostek --}}
                                            <td>{{number_format($item->perhitungan_jkk, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jht, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jkm, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jkk + $item->perhitungan_jht + $item->perhitungan_jkm, 0, ",", ".")}}</td>
                                            {{-- end jamsostek --}}
                                            <td>{{number_format($item->jp1, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->jp2, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->jp1 + $item->jp2, 0, ",", ".")}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="2" style="text-align: center;">
                                            Jumlah
                                        </td>
                                        <td style="text-align: center;">{{ $grand_karyawan }}</td>
                                    <td style="text-align: center;">{{ number_format($total_jkk, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jht, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jkm, 0, ",", ".") }}</td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format($total_jkk + $total_jht + $total_jkm, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jp1, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jp2, 0, ",", ".") }}</td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format($total_jp1 +
                                            $total_jp2, 0, ",", ".") }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)
                                        </td>
                                        <td style="background-color: #54B435; text-align: center;">
                                            {{ number_format($total_jkk + $total_jht + $total_jkm + $total_jp1 + $total_jp2, 0, ",", ".") }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                @elseif($status == 2)
                    @if (count($karyawan) > 0)
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
                                    @php
                                        $total_jkk = 0;
                                        $total_jht = 0;
                                        $total_jkm = 0;
                                        $total_jp1 = 0;
                                        $total_jp2 = 0;
                                    @endphp
                                    @foreach ($karyawan as $item)
                                        @php
                                            $total_jkk += $item->perhitungan_jkk;
                                            $total_jht += $item->perhitungan_jht;
                                            $total_jkm += $item->perhitungan_jkm;
                                            $total_jp1 += $item->jp1;
                                            $total_jp2 += $item->jp2;
                                        @endphp
                                        <tr>
                                            <td>{{$item->nip}}</td>
                                            <td>{{$item->nama_karyawan}}</td>
                                            {{-- jamsostek --}}
                                            <td>{{number_format($item->perhitungan_jkk, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jht, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jkm, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->perhitungan_jkk + $item->perhitungan_jht + $item->perhitungan_jkm, 0, ",", ".")}}</td>
                                            {{-- end jamsostek --}}
                                            <td>{{number_format($item->jp1, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->jp2, 0, ",", ".")}}</td>
                                            <td>{{number_format($item->jp1 + $item->jp2, 0, ",", ".")}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="font-weight: bold; text-align: center;">
                                    <tr>
                                        <td colspan="2" style="text-align: center;">Jumlah</td>
                                        <td style="text-align: center;">{{ number_format($total_jkk, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jht, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jkm, 0, ",", ".") }}</td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format($total_jkk + $total_jht + $total_jkm, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jp1, 0, ",", ".") }}</td>
                                        <td style="text-align: center;">{{ number_format($total_jp2, 0, ",", ".") }}</td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format($total_jp1 +
                                            $total_jp2, 0, ",", ".") }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)
                                        </td>
                                        <td style="background-color: #54B435; text-align: center;">
                                            {{ number_format($total_jkk + $total_jht + $total_jkm + $total_jp1 + $total_jp2, 0, ",", ".") }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
<button class="btn-scroll-to-top btn btn-primary fixed hidden bottom-5 right-5">
    To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
</button>

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
                    customize: function(xlsx, row) {
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
                <div class="input-box">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-input" id="kantor" required>
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
                            <div class="input-box">
                                <label for="Cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-input" required>
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
