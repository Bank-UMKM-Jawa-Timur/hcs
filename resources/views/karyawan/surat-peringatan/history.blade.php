@extends('layouts.template')

@php
    $firstYear = $firstData?->date?->format('Y') ?? date('Y');
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
                <h5 class="card-title">Histori Surat Peringatan</h5>
                <p class="card-title"><a href="">Histori</a> > <a href="/surat-peringatan">Surat Peringatan</a></p>
            </div>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="{{ route('surat-peringatan.history') }}" method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year">Tahun</label>
                        <select name="tahun" id="year" class="form-control">
                            <option value="">-- Semua --</option>
                            @for ($year = $firstYear; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" @selected($year == $request->tahun)>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <div class="card ml-3 mr-3 mb-3 mt-3 shadow">
        <div class="col-md-12">
            <div class="table-responsive overflow-hidden pt-2">
                <table class="table text-center cell-border stripe" id="sp-table" style="width: 100%;">
                    <thead style="background-color: #CCD6A6;">
                        <tr>
                            <th class="text-center">No. SP</th>
                            <th class="text-center">Tanggal SP</th>
                            <th class="text-center">Karyawan</th>
                            <th class="text-center">Pelanggaran</th>
                            <th class="text-center">Sanksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($history as $sp)
                        <tr>
                            <td>{{ $sp->no_sp }}</td>
                            <td>{{ $sp->tanggal_sp->format('d/m/Y') }}</td>
                            <td>{{ $sp->karyawan->nama_karyawan }}</td>
                            <td>{{ $sp->pelanggaran }}</td>
                            <td>{{ $sp->sanksi }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
        $("#sp-table").DataTable({
            dom : "Bfrtip",
            pageLength: 25,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Bank UMKM Jawa Timur',
                    filename : 'Bank UMKM Jawa Timur Laporan Surat Peringatan',
                    message: 'Laporan Surat Peringatan\n ',
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Bank UMKM Jawa Timur\n Laporan Surat Peringatan ',
                    filename : 'Bank UMKM Jawa Timur Laporan Surat Peringatan',
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
                    title: 'Bank UMKM Jawa Timur Laporan Surat Peringatan ',
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
