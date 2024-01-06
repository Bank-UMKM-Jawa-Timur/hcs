@extends('layouts.template')

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

    table.dataTable td {
        font-size: 9px;
    }
</style>

<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title @active('klasifikasi') font-weight-bold">Data Masa Pensiun</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a
                    href="{{ route('karyawan.index') }}">Karyawan</a> > Data Masa Pensiun</p>
        </div>
    </div>
</div>
<form action="{{ route('reminder-pensiun.show') }}" method="get">
    {{--  Form  --}}
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Kategori {{ old('kategori') }}</label>
                    <select name="kategori" class="form-control" id="kategori" required>
                        <option value="">--- Pilih Kategori ---</option>
                        <option @selected($request?->kategori == 1) value="1">Keseluruhan</option>
                        <option @selected($request?->kategori == 2) value="2">Divisi</option>
                        <option @selected($request?->kategori == 3) value="3">Sub Divisi</option>
                        <option @selected($request?->kategori == 4) value="4">Bagian</option>
                        <option @selected($request?->kategori == 5) value="5">Kantor</option>
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

            <div id="jabatan_col" class="col-md-4">
            </div>

            <div id="panggol_col" class="col-md-4">
            </div>

            <div id="status_col" class="col-md-4">
            </div>

            <div id="pendidikan_col" class="col-md-4">
            </div>

            <div class="col-md-12 mt-2">
                <button class="is-btn is-primary" type="submit">Tampilkan</button>
            </div>
        </div>
    </div>
</form>
    {{--  Table  --}}
    <div class="card ml-3 mr-3 mb-3 mt-3 shadow">
        <div class="col-md-12">
            @if ($karyawan != null)
            <div class="table-responsive table overflow-hidden pt-2">
                <table class="table cell-border stripe" style="width: 100%; word-break: break-all;" id="table_export">
                    <thead>
                        <tr>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">NIP
                            </th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 100px;">
                                Nama</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 90px;">
                                Jabatan</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 50px;">
                                Kantor</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">Gol
                            </th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                                Tanggal<br>Lahir</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 35px;">
                                Umur</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 25px;">JK
                            </th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                                Status</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; ">SK<br>Angkat</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                                Tanggal<br>Angkat</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 40px;">
                                Masa<br>Kerja</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 9px; min-width: 40px;">
                                Pendidikan<br>Terakhir</th>
                            <th style="background-color: #CCD6A6; text-align: center; font-size: 11px; min-width: 65px;">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawan as $item)
                        @php
                        $umur = Carbon\Carbon::create($item->tgl_lahir);
                        $waktuSekarang = Carbon\Carbon::now();
                        $hitung = $waktuSekarang->diff($umur);
                        $tampilUmur = $hitung->format('%y,%m');

                        $tglLahir = $item->tgl_lahir;
                        $lahir = Carbon\Carbon::create($tglLahir);
                        $pensiun = Carbon\Carbon::create(date('Y-m-d', strtotime($tglLahir. ' + 56 years')));
                        $hitungPensiun = $pensiun->diff($waktuSekarang);
                        $tampilPensiun = null;
                        $textColor = null;
                        if ($waktuSekarang->diffInYears($umur) >= 54) {
                        $tampilPensiun = 'Persiapan pensiun dalam ' . $hitungPensiun->format('%y Tahun, %m Bulan, %d Hari');
                        if ($waktuSekarang->diffInYears($umur ) >= 55) {
                        $textColor = 'text-warning';
                        } else {
                        $textColor = 'text-success';
                        }
                        }
                        if ($waktuSekarang->diffInYears($umur) >= 56) {
                        $tampilPensiun = 'Sudah melebihi batas pensiun';
                        $textColor = 'text-danger';
                        }
                        @endphp
                        <tr>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td>{{ jabatanLengkap($item) . ' ' . $item->ket_jabatan ?? '-' }}</td>
                            @php
                            $nama_cabang = DB::table('mst_cabang')
                            ->where('kd_cabang', $item->kd_entitas)
                            ->first();
                            @endphp
                            <td>{{ ($nama_cabang != null) ? $nama_cabang->nama_cabang : 'Pusat' }}</td>
                            <td>{{ ($item->kd_panggol != null) ? $item->kd_panggol : '-' }}</td>
                            @php
                            $tglLahir = date('d M Y', strtotime($item->tgl_lahir));
                            @endphp
                            <td>{{ ($item->tgl_lahir != null) ? $tglLahir : '-' }}</td>
                            <td>{{ $tampilUmur }}</td>
                            @php
                            if ($item->jk == 'Laki-laki') {
                            $jk = 'L';
                            } else {
                            $jk = 'P';
                            }

                            @endphp
                            <td>{{ $jk }}</td>
                            @php
                            if ($item->status == 'Kawin' || $item->status == 'K') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'K';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'K';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Belum Kawin' || $item->status == 'TK') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'TK';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'TK';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Tidak Diketahui') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'TD';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'TD';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Cerai Mati') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'CM';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'CM';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Cerai') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'CR';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'CR';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Janda') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'JD';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'JD';
                            $anak = 0;
                            }
                            } elseif ($item->status == 'Duda') {
                            if ($item->keluarga?->jml_anak != null) {
                            $status = 'DA';
                            $anak = $item->keluarga?->jml_anak;
                            } else {
                            $status = 'DA';
                            $anak = 0;
                            }
                            } else {
                            $status = '-';
                            $anak = '-';
                            }
                            @endphp
                            <td>{{ $status }}/{{ $anak }}</td>
                            <td>{{ ($item->skangkat != null) ? $item->skangkat : '-' }}</td>
                            <td>{{ ($item->tanggal_pengangkat != null) ? date('d M Y', strtotime($item->tanggal_pengangkat))
                                : '-' }}</td>
                            @php
                            $mulaKerja = Carbon\Carbon::create($item->tgl_mulai);
                            $waktuSekarang = Carbon\Carbon::now();

                            $hitung = $waktuSekarang->diff($mulaKerja);
                            $masaKerja = $hitung->format('%y,%m');
                            @endphp
                            <td>{{ ($item->tgl_mulai != null) ? $masaKerja : '-' }}</td>
                            <td>{{ $item->pendidikan ?? '-' }}</td>
                            <td style="font-size: 12px" class="{{ $textColor }}">{{ $tampilPensiun ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $karyawan->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    @endif
                </div>
            </div>
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
<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script>
    // Pengambilan Kategori
        var k = document.getElementById("kategori");
        var category = k.options[k.selectedIndex].text;

        $("#table_export").DataTable({
            dom : "Bfrtip",
            pageLength: 25,
            bPaginate: false,
            bInfo : false,
            ordering: false,
            drawCallback: function () {
                var ikjp = $('#table_export').DataTable().column(2).data().sum();
                var tetap = $('#table_export').DataTable().column(3).data().sum();
                var kp = $('#table_export').DataTable().column(4).data().sum();
                $('#total_IKJP').html(ikjp);
                $('#total_Tetap').html(tetap);
                $('#total_KP').html(kp);

                $('#total').html((kp + tetap + ikjp));
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    title : function(){
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan") {
                            return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category +' '+selectedValueKantor+'_'+selectedValueCabang+'';
                                    }
                                }

                            }
                        }
                    },
                    filename : function(){
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan") {
                            return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category +' '+selectedValueKantor+'_'+selectedValueCabang+'';
                                    }
                                }

                            }
                        }
                    },
                    message: 'Klasifikasi Data Karyawan\n ',
                    text:'Excel',
                    header: true,
                    footer: true,
                    exportOptions: {
                        orthogonal: 'sort'
                    },
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Bank UMKM Jawa Timur\n Klasifikasi Data Karyawan ',
                    filename : function(){
                        var selectedValueKantor = $('#kantor').val();
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan") {
                            return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    return 'Bank UMKM Jawa Timur Data Masa Pensiun kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        return 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category +' '+selectedValueKantor+'_'+selectedValueCabang+'';
                                    }
                                }

                            }
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
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        if (selectedValueCategory === "Keseluruhan") {
                            doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        doc.content[0].text = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category +' '+selectedValueKantor+'_'+selectedValueCabang+'';
                                    }
                                }

                            }
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
                        var selectedValueCategory = $('#kategori').find('option:selected').text();
                        var selectedValueDivisi = $('#divisi').find('option:selected').text();
                        var selectedValueSubDivisi = $('#subDivisi').find('option:selected').text();
                        var selectedValueBagian = $('#bagian').find('option:selected').text();
                        var selectedValueCabang = $('#cabang').find('option:selected').text();

                        var header = document.createElement('h1');
                        if (selectedValueCategory === "Keseluruhan") {
                            header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                        }else{
                            // Kategori divisi, sub divisi, bagian
                            if (selectedValueCategory === "Divisi" || selectedValueCategory === "Sub Divisi" || selectedValueCategory === "Bagian") {
                                if (selectedValueDivisi === null || selectedValueDivisi === undefined) {
                                    header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '';
                                }else{
                                    if (selectedValueSubDivisi === null || selectedValueSubDivisi === undefined || selectedValueSubDivisi === "--- Pilih Sub Divisi ---") {
                                        header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori Divisi_'+selectedValueDivisi+'';
                                    }else{
                                        if (selectedValueBagian === null || selectedValueBagian === undefined || selectedValueBagian === "--- Pilih Bagian ---") {
                                            header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+'';
                                        }else{
                                            header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori Divisi_'+selectedValueDivisi+', Sub Divisi_'+selectedValueSubDivisi+', Bagian_'+selectedValueBagian+'';
                                        }
                                    }
                                }
                            }else if(selectedValueCategory === "Kantor"){
                                // kategori kantor, cabang
                                if (selectedValueKantor === null || selectedValueKantor === undefined) {
                                    header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun kategori_' + category + '';
                                } else {
                                    if(selectedValueCabang === null || selectedValueCabang === undefined || selectedValueCabang === "--- Pilih Cabang ---" || selectedValueCabang === ""){
                                        header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category + '_'+selectedValueKantor+'';
                                    }else{
                                        header.textContent = 'Bank UMKM Jawa Timur Data Masa Pensiun Kategori_' + category +' '+selectedValueKantor+'_'+selectedValueCabang+'';
                                    }
                                }

                            }
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
            ],
            columnDefs: [{
                targets:[1],
                render: function(data, type, row, meta){
                    if(type === 'sort'){
                        //data = ' ' + data ;
                        return "\u200C" + data ;
                    }

                    return data ;

                }
            }]
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
            $('#jabatan_col').empty();
            $('#panggol_col').empty();
            $('#status_col').empty();
            $('#pendidikan_col').empty();

            if (value == 2) {
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

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 3) {
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

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 4) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').removeAttr("disabled", "disabled");
                $("#bagian_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 5) {
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

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 6) {
                generateOfficeGaji();

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

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 7) {
                generatePendidikan();

                $('#pendidikan').removeAttr("disabled", "disabled");
                $("#pendidikan_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();
            } else if (value == 9) {
                generateJabatan();

                $('#jabatan').removeAttr("disabled", "disabled");
                $("#jabatan_col").show();

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

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 10) {
                generatePanggol();

                $('#panggol').removeAttr("disabled", "disabled");
                $("#panggol_col").show();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

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

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            } else if (value == 11) {
                generateStatus();

                $('#status').removeAttr("disabled", "disabled");
                $("#status_col").show();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

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

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
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

                $('#jabatan').attr("disabled", "disabled");
                $("#jabatan_col").hide();

                $('#panggol').attr("disabled", "disabled");
                $("#panggol_col").hide();

                $('#status').attr("disabled", "disabled");
                $("#status_col").hide();

                $('#pendidikan').attr("disabled", "disabled");
                $("#pendidikan_col").hide();
            }
        });

        function generateDivision() {
            const division = '{{ $request?->divisi }}';
            $.ajax({
                type: 'GET',
                url: "{{ route('get_divisi') }}",
                dataType: 'JSON',
                success: (res) => {
                    $('#divisi_col').empty();
                    $('#divisi_col').append(`
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="divisi" class="form-control">
                                <option value="-">--- Pilih Divisi ---</option>
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
                                <option value="-">--- Pilih Sub Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $('#divisi').change(function(e) {
                        var divisi = $(this).val();

                        if (divisi) {
                            const subDivision = '{{ $request?->subDivisi }}';

                            $.ajax({
                                type: 'GET',
                                url: "{{ route('get_subdivisi') }}?divisiID="+divisi,
                                dataType: 'JSON',
                                success: (res) => {
                                    $('#subDivisi').empty();
                                    $('#subDivisi').append('<option value="">--- Pilih Sub Divisi ---</option>');

                                    $.each(res, (i, item) => {
                                        const kd_subDivisi = item.kd_subdiv;
                                        $('#subDivisi').append(`<option ${subDivision == kd_subDivisi ? 'selected' : ''} value="${kd_subDivisi}">${item.kd_subdiv} - ${item.nama_subdivisi}</option>`);
                                    });

                                    $('#bagian_col').empty();
                                    $('#bagian_col').append(`
                                        <div class="form-group">
                                            <label for="bagian">Bagian</label>
                                            <select name="bagian" id="bagian" class="form-control">
                                                <option value="-">--- Pilih Bagian ---</option>
                                            </select>
                                        </div>
                                    `);

                                    $("#subDivisi").change(function(){
                                        const bagian = '{{ $request?->bagian}}';
                                        $.ajax({
                                            type: "GET",
                                            url: "{{ route('getBagian') }}?kd_entitas="+$(this).val(),
                                            datatype: "JSON",
                                            success: function(res){
                                                $('#bagian').empty();
                                                $('#bagian').append('<option value="">--- Pilih Bagian ---</option>');

                                                $.each(res, (i, item) => {
                                                    const kd_bagian = item.kd_bagian;
                                                    $('#bagian').append(`<option ${bagian == kd_bagian ? 'selected' : ''} value="${kd_bagian}">${item.kd_bagian} - ${item.nama_bagian}</option>`);
                                                });
                                            }
                                        })
                                    });
                                    $('#subDivisi').trigger('change');
                                }
                            });
                        }
                    });

                    $('#divisi').trigger('change');
                }

            });
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
                    url: "{{ route('get_cabang') }}",
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
                                    <option value="-">--- Pilih Cabang ---</option>
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

        function generateOfficeGaji() {
            const office = '{{ $request?->kantor }}';
            $('#kantor_col').append(`
                <div class="form-group">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-control" id="kantor">
                        <option value="-">--- Pilih Kantor ---</option>
                        <option ${ office == "Keseluruhan" ? 'selected' : '' } value="Keseluruhan">Keseluruhan</option>
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
                    url: "{{ route('get_cabang') }}",
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
                                    <option value="-">--- Pilih Cabang ---</option>
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

        function generateJabatan() {
            const jabatan = '{{ $request?->jabatan }}';
            $('#jabatan_col').append(`
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control">
                        <option value="-">--- Pilih Jabatan ---</option>
                        @foreach ($jabatan as $item)
                            <option ${ jabatan == "{{ $item->kd_jabatan }}" ? 'selected' : '' } value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generatePanggol() {
            const panggol= '{{ $request?->panggol }}';
            $('#panggol_col').append(`
                <div class="form-group">
                    <label for="panggol">Jabatan</label>
                    <select name="panggol" id="panggol" class="form-control">
                        <option value="-">--- Pilih Jabatan ---</option>
                        @foreach ($panggol as $item)
                            <option ${ panggol == "{{ $item->golongan }}" ? 'selected' : '' } value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generateStatus() {
            const status= '{{ $request?->status }}';
            $('#status_col').append(`
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="-">--- Pilih Status ---</option>
                        @foreach (\App\Enum\StatusKaryawan::cases() as $item)
                            <option ${ status == "{{ $item }}" ? 'selected' : '' } value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        function generatePendidikan() {
            const pendidikan= '{{ $request?->pendidikan }}';
            $('#pendidikan_col').append(`
                <div class="form-group">
                    <label for="pendidikan">Pendidikan</label>
                    <select name="pendidikan" id="pendidikan" class="form-control">
                        <option value="-">--- Pilih Pendidikan ---</option>
                        @foreach (\App\Enum\PendidikanKaryawan::cases() as $item)
                            <option ${ pendidikan == "{{ $item }}" ? 'selected' : '' } value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            `);
        }

        $('#kategori').trigger('change');
        $('#kantor').trigger('change');
</script>
@endsection
