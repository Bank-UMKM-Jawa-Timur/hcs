@extends('layouts.template')
@php
$request = isset($request) ? $request : null;
@endphp
@section('content')
    <style>
        .dataTables_wrapper .dataTables_filter{
            float: right;
        }
        .dataTables_wrapper .dataTables_length{
            float: left;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            width: 90%;
        }
    </style>

    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Laporan JAMSOSTEK</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="">Laporan JAMSOSTEK </a></p>
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
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">--- Pilih Kategori ---</option>
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
                    $earliest_year = 2022;
                @endphp
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
                <div class="col-md-12 mt-2">
                    <button class="btn btn-info" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card ml-3 mr-3 mb-3 mt-3 shadow">
        <div class="col-md-12">
            @if ($status != null)
                @php
                    function rupiah($angka)
                    {
                        $hasil_rupiah = number_format($angka, 0, ".", ",");
                        return $hasil_rupiah;
                    }
                    
                    function rupiah2($angka)
                    {
                        $hasil_rupiah = number_format($angka, 2, ".", ",");
                        return $hasil_rupiah;
                    }
                    function rupiah4($angka)
                    {
                        $hasil_rupiah = number_format($angka, 4, ".", ",");
                        return $hasil_rupiah;
                    }
                @endphp
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
                                <tr>
                                    <td>-</td>
                                    <td>Kantor Pusat</td>
                                    <td>{{ $count_pusat }}</td>
                                    <td>{{ rupiah(((0.0024 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(((0.057 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(((0.003 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah((((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) + ((0.003 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(array_sum($jp1_pusat)) }}</td>
                                    <td>{{ rupiah(array_sum($jp2_pusat)) }}</td>
                                    <td>{{ rupiah((array_sum($jp1_pusat) + array_sum($jp2_pusat))) }}</td>
                                </tr>

                                @php
                                    $total_jkk = array();
                                    $total_jht = array();
                                    $total_jkm = array();
                                    $total_jamsostek = array();

                                    $total_jp1 = array();
                                    $total_jp2 = array();
                                    $total_jp = array();

                                    array_push($total_jamsostek, (((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) + ((0.003 * $total_gaji_pusat)));
                                    array_push($total_jkk, ((0.0024 * $total_gaji_pusat)));
                                    array_push($total_jht, ((0.057 * $total_gaji_pusat)));
                                    array_push($total_jkm, ((0.003 * $total_gaji_pusat)));

                                    array_push($total_jp, (array_sum($jp1_pusat) + array_sum($jp2_pusat)));
                                    array_push($total_jp1, array_sum($jp1_pusat));
                                    array_push($total_jp2, array_sum($jp2_pusat));
                                @endphp

                                @foreach ($data_cabang as $item)
                                    @php
                                        $nama_cabang = DB::table('mst_cabang')
                                            ->where('kd_cabang', $item->kd_entitas)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->kd_entitas }}</td>
                                        <td>{{ $nama_cabang->nama_cabang }}</td>
                                        @php
                                            $jp1_cabang = array();
                                            $jp2_cabang = array();
                                            $total_gaji_cabang = array();

                                            $karyawan = DB::table('mst_karyawan')
                                                ->where('kd_entitas', $item->kd_entitas)
                                                ->get();
                                            foreach($karyawan as $i){
                                                if ($i->status_karyawan != 'Nonaktif') {
                                                    $data_gaji = DB::table('gaji_per_bulan')
                                                        ->where('nip', $i->nip)
                                                        ->where('bulan', $bulan)
                                                        ->where('tahun', $tahun)
                                                        ->first();
                                                    array_push($total_gaji_cabang, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
                                                }
                                            }
                                            foreach($total_gaji_cabang as $i){
                                                array_push($jp1_cabang, ((($i >  9077600) ?  round(9077600 * 0.01) : round($i * 0.01))));
                                                array_push($jp2_cabang, ((($i >  9077600) ?  round(9077600 * 0.02) : round($i * 0.02))));
                                            }
                                        @endphp
                                        <td>{{ count($karyawan) }}</td>
                                        <td>{{ rupiah(((0.0024 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(((0.057 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(((0.003 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah((((0.0024 * array_sum($total_gaji_cabang))) + ((0.057 * array_sum($total_gaji_cabang)))) + ((0.003 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(array_sum($jp1_cabang)) }}</td>
                                        <td>{{ rupiah(array_sum($jp2_cabang)) }}</td>
                                        <td>{{ rupiah((array_sum($jp1_cabang) + array_sum($jp2_cabang))) }}</td>

                                        @php
                                            array_push($total_jamsostek, (((0.0024 * array_sum($total_gaji_cabang))) + ((0.057 * array_sum($total_gaji_cabang)))) + ((0.003 * array_sum($total_gaji_cabang))));
                                            array_push($total_jkk, ((0.0024 * array_sum($total_gaji_cabang))));
                                            array_push($total_jht, ((0.057 * array_sum($total_gaji_cabang))));
                                            array_push($total_jkm, ((0.003 * array_sum($total_gaji_cabang))));

                                            array_push($total_jp, (array_sum($jp1_cabang) + array_sum($jp2_cabang)));
                                            array_push($total_jp1, array_sum($jp1_cabang));
                                            array_push($total_jp2, array_sum($jp2_cabang));
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        Jumlah
                                    </td>
                                    @php
                                        $total_karyawan = DB::table('mst_karyawan')->get();
                                    @endphp
                                    <td style="text-align: center;">{{ count($total_karyawan) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jkk)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jht)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jkm)) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($total_jamsostek)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jp1)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jp2)) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($total_jp)) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="9" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)</td>
                                    <td style="background-color: #54B435; text-align: center;">{{ rupiah((array_sum($total_jamsostek) + array_sum($total_jp))) }}</td>
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
                                            {{ rupiah4(($jkk[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah2(($jht[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah2(($jkm[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah2(($jkk[$i] + $jht[$i] + $jkm[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp1[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp2[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp1[$i] + $jp2[$i])) }}
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot style="font-weight: bold; text-align: center;">
                                <tr>
                                    <td colspan="2" style="text-align: center;">Jumlah</td>
                                    <td style="text-align: center;">{{ rupiah2(array_sum($jkk)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($jht)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($jkm)) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah((array_sum($jkk) + array_sum($jht) + array_sum($jkm))) }}</td>
                                    <td style="text-align: center;">{{ rupiah((array_sum($jp1))) }}</td>
                                    <td style="text-align: center;">{{ rupiah((array_sum($jp2))) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah((array_sum($jp1) + array_sum($jp2))) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)</td>
                                    <td style="background-color: #54B435; text-align: center;">{{ rupiah((array_sum($jkk) + array_sum($jht) + array_sum($jkm)) + (array_sum($jp1) + array_sum($jp2))) }}</td>
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
        var a = document.getElementById("bulan");
        var b = document.getElementById("tahun");
        var bulan = a.options[a.selectedIndex].text;
        var tahun = b.options[b.selectedIndex].text;
        
        $("#table_export").DataTable({

            dom : "Bfrtip",
            iDisplayLength: -1,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Bank UMKM Jawa Timur',
                    filename : 'Bank UMKM Jawa Timur Laporan Jamsostek',
                    message: 'Rekapitulasi Beban Asuransi\n ' + bulan + ' ' + tahun,
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Bank UMKM Jawa Timur\n Rekapitulasi Beban Asuransi ' + bulan + ' ' + tahun,
                    filename : 'Bank UMKM Jawa Timur Laporan Jamsostek',
                    text:'PDF',
                    footer: true,
                    paperSize: 'A4',
                    orientation: 'landscape',
                    customize: function (doc) {
                        var now = new Date();
						var jsDate = now.getDate()+' / '+(now.getMonth()+1)+' / '+now.getFullYear();
                        
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
                    title: 'Bank UMKM Jawa Timur Rekapitulasi Beban Asuransi ' + bulan + ' ' + tahun,
                    text:'print',
                    footer: true,
                    paperSize: 'A4',
                    customize: function (win) {
                        var last = null;
                        var current = null;
                        var bod = [];
        
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
                            .css('width', '1000px');
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
                    <select name="kantor" class="form-control" id="kantor">
                        <option value="-">--- Pilih Kantor ---</option>
                        <option ${ office == "Pusat" ? 'selected' : '' } value="Pusat">Pusat</option>
                        <option ${ office == "Cabang" ? 'selected' : '' } value="Cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function(e) {
                $('#cabang_col').empty();
                if($(this).val() != "Cabang") return;
                generateSubOffice();
            });

            function generateSubOffice() {
                $('#cabang_col').empty();
                const subOffice = '{{ $request?->cabang }}';

                $.ajax({
                    type: 'GET',
                    url: '/getcabang',
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="Cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
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
