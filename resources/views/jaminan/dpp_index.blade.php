@extends('layouts.template')
@php
$request = isset($request) ? $request : null;
$status = isset($status) ? $status : null;
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
                <h5 class="card-title">Laporan DPP</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="">Laporan DPP </a></p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('get-dpp') }}" method="post">
            @csrf
            <div class="row m-0">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kategori {{ old('kategori') }}</label>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">--- Pilih Kategori ---</option>
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
            <div class="row m-0">
                @php
                    $already_selected_value = date('y');
                    $earliest_year = 2022;
                @endphp
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Bulan">Bulan</label>
                        <select name="bulan" class="form-control">
                            <option value="-">--- Pilih Bulan ---</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option @selected($request?->bulan == $i) value="{{ $i }}">{{ getMonth($i) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="tahun">Tahun</label>
                    <div class="form-group">
                        <select name="tahun" class="form-control">
                            <option value="">--- Pilih Tahun ---</option>
                            @foreach (range(date('Y'), $earliest_year) as $x)
                                <option @selected($request?->tahun == $x) value="{{ $x }}">{{ $x }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-info" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card ml-3 mr-3 mb-3 mt-3 shadow">
        <div class="col-md-12">
            @if ($status != null)
                @php
                    function rupiah($angka)
                    {
                        $hasil_rupiah = number_format($angka, 0, ".", ",");
                        return $hasil_rupiah;
                    }
                @endphp
                @if ($status == 1)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead style="background-color: #CCD6A6">
                                <th style="text-align: center">Kode Kantor</th>
                                <th style="text-align: center">Nama Kantor</th>
                                <th style="text-align: center">DPP</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>Kantor Pusat</td>
                                    <td>{{ rupiah($dpp_pusat) }}</td>
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
                                            foreach($karyawan as $i){
                                                if($i->status_karyawan == 'Tetap'){
                                                    $data_gaji = DB::table('gaji_per_bulan')
                                                        ->where('nip', $i->nip)
                                                        ->where('bulan', $bulan)
                                                        ->where('tahun', $tahun)
                                                        ->first();

                                                    array_push($total_tunjangan_keluarga, ($data_gaji != null) ? $data_gaji->tj_keluarga : 0);
                                                    array_push($total_tunjangan_kesejahteraan, ($data_gaji != null) ? $data_gaji->tj_kesejahteraan : 0);
                                                    array_push($total_gj_cabang, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                                                }
                                            }

                                            $gj_cabang = round((array_sum($total_gj_cabang) + array_sum($total_tunjangan_keluarga) + (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13);

                                            array_push($total_dpp, $gj_cabang);
                                        @endphp
                                        <td>{{ rupiah($gj_cabang) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="2" style="text-align: center">
                                        Jumlah
                                    </td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($total_dpp)) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @elseif($status == 2)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead style="background-color: #CCD6A6">
                                <th style="text-align: center">NIP</th>
                                <th style="text-align: center">Nama Karyawan</th>
                                <th style="text-align: center">DPP</th>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($karyawan); $i++)
                                    @if ($karyawan[$i]->status_karyawan == 'Tetap')
                                        <tr>
                                            <td>{{ $karyawan[$i]->nip }}</td>
                                            <td>{{ $karyawan[$i]->nama_karyawan }}</td>
                                            <td>{{ rupiah($dpp[$i]) }}</td>
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="2" style="text-align: center">Jumlah</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($dpp)) }}</td>
                                </tr>
                            </tfoot>
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
            iDisplayLength: -1,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Bank UMKM Jawa Timur\n Bulan '+name,
                    text:'Excel',
                    customize: function( xlsx, row ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                }
            ]
        });
        
        $(".buttons-excel").attr("class","btn btn-success mb-2");
        
        // document.getElementById('btn_export').addEventListener('click', function(){
        //     var table2excel = new Table2Excel();
        //     table2excel.export(document.querySelectorAll('#table_export'));
        // });


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
                                <label for="Cabang">Cabang</label>
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

        function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $('#kategori').trigger('change');
        $('#kantor').trigger('change');
    </script>
@endsection
