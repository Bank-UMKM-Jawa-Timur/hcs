@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Laporan Demosi</h5>
            <p class="card-title"><a href="#">Laporan</a> > <a href="#">Laporan Pergerakan Karir</a> > <a
                    href="{{ route('laporan-demosi.index') }}">Laporan Demosi</a></p>
        </div>

        <div class="card-body">
            <form id="form" action="" method="get">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Dari</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', Request::get('start_date')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Sampai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', Request::get('end_date')) }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 pt-4 pb-4">
                        <button class="is-btn is-primary" type="submit">Tampilkan</button>
                    </div>
                </div>
                @isset($data)
                <div class="row mt-1 px-3">
                    <div class="table-responsive">
                        <div class="d-flex justify-content-between mb-4">
                            <div class="p-2 mt-4 w-100">
                                <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                                <select name="page_length" id="page_length"
                                    class="border px-2 py-2 cursor-pointer rounded appearance-none text-center">
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
                            <div class="p-2 w-25">
                                <label for="q">Cari</label>
                                <input type="search" name="q" id="q" placeholder="Cari disini..."
                                    class="form-control p-2" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                            </div>
                        </div>
                        <table class="table" id="table_export">
                            <thead class="text-primary">
                                <th>
                                    #
                                </th>
                                <th>
                                    NIP
                                </th>
                                <th>
                                    Nama Karyawan
                                </th>
                                <th>
                                    Tanggal Demosi
                                </th>
                                <th>
                                    Jabatan Lama
                                </th>
                                <th>
                                    Jabatan Baru
                                </th>
                                <th>
                                    Kantor Lama
                                </th>
                                <th>
                                    Kantor Baru
                                </th>
                                <th>
                                    Bukti SK
                                </th>
                            </thead>
                            @php
                                $i = 1;
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                                if ($pagination) {
                                    $i = $pagination['iteration'];
                                }
                            @endphp
                            <tbody>
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($data as $item)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {{ $item->nip }}
                                    </td>
                                    <td>
                                        {{ $item->nama_karyawan }}
                                    </td>
                                    <td>
                                        <span style="display: none;">{{ date('Ymd', strtotime($item->tanggal_pengesahan)) }}</span>
                                        {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
                                    </td>
                                    <td class="text-nowrap">
                                        {{ ($item->status_jabatan_lama != null) ? $item->status_jabatan_lama.' - ' : '' }}{{
                                        $item->jabatan_lama }}
                                    </td>
                                    <td class="text-nowrap">
                                        {{ ($item->status_jabatan_baru != null) ? $item->status_jabatan_baru.' - ' : '' }}{{
                                        $item->jabatan_baru }}
                                    </td>
                                    <td>
                                        {{ $item->kantor_lama ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $item->kantor_baru ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $item->bukti_sk }}
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('components.pagination.table-info', [
                            'obj' => $data,
                            'page_length' => $pagination['page_length'],
                            'start' => $pagination['start'],
                            'end' => $pagination['end']
                        ])
                    </div>
                </div>
            </form>
            @endisset
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
    $('#page_length').on('change', function() {
            $('#form').submit()
        })
    var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;
        
        $("#table_export").DataTable({

            dom : "Bfrtip",
            iDisplayLength: -1,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
                    filename : 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
                    message: 'Rekapitulasi Beban Asuransi\n ' + start_date + ' ' + end_date,
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
                    filename : 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
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
                    title: 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
                    filename : 'Laporan Pergerakan Karir - Demosi (Dari '+start_date + ' Sampai ' + end_date+')',
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

</script>
@endsection