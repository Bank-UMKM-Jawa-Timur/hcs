@extends('layouts.app-template')
@section('content')
    <div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title">Penonaktifan Karyawan</h5>
            <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > <a href="">Pergerakan
                    Karir</a> > Penonaktifan Karyawan</p>
        </div>
        <div class="card-header row mt-3 mr-8 pr-5">
            @if (auth()->user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan'))
                <a class="mb-3" href="{{ route('penonaktifan.create') }}">
                    <button class="is-btn is-primary">tambah penonaktifan</button>
                </a>
            @endif
        </div>
    </div>

    <div class="card-body p-3">
        <div class="col">
            <form id="form" method="get">
                <div class="row">
                    <div class="col-lg-12">
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
                        <div class="table-responsive overflow-hidden content-center">
                            <table class="table whitespace-nowrap" id="table" style="width: 100%">
                                <thead class="text-primary">
                                    <th>No</th>
                                    <th>
                                        NIP
                                    </th>
                                    <th>
                                        NIK
                                    </th>
                                    <th>
                                        Nama karyawan
                                    </th>
                                    <th>
                                        Kantor Terakhir
                                    </th>
                                    <th>
                                        Jabatan Terakhir
                                    </th>
                                    <th style="text-align: center">Kategori <br>Penonaktifan</th>
                                    <th style="text-align: center">Tanggal Penonaktifan</th>
                                </thead>
                                <tbody>
                                    @php
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                        $start = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                                        $end = $page == 1 ? $page_length : $start + $page_length - 1;
                                        $i = $page == 1 ? 1 : $start;
                                    @endphp
                                    @foreach ($karyawan as $krywn)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $krywn->nip }}</td>
                                            <td>{{ $krywn->nik }}</td>
                                            <td>{{ $krywn->nama_karyawan }}</td>
                                            <td>{{ $krywn->kantor_terakhir }}</td>
                                            <td>{{ $krywn->prefix . $krywn->jabatan_result }} {{ $krywn->entitas_result }}
                                                {{ $krywn?->bagian?->nama_bagian }} {{ $krywn->ket }}</td>
                                            <td>{{ $krywn->kategori_penonaktifan ?? '-' }}</td>
                                            <td>{{ $krywn->tanggal_penonaktifan != null ? date('d M Y', strtotime($krywn->tanggal_penonaktifan)) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between">
                                <div>
                                    Showing {{ $start }} to {{ $end }} of {{ $karyawan->total() }}
                                    entries
                                </div>
                                <div>
                                    @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $karyawan->links('pagination::bootstrap-4') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    $(document).ready(function() {
        var table = new DataTable('#table', {
            dom: 'RlBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Pergerakan Karir - Penonaktifan',
                    filename : 'Pergerakan Karir - Penonaktifan',
                    text:'Excel',
                    header: true,
                    footer: true,
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Pergerakan Karir - Penonaktifan',
                    filename : 'Pergerakan Karir - Penonaktifan',
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
                    title: 'Pergerakan Karir - Penonaktifan',
                    filename : 'Pergerakan Karir - Penonaktifan',
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
        
        $(".buttons-excel").attr("class","btn btn-success mb-2 ml-3");
        $(".buttons-pdf").attr("class","btn btn-success mb-2");
        $(".buttons-print").attr("class","btn btn-success mb-2");
    });
</script>
<script>
    $('#page_length').on('change', function() {
        $('#form').submit()
    })
    // Adjust pagination url
    var btn_pagination = $('.pagination').find('a')
    var page_url = window.location.href
    $('.pagination').find('a').each(function(i, obj) {
        if (page_url.includes('page_length')) {
            btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
        }
        if (page_url.includes('q')) {
            btn_pagination[i].href += `&q=${$('#q').val()}`
        }
    })
</script>
@endsection
