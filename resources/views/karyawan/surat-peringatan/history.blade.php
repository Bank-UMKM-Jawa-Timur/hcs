@extends('layouts.template')
@include('vendor.select2')

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
                        <label for="kategori">Kategori</label>
                        <select id="kategori" class="form-control">
                            <option value="">Semua Data</option>
                            <option value="nip" @selected($request->nip)>Karyawan</option>
                            <option value="tanggal" @selected($request->first_date || $request->end_date)>Tanggal</option>
                            <option value="tahun" @selected($request->tahun)>Tahun</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="kategori-wrapper"></div>
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
                            <th class="text-center">NIP</th>
                            <th class="text-center">Nama Karyawan</th>
                            <th class="text-center">Kantor</th>
                            <th class="text-center">Pelanggaran</th>
                            <th class="text-center">Sanksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($history as $sp)
                        <tr>
                            <td>{{ $sp->no_sp }}</td>
                            <td>{{ $sp->tanggal_sp->format('d/m/Y') }}</td>
                            <td>{{ $sp->nip }}</td>
                            <td>{{ $sp->karyawan->nama_karyawan }}</td>
                            @php
                                $cab = isset($sp->karyawan->entitas->cab);
                            @endphp
                            <td>{{ $cab ? $sp->karyawan->entitas->cab->nama_cabang : 'Pusat' }}</td>
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
function generateKategori(kategori) {
    if(kategori == 'tahun') {
        return `
        <div class="form-group col-md-4">
            <label for="year">Tahun</label>
            <select name="tahun" id="year" class="form-control">
                @for ($year = $firstYear; $year <= date('Y'); $year++)
                    <option value="{{ $year }}" @selected($year == $request->tahun)>{{ $year }}</option>
                @endfor
            </select>
        </div>
        `;
    }

    if(kategori == 'nip') {
        return `
        <div class="form-group col-md-4">
            <label for="nip">Karyawan</label>
            <select class="form-control" id="nip" name="nip"></select>
        </div>
        `;
    }

    if(kategori == 'tanggal') {
        return `
        <div class="form-group col-md-4">
            <label for="first-date">Tanggal Awal</label>
            <input class="form-control @error('first_date') is-invalid @enderror" type="date" id="first-date" name="first_date" value="{{ $request->first_date }}">
            @error('first_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="end-date">Tanggal Akhir</label>
            <input class="form-control" type="date" id="end-date" name="end_date" value="{{ $request->end_date }}">
        </div>
        `;
    }
}

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

$('#kategori').change(function() {
    const wrapper = $('#kategori-wrapper');
    const kategori = $(this).val();

    wrapper.html('');
    wrapper.append(generateKategori(kategori));

    if(kategori == 'nip') initNIP();
});

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

$('#kategori').trigger('change');
</script>
@endpush
