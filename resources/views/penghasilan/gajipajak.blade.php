<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export Laporan Jamsostek</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
  <body>

    <div class="container mt-3 mb-3" style="min-width: 1250px" id="row-baru">
        <div class="card">
            <div class="card-header">
                    <h5 class="card-title text-center">GAJI PAJAK</h5>
                    <h5 class="card-title text-center">BANK UMKM JAWA TIMUR</h5>
            </div>
            @php
                $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
   
                function rupiah($angka){
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
            @endphp
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table stripe" style="min-width: 1350px" id="table_export" style="width: 100%">
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title">PENGHASILAN TERATUR</h5>
                                <table class="table text-center cell-border stripe" style="width: 100%">
                                    <thead>
                                        @php
                                            $total_kuning = array();
                                            $total_nonkuning = array();
                                        @endphp
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Gaji Pokok</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">JAMSOSTEK</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Makan</th>
                                            <th colspan="8" style="background-color: #CCD6A6">Tunjangan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Pulsa</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Vitamin</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Transport</th>
                                        </tr>                                   
                                        <tr style="background-color: #DAE2B6">
                                            <th>Klrg</th>
                                            <th>Jbtn</th>
                                            <th>Pysn</th>
                                            <th>Prmhn</th>
                                            <th>Lst Air</th>
                                            <th>Plksn</th>
                                            <th>Kmhln</th>
                                            <th>Ksjhtn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                <td>{{ rupiah($gj_pokok[$i]) }}</td>
                                                <td>{{ rupiah($jamsostek[$i]) }}</td>
                                                <td>{{ ($tunjangan[$i][10] != 0) ? rupiah($tunjangan[$i][10]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][0] != 0) ? rupiah($tunjangan[$i][0]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($penyesuaian[$i] != 0) ? rupiah($penyesuaian[$i]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][2] != 0) ? rupiah($tunjangan[$i][2]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][3] != 0) ? rupiah($tunjangan[$i][3]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][1] != 0) ? rupiah($tunjangan[$i][1]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][5] != 0) ? rupiah($tunjangan[$i][5]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][4] != 0) ? rupiah($tunjangan[$i][4]) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($tunjangan[$i][6] != 0) ? rupiah($tunjangan[$i][6]) : '-' }}</td>
                                                <td>{{ ($tunjangan[$i][8] != 0) ? rupiah($tunjangan[$i][8]) : '-' }}</td>
                                                <td>{{ ($tunjangan[$i][9] != 0) ? rupiah($tunjangan[$i][9]) : '-' }}</td>
                                                <td>{{ ($tunjangan[$i][7] != 0) ? rupiah($tunjangan[$i][7]) : '-' }}</td>
                                                @for ($j = 0; $j < 11; $j++)
                                                    @php
                                                        if ($j == 10 || $j == 8 || $j == 9 || $j == 7){
                                                            array_push($total_nonkuning, $tunjangan[$i][$j]);
                                                            array_push($total_nonkuning, $penyesuaian[$i]);
                                                        } else {
                                                            array_push($total_kuning, $tunjangan[$i][$j]);
                                                        }
                                                    @endphp
                                                @endfor
                                                @php
                                                    array_push($total_nonkuning, $gj_pokok[$i]);
                                                @endphp
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="6">
                                                Total Tunjangan + Keseluruhan
                                            </td>
                                            <td style="background-color: #FED049" colspan="5">{{ rupiah(array_sum($total_kuning)) }}</td>
                                            <td style="background-color: #54B435" colspan="4">{{ rupiah(array_sum($total_nonkuning)) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title mt-5">PENGHASILAN TIDAK TERATUR</h5>
                                <table class="table text-center cell-border stripe" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Lembur</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">BP Kesehatan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Uang Duka</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD Pendidikan </th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD Pindah Tugas</th>
                                        </tr>                                   
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_penghasilan = array();
                                        @endphp
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                @for ($j = 0; $j < 6; $j++)
                                                    <td>{{ ($penghasilan[$i][$j] != 0) ? rupiah($penghasilan[$i][$j]) : '-' }}</td>
                                                    @php
                                                        array_push($total_penghasilan, $penghasilan[$i][$j]);
                                                    @endphp
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="4">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="2">{{ rupiah(array_sum($total_penghasilan)) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title mt-5">BONUS</h5>
                                <table class="table text-center cell-border stripe" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Tunjangan Hari Raya</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Jasa Produksi</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Dana Pendidikan</th>
                                        </tr>                                   
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_bonus =array();
                                        @endphp
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                @for ($j = 0; $j < 3; $j++)
                                                    <td>{{ ($bonus[$i][$j] != 0) ? rupiah($bonus[$i][$j]) : '-' }}</td>
                                                    @php
                                                        array_push($total_bonus, $bonus[$i][$j]);
                                                    @endphp
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="2">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="2">{{ rupiah(array_sum($total_bonus)) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
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
  </body>
</html>