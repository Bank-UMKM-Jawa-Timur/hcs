@extends('layouts.template')
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
                <h5 class="card-title">Laporan JAMSOSTEK</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="">Laporan JAMSOSTEK </a></p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('filter-laporan') }}" method="post">
            @csrf
            <div class="row m-0">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kategori</label>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">--- Pilih Kategori ---</option>
                            <option {{ old('kategori') == '1' ? 'selected' : '' }} value="1">Rekap Keseluruhan</option>
                            <option {{ old('kategori') == '2' ? 'selected' : '' }} value="2">Rekap Kantor / Cabang</option>
                        </select>
                    </div>
                </div>
                @php
                    $already_selected_value = date('y');
                    $earliest_year = 2022;
                @endphp
                <div class="col-md-4">
                    <label for="tahun">Tahun</label>
                    <div class="form-group">
                        <select name="tahun" class="form-control">
                            <option value="">--- Pilih Tahun ---</option>
                            @foreach (range(date('Y'), $earliest_year) as $x)
                                <option {{ old('tahun') == $x  ? 'selected' : '' }} value="{{ $x }}">{{ $x }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Bulan">Bulan</label>
                        <select name="bulan" class="form-control">
                            <option {{ old('bulan') == '-' ? 'selected' : '' }} value="-">--- Pilih Bulan ---</option>
                            <option {{ old('bulan') == '1' ? 'selected' : '' }} value='1'>Januari</option>
                            <option {{ old('bulan') == '2' ? 'selected' : '' }} value='2'>Februari </option>
                            <option {{ old('bulan') == '3' ? 'selected' : '' }} value='3'>Maret</option>
                            <option {{ old('bulan') == '4' ? 'selected' : '' }} value='4'>April</option>
                            <option {{ old('bulan') == '5' ? 'selected' : '' }} value='5'>Mei</option>
                            <option {{ old('bulan') == '6' ? 'selected' : '' }} value='6'>Juni</option>
                            <option {{ old('bulan') == '7' ? 'selected' : '' }} value='7'>Juli</option>
                            <option {{ old('bulan') == '8' ? 'selected' : '' }} value='8'>Agustus</option>
                            <option {{ old('bulan') == '9' ? 'selected' : '' }} value='9'>September</option>
                            <option {{ old('bulan') == '10' ? 'selected' : '' }} value='10'>Oktober</option>
                            <option {{ old('bulan') == '11' ? 'selected' : '' }} value='11'>November</option>
                            <option {{ old('bulan') == '12' ? 'selected' : '' }} value='12'>Desember</option>
                        </select>
                    </div>
                </div>
                <div id="kantor_col">
                </div>
                <div id="cabang_col">
                </div>
                <div class="col-md-4 mt-2">
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
                    function rupiahJkk($angka)
                    {
                        $hasil_rupiah = number_format($angka, 0, ".", ",");
                        return $hasil_rupiah;
                    }
                @endphp
                @if ($status == 1)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Kode Kantor</th>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Nama Kantor</th>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Jumlah Pegawai</th>
                                    <th colspan="4" style="background-color: #CCD6A6; text-align: center;">JAMSOSTEK</th>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">JP(1%)</th>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">JP(2%)</th>
                                    <th rowspan="2" style="background-color: #CCD6A6; text-align: center;">Total JP</th>
                                </tr>                                   
                                <tr style="background-color: #DAE2B6">
                                    <th style="text-align: center;">JKK</th>
                                    <th style="text-align: center;">JHT</th>
                                    <th style="text-align: center;">JKM</th>
                                    <th style="text-align: center;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>Kantor Pusat</td>
                                    <td>{{ $count_pusat }}</td>
                                    <td>{{ rupiahJkk(((0.0024 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(((0.057 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(((0.003 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah((((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) + ((0.003 * $total_gaji_pusat))) }}</td>
                                    <td>{{ rupiah(array_sum($jp1_pusat)) }}</td>
                                    <td>{{ rupiah(array_sum($jp2_pusat)) }}</td>
                                    <td>{{ rupiah((array_sum($jp1_pusat) + array_sum($jp2_pusat))) }}</td>
                                </tr>

                                @php
                                    $total_jkk = array();
                                    $total_jht = array();
                                    $total_jkm = array();
                                    $total_jamsostek = array();

                                    $total_jp1 = array();
                                    $total_jp2 = array();
                                    $total_jp = array();

                                    array_push($total_jamsostek, (((0.0024 * $total_gaji_pusat)) + ((0.057 * $total_gaji_pusat))) + ((0.003 * $total_gaji_pusat)));
                                    array_push($total_jkk, ((0.0024 * $total_gaji_pusat)));
                                    array_push($total_jht, ((0.057 * $total_gaji_pusat)));
                                    array_push($total_jkm, ((0.003 * $total_gaji_pusat)));

                                    array_push($total_jp, (array_sum($jp1_pusat) + array_sum($jp2_pusat)));
                                    array_push($total_jp1, array_sum($jp1_pusat));
                                    array_push($total_jp2, array_sum($jp2_pusat));
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
                                            $jp1_cabang = array();
                                            $jp2_cabang = array();
                                            $total_gaji_cabang = array();

                                            $karyawan = DB::table('mst_karyawan')
                                                ->where('kd_entitas', $item->kd_entitas)
                                                ->get();
                                            foreach($karyawan as $i){
                                                $data_gaji = DB::table('tunjangan_karyawan')
                                                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                                                    ->where('nip', $i->nip)
                                                    ->where('mst_tunjangan.status', 1)
                                                    ->sum('tunjangan_karyawan.nominal');

                                                // if ($i->gj_penyesuaian != null) {
                                                    array_push($total_gaji_cabang, ((isset($data_gaji)) ? $data_gaji + $i->gj_pokok + $i->gj_penyesuaian : 0 + $i->gj_pokok + $i->gj_penyesuaian));
                                                // } else {
                                                //     array_push($total_gaji_cabang, ((isset($data_gaji)) ? $data_gaji + $i->gj_pokok : 0 + $i->gj_pokok));
                                                // }
                                            }
                                            foreach($total_gaji_cabang as $i){
                                                array_push($jp1_cabang, ((($i >  9077600) ?  9077600 * 0.01 : $i * 0.01)));
                                                array_push($jp2_cabang, ((($i >  9077600) ?  9077600 * 0.02 : $i * 0.02)));
                                            }
                                        @endphp
                                        <td>{{ count($karyawan) }}</td>
                                        <td>{{ rupiahJkk(((0.0024 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(((0.057 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(((0.003 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah((((0.0024 * array_sum($total_gaji_cabang))) + ((0.057 * array_sum($total_gaji_cabang)))) + ((0.003 * array_sum($total_gaji_cabang)))) }}</td>
                                        <td>{{ rupiah(array_sum($jp1_cabang)) }}</td>
                                        <td>{{ rupiah(array_sum($jp2_cabang)) }}</td>
                                        <td>{{ rupiah((array_sum($jp1_cabang) + array_sum($jp2_cabang))) }}</td>

                                        @php
                                            array_push($total_jamsostek, (((0.0024 * array_sum($total_gaji_cabang))) + ((0.057 * array_sum($total_gaji_cabang)))) + ((0.003 * array_sum($total_gaji_cabang))));
                                            array_push($total_jkk, ((0.0024 * array_sum($total_gaji_cabang))));
                                            array_push($total_jht, ((0.057 * array_sum($total_gaji_cabang))));
                                            array_push($total_jkm, ((0.003 * array_sum($total_gaji_cabang))));

                                            array_push($total_jp, (array_sum($jp1_cabang) + array_sum($jp2_cabang)));
                                            array_push($total_jp1, array_sum($jp1_cabang));
                                            array_push($total_jp2, array_sum($jp2_cabang));
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="font-weight: bold">
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        Jumlah
                                    </td>
                                    @php
                                        $total_karyawan = DB::table('mst_karyawan')->get();
                                    @endphp
                                    <td style="text-align: center;">{{ count($total_karyawan) }}</td>
                                    <td style="text-align: center;">{{ rupiahJkk(array_sum($total_jkk)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jht)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jkm)) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($total_jamsostek)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jp1)) }}</td>
                                    <td style="text-align: center;">{{ rupiah(array_sum($total_jp2)) }}</td>
                                    <td style="background-color: #FED049; text-align: center;">{{ rupiah(array_sum($total_jp)) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="9" style="text-align: center;">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)</td>
                                    <td style="background-color: #54B435; text-align: center;">{{ rupiah((array_sum($total_jamsostek) + array_sum($total_jp))) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @elseif($status == 2)
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="table_export" style="width: 100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="background-color: #CCD6A6">NIP</th>
                                    <th rowspan="2" style="background-color: #CCD6A6">Nama Karyawan</th>
                                    <th colspan="4" style="background-color: #CCD6A6">JAMSOSTEK</th>
                                    <th rowspan="2" style="background-color: #CCD6A6">JP(1%)</th>
                                    <th rowspan="2" style="background-color: #CCD6A6">JP(2%)</th>
                                    <th rowspan="2" style="background-color: #CCD6A6">Total JP</th>
                                </tr>                                   
                                <tr style="background-color: #DAE2B6">
                                    <th>JKK</th>
                                    <th>JHT</th>
                                    <th>JKM</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($karyawan); $i++)
                                    <tr>
                                        <td>
                                            {{ $karyawan[$i]->nip }}
                                        </td>
                                        <td>
                                            {{ $karyawan[$i]->nama_karyawan }}
                                        </td>
                                        <td>
                                            {{ rupiahJkk(($jkk[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jht[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jkm[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jkk[$i] + $jht[$i] + $jkm[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp1[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp2[$i])) }}
                                        </td>
                                        <td>
                                            {{ rupiah(($jp1[$i] + $jp2[$i])) }}
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot style="font-weight: bold; text-align: center;">
                                <tr>
                                    <td colspan="2">Jumlah</td>
                                    <td>{{ rupiah(array_sum($jkk)) }}</td>
                                    <td>{{ rupiah(array_sum($jht)) }}</td>
                                    <td>{{ rupiah(array_sum($jkm)) }}</td>
                                    <td style="background-color: #FED049">{{ rupiah((array_sum($jkm) + array_sum($jht) + array_sum($jkm))) }}</td>
                                    <td>{{ rupiah((array_sum($jp1))) }}</td>
                                    <td>{{ rupiah((array_sum($jp2))) }}</td>
                                    <td style="background-color: #FED049">{{ rupiah((array_sum($jp1) + array_sum($jp2))) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="8">(Total Jamsostek) + (Total JP 1%) + (Total JP 2%)</td>
                                    <td style="background-color: #54B435">{{ rupiah((array_sum($jkm) + array_sum($jht) + array_sum($jkm)) + (array_sum($jp1) + array_sum($jp2))) }}</td>
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
        $("#clear").click(function(e){
            $("#row-baru").empty()
        })

        $("#kategori").change(function(e){
            var value = $(this).val();
            $("#kantor_col").empty();
            console.log(value);
            if(value == 2){
                $("#kantor_col").addClass("col-md-4");
                $("#kantor_col").append(`
                <div class="form-group">
                        <label for="">Kantor</label>
                        <select name="kantor" class="form-control" id="kantor">
                            <option {{ old('kantor') == '-' ? 'selected' : '' }} value="-">--- Pilih Kantor ---</option>
                            <option {{ old('kantor') == 'Pusat' ? 'selected' : '' }} value="Pusat">Pusat</option>
                            <option {{ old('kantor') == 'Cabang' ? 'selected' : '' }} value="Cabang">Cabang</option>
                        </select>
                    </div>
                `)


                $("#kantor").change(function(e){
                    var value = $(this).val();
                    if(value == 'Cabang'){
                        $.ajax({
                            type: "GET",
                            url: '/getcabang',
                            datatype: 'JSON',
                            success: function(res){
                                $('#cabang_col').addClass("col-md-4");
                                $("#cabang_col").empty();
                                $("#cabang_col").append(`
                                        <div class="form-group">
                                            <label for="Cabang">Cabang</label>
                                            <select name="cabang" id="cabang" class="form-control">
                                                <option value="">--- Pilih Cabang ---</option>
                                            </select>
                                        </div>`
                                );

                                $("#kantor_row3").hide()
                                $.each(res[0], function(i, item){
                                    $('#cabang').append('<option {{ old('cabang') == '+item.kd_cabang+' ? 'selected' : '' }} value="'+item.kd_cabang+'">'+item.kd_cabang + ' - ' +item.nama_cabang+'</option>')
                                })
                            }
                        })
                    }else {
                        $("#cabang_col").removeClass("col-md-4");
                        $("#cabang_col").empty();
                    }
                })
            } else{
                $("#kantor_col").removeClass("col-md-4")
                $("#kantor_col").empty()
            }
        })

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
        
        document.getElementById('btn_export').addEventListener('click', function(){
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll('#table_export'));
        });
    </script>
@endsection
