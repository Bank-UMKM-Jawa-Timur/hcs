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
                <h5 class="card-title">Pengklasifikasian Data</h5>
                <p class="card-title"><a href="/">Dashboard</a> > <a href="/karyawan">Karyawan</a> > Pengklasifikasian Data</p>
            </div>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="{{ route('klasifikasi-data') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kategori {{ old('kategori') }}</label>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">--- Pilih Kategori ---</option>
                            <option @selected($request?->kategori == 1) value="1">Rekap Divisi</option>
                            <option @selected($request?->kategori == 2) value="2">Rekap Sub Divisi</option>
                            <option @selected($request?->kategori == 3) value="3">Rekap Bagian</option>
                            <option @selected($request?->kategori == 4) value="4">Rekap Kantor</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="kantor_col" class="col-md-4">
                </div>
                
                <div id="cabang_col" class="col-md-4">
                </div>
    
                <div id="divisi_col" class="col-md-4">
                </div>
    
                <div id="subDivisi_col" class="col-md-4">
                </div>
    
                <div id="bagian_col" class="col-md-4">
                </div>

                <div class="col-md-12">
                    <button class="btn btn-info" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card ml-3 mr-3 mb-3 mt-3 shadow">
        <div class="col-md-12">
            @if ($status != null)
                @if ($status == 1)                    
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIP</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIK</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Nama Karyawan</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Alamat Sekarang</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Tanggal Pengangkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $item)
                                    <tr>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ ($item->alamat_sek != null) ? $item->alamat_sek : $item->alamat_ktp }}</td>
                                        <td>{{ ($item->tanggal_pengangkat != null) ? $item->tanggal_pengangkat : $item->tgl_mulai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($status == 2)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIP</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIK</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Nama Karyawan</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Alamat Sekarang</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Tanggal Pengangkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $item)
                                    <tr>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ ($item->alamat_sek != null) ? $item->alamat_sek : $item->alamat_ktp }}</td>
                                        <td>{{ ($item->tanggal_pengangkat != null) ? $item->tanggal_pengangkat : $item->tgl_mulai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($status == 3) 
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIP</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIK</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Nama Karyawan</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Alamat Sekarang</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Tanggal Pengangkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $item)
                                    <tr>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ ($item->alamat_sek != null) ? $item->alamat_sek : $item->alamat_ktp }}</td>
                                        <td>{{ ($item->tanggal_pengangkat != null) ? $item->tanggal_pengangkat : $item->tgl_mulai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($status == 4)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIP</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">NIK</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Nama Karyawan</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Alamat Sekarang</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Tanggal Pengangkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $item)
                                    <tr>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ ($item->alamat_sek != null) ? $item->alamat_sek : $item->alamat_ktp }}</td>
                                        <td>{{ ($item->tanggal_pengangkat != null) ? $item->tanggal_pengangkat : $item->tgl_mulai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
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
        $("#table_export").DataTable({
            dom : "Bfrtip",
            pageLength: 25,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Bank UMKM Jawa Timur',
                    filename : 'Bank UMKM Jawa Timur Klasifikasi Data Karyawan',
                    message: 'Klasifikasi Data Karyawan\n ',
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Bank UMKM Jawa Timur\n Klasifikasi Data Karyawan ',
                    filename : 'Bank UMKM Jawa Timur Klasifikasi Data Karyawan',
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
                    title: 'Bank UMKM Jawa Timur Klasifikasi Data Karyawan ',
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

        $('#kategori').change(function(e) {
            const value = $(this).val();
            $('#kantor_col').empty();
            $('#cabang_col').empty();
            $('#divisi_col').empty();
            $('#subDivisi_col').empty();
            $('#bagian_col').empty();

            if (value == 1) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 2) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 3) {
                generateBagian();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').removeAttr("disabled", "disabled");
                $("#bagian_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 4) {
                generateOffice();

                $('#kantor').removeAttr("disabled", "disabled");
                $("#kantor_col").show();

                $('#cabang').removeAttr("disabled", "disabled");
                $("#cabang_col").show();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();
            } else {
                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            }
        });

        function generateDivision() {
            const division = '{{ $request?->divisi }}';
            $.ajax({
                type: 'GET',
                url: '/getdivisi',
                dataType: 'JSON',
                success: (res) => {
                    $('#divisi_col').empty();
                    $('#divisi_col').append(`
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="divisi" class="form-control">
                                <option value="">--- Pilih Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $.each(res, (i, item) => {
                        const kd_divisi = item.kd_divisi;
                        $('#divisi').append(`<option ${division == kd_divisi ? 'selected' : ''} value="${kd_divisi}">${item.kd_divisi} - ${item.nama_divisi}</option>`);
                    });

                    $('#subDivisi_col').empty();
                    $('#subDivisi_col').append(`
                        <div class="form-group">
                            <label for="subDivisi">Sub Divisi</label>
                            <select name="subDivisi" id="subDivisi" class="form-control">
                                <option value="">--- Pilih Sub Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $('#divisi').change(function(e) {
                        var divisi = $(this).val();
        
                        if (divisi) {
                            const subDivision = '{{ $request?->subDivisi }}';
        
                            $.ajax({
                                type: 'GET',
                                url: '/getsubdivisi?divisiID='+divisi,
                                dataType: 'JSON',
                                success: (res) => {
                                    $('#subDivisi').empty();
                                    $('#subDivisi').append('<option value="">--- Pilih Sub Divisi ---</option>');
        
                                    $.each(res, (i, item) => {
                                        const kd_subDivisi = item.kd_subdiv;
                                        $('#subDivisi').append(`<option ${subDivision == kd_subDivisi ? 'selected' : ''} value="${kd_subDivisi}">${item.kd_subdiv} - ${item.nama_subdivisi}</option>`);
                                    });
                                }
                            });
                        }
                    });
                }

            });
        }

        function generateBagian() {
            const bagian = '{{ $request?->bagian}}';
            $('#bagian_col').append(`
                <div class="form-group">
                    <label for="bagian">Bagian</label>
                    <select name="bagian" id="bagian" class="form-control">
                        <option value="-">--- Pilih Bagian ---</option>
                        <option ${ bagian == "Penyelia" ? 'selected' : '' } value="Penyelia">Penyelia</option>
                        <option ${ bagian == "Staff" ? 'selected' : '' } value="Staff">Staff</option>
                        <option ${ bagian == "IKJP" ? 'selected' : '' } value="IKJP">IKJP</option>
                    </select>
                </div>
            `);
        }

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
                                <label for="cabang">Cabang</label>
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
        $('#divisi').trigger('change');
        $('#subDivisi').trigger('change');
        $('#bagian').trigger('change');
    </script>
@endsection