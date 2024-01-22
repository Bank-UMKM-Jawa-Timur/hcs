@extends('layouts.app-template')
@include('vendor.select2')

@section('content')
{{-- <div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title font-weight-bold">History Penjabat Sementara (PJS)</h5>
            <p class="card-title"><a href="/">Histori </a> > <a href="{{ route('pejabat-sementara.history') }}">History
                    Penjabat Sementara</a></p>
        </div>
    </div>
</div> --}}
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">History Penjabat Sementara (PJS)</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">History</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('pejabat-sementara.history') }}" class="text-sm text-gray-500 font-bold">History
                    Penjabat Sementara</a>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('pejabat-sementara.history') }}" method="post">
            @csrf
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-input" required>
                        <option value="">-- Pilih --</option>
                        <option value="aktif" @selected(request()?->kategori == 'aktif')>Aktif</option>
                        <option value="karyawan" @selected(request()->nip)>Karyawan</option>
                    </select>
                </div>
                <div class="input-box" id="nip-wrapper">
                </div>
            </div>
            <div class="mt-5">
                <button class="btn btn-primary" type="submit">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

@if($pjs)
<div class="body-pages">
    <div class="table-wrapping">
        <table class="tables-stripped border-none" id="pjs-table" style="width: 100%;">
            <thead>
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
                    <td>{{ jabatanLengkap($data) }}</td>
                    <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                    <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                    <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('style')
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
@endpush

@push('extraScript')
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script>
    function initNIP() {
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
    }

    var valKaryawan = $('#kategori').find(":selected").val();

    if (valKaryawan == "karyawan") {
        console.log(valKaryawan);
        $('#nip-wrapper').html(`
                <div class="input-box">
                    <label for="nip">Karyawan</label>
                    <select name="nip" id="nip" class="form-input" required></select>
                </div>
            `);

        initNIP();
    }else{
        $('#nip-wrapper').empty();
    }

    $('#kategori').change(function() {
        const value = $(this).val();
        console.log(value);

        if(value === 'karyawan') {
            $('#nip-wrapper').html(`
                <div class="input-box">
                    <label for="nip">Karyawan</label>
                    <select name="nip" id="nip" class="form-input" required></select>
                </div>
            `);

            initNIP();
        }else{
            $('#nip-wrapper').empty();
        }


    });

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

    $('#kategori').trigger('change');
</script>
@endpush