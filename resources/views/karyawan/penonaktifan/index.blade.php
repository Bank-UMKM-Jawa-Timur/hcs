@extends('layouts.template')
@section('content')
<div class="card-header">
    <div class="card-header">
      <h5 class="card-title">Penonaktifan Karyawan</h5>
      <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > Penonaktifan Karyawan</p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                @can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan')
                <a class="mb-3" href="{{ route('penonaktifan.create') }}">
                  <button class="btn btn-primary">tambah penonaktifan</button>
                </a>
                @endcan
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
                            $i = 1;
                        @endphp
                        @foreach ($karyawan as $krywn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $krywn->nip }}</td>
                                <td>{{ $krywn->nik }}</td>
                                <td>{{ $krywn->nama_karyawan }}</td>
                                <td>{{ ($krywn->entitas->type == 2) ?
                                    $krywn->entitas->cab->nama_cabang :
                                    'Pusat'
                                }}</td>
                                @php
                                    $prefix = match($krywn->status_jabatan) {
                                        'Penjabat' => 'Pj. ',
                                        'Penjabat Sementara' => 'Pjs. ',
                                        default => '',
                                    };

                                    $jabatan = $krywn->jabatan->nama_jabatan;

                                    $ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

                                    if(isset($krywn->entitas->subDiv)) {
                                        $entitas = $krywn->entitas->subDiv->nama_subdivisi;
                                    } else if(isset($krywn->entitas->div)) {
                                        $entitas = $krywn->entitas->div->nama_divisi;
                                    } else {
                                        $entitas = '';
                                    }

                                    if ($jabatan == "Pemimpin Sub Divisi") {
                                    $jabatan = 'PSD';
                                    } else if ($jabatan == "Pemimpin Bidang Operasional") {
                                    $jabatan = 'PBO';
                                    } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                                    $jabatan = 'PBP';
                                    } else {
                                    $jabatan = $krywn->jabatan->nama_jabatan;
                                    }
                                @endphp
                                <td>{{ $prefix . $jabatan }} {{ $entitas }} {{ $krywn?->bagian?->nama_bagian }} {{ $ket }}</td>
                                <td>{{ $krywn->kategori_penonaktifan ?? '-' }}</td>
                                <td>{{ $krywn->tanggal_penonaktifan != null ? date('d M Y', strtotime($krywn->tanggal_penonaktifan)) : '-' }}</td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
            </div>
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
@endsection
