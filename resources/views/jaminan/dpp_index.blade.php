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
            <h2 class="text-2xl font-bold tracking-tighter">Laporan DPP</h2>
            <div class="breadcrumb">
             <a href="#" class="text-sm text-gray-500">Laporan</a>
             <i class="ti ti-circle-filled text-theme-primary"></i>
             <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Laporan DPP</a>
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
                        <div class="table-wrapping mt-10">
                            <div class="col-md-12">
                                <h5 class="text-center align-item-center"><b>Data Ini Masih Belum Diproses ({{ getMonth($bulan) }} {{ $tahun
                                        }})</b></h5>
                            </div>
                        </div>
                    @endif
                    @if ($status == 1)
                        <div class="table-wrapping">
                            <table class="tables-stripped" id="table_export" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Kode Kantor</th>
                                        <th style="text-align: center">Nama Kantor</th>
                                        <th style="text-align: center">DPP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>-</td>
                                        <td>Kantor Pusat</td>
                                        <td>{{ number_format($dpp_pusat, 0, ".", ",") }}</td>
                                    </tr>
                
                                    @php
                                    $total_tunjangan_keluarga = array();
                                    $total_tunjangan_kesejahteraan = array();
                                    $total_gj_cabang = array();
                                    $total_jamsostek = array();
                
                                    $total_dpp = array();
                
                                    array_push($total_dpp, $dpp_pusat);
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
                                        $total_tunjangan_keluarga = array();
                                        $total_tunjangan_kesejahteraan = array();
                                        $total_gj_cabang = array();
                                        $gj_cabang = null;
                
                                        $karyawan = DB::table('mst_karyawan')
                                        ->where('kd_entitas', $item->kd_entitas)
                                        ->whereNotIn('status_karyawan', ['Kontrak Perpanjangan', 'IKJP'])
                                        ->get();
                                        // Cek Data Di Table Gaji Perbulan
                
                                        // Jika Data Tidak Tersedia Di Gaji Perbulan
                                        if($cek_data == 0){
                                        foreach($karyawan as $i){
                                        if($i->status_karyawan == 'Tetap'){
                                        $data_gaji = DB::table('mst_karyawan')
                                        ->where('nip', $i->nip)
                                        ->select('gj_pokok', 'gj_penyesuaian')
                                        ->first();
                                        $data_tj_keluarga = DB::table('tunjangan_karyawan')
                                        ->where('nip', $i->nip)
                                        ->where('id_tunjangan', 1)
                                        ->first();
                                        $data_tj_kesejahteraan = DB::table('tunjangan_karyawan')
                                        ->where('nip', $i->nip)
                                        ->where('id_tunjangan', 8)
                                        ->first();
                
                                        array_push($total_gj_cabang, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                                        array_push($total_tunjangan_keluarga, ($data_tj_keluarga != null) ? $data_tj_keluarga->nominal :
                                        0);
                                        array_push($total_tunjangan_kesejahteraan, ($data_tj_kesejahteraan != null) ?
                                        $data_tj_kesejahteraan->nominal : 0);
                                        }
                                        }
                                        } else{
                                        foreach($karyawan as $i){
                                        if($i->status_karyawan == 'Tetap'){
                                        $data_gaji = DB::table('gaji_per_bulan')
                                        ->where('nip', $i->nip)
                                        ->where('bulan', $bulan)
                                        ->where('tahun', $tahun)
                                        ->first();
                
                                        array_push($total_tunjangan_keluarga, ($data_gaji != null) ? $data_gaji->tj_keluarga : 0);
                                        array_push($total_tunjangan_kesejahteraan, ($data_gaji != null) ? $data_gaji->tj_kesejahteraan :
                                        0);
                                        array_push($total_gj_cabang, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                                        }
                                        }
                                        }
                
                                        $gj_cabang = round((array_sum($total_gj_cabang) + array_sum($total_tunjangan_keluarga) +
                                        (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13);
                
                                        array_push($total_dpp, $gj_cabang);
                                        @endphp
                                        <td>{{ number_format($gj_cabang, 0, ".", ",") }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="2" style="text-align: center">
                                            Jumlah
                                        </td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format(array_sum($total_dpp), 0, ".", ",") }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @elseif($status == 2)
                        <div class="table-wrapping overflow-hidden pt-2">
                            <table class="tables text-center cell-border stripe" id="table_export" style="width: 100%">
                                <thead style="background-color: #CCD6A6">
                                    <th style="text-align: center">NIP</th>
                                    <th style="text-align: center">Nama Karyawan</th>
                                    <th style="text-align: center">DPP</th>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($karyawan); $i++) @if ($karyawan[$i]->status_karyawan == 'Tetap')
                                        <tr>
                                            <td>{{ $karyawan[$i]->nip }}</td>
                                            <td>{{ $karyawan[$i]->nama_karyawan }}</td>
                                            <td>{{ isset($dpp[$i]) ? number_format($dpp[$i], 0, ".", ",") : '0' }}</td>
                                        </tr>
                                        @endif
                                        @endfor
                                </tbody>
                                <tfoot style="font-weight: bold">
                                    <tr>
                                        <td colspan="2" style="text-align: center">Jumlah</td>
                                        <td style="background-color: #FED049; text-align: center;">{{ number_format(array_sum($dpp), 0, ".", ",") }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<button class="btn-scroll-to-top btn btn-primary fixed hidden bottom-5 right-5">
    To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
</button>

@endsection

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
                    customize: function( xlsx, row ) {
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
@endpush