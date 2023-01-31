@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="card-header">
    <div class="card-title">
        <h5 class="card-title">History PJS</h5>
        <p class="card-title"><a href="/">Dashboard </a> > <a href="">History PJS</a></p>
    </div>
</div>

<div class="card-body p-4">
    <div class="row justify-content-between">
        <div class="col-md-5">
            <form action="{{ route('pejabat-sementara.history') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="aktif" @selected(request()?->kategori == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request()?->kategori == 'nonaktif')>Nonaktif</option>
                    </select>
                    <button class="btn btn-primary float-right" type="submit">Tampilkan</button>
                </div>
            </form>
        </div>
        <div class="col-md-5">
            <form action="{{ route('pejabat-sementara.history') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="nip">Karyawan</label>
                    <select name="nip" id="nip" class="form-control"></select>
                    <button class="btn btn-primary float-right" type="submit">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($pjs)
<div class="card ml-3 mr-3 mb-3 mt-3 shadow">
    <div class="col-md-12">
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="pjs-table" style="width: 100%;">
                <thead style="background-color: #CCD6A6;">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama Karyawan</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Mulai</th>
                        <th class="text-center">Berakhir</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($pjs as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nip }}</td>
                    <td>{{ $data->karyawan->nama_karyawan }}</td>
                    <td>{{ $data->jabatan->nama_jabatan }}</td>
                    <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                    <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                    <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('style')
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
@endpush

@push('script')
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}',
            data: function(params) {
                return {
                    search: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true,
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    @isset(request()->nip)
        @if($karyawan)
            nipSelect.append(`
                <option value="{{$karyawan->nip}}">{{$karyawan->nip}} - {{$karyawan->nama_karyawan}}</option>
            `).trigger('change');
        @endif
    @endisset

    $('#pjs-table').DataTable({
        dom : "Bfrtip",
        pageLength: 25,
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Bank UMKM Jawa Timur',
                filename : 'Bank UMKM Jawa Timur Laporan PJS',
                message: 'Laporan PJS\n ',
                text:'Excel',
                header: true,
                footer: true,
                customize: function( xlsx, row ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Bank UMKM Jawa Timur\n Laporan PJS ',
                filename : 'Bank UMKM Jawa Timur Laporan PJS',
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
                title: 'Bank UMKM Jawa Timur Laporan PJS ',
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
@endpush
