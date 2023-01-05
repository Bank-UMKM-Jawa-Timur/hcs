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

    <div class="container mt-3 mb-3" style="min-width: 1800px" id="row-baru">
        <div class="card">
            <div class="card-header">
                    <h5 class="card-title text-center">GAJI PAJAK</h5>
                    <h5 class="card-title text-center">BANK UMKM JAWA TIMUR</h5>
                    <h5 class="card-title text-center">{{ $tahun }}</h5>
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
                    <table class="table stripe" id="table_export" style="width: 100%">
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title">PENGHASILAN TERATUR</h5>
                                <table class="table text-center cell-border stripe" style="width: 100%">
                                    <thead>
                                        @php
                                            $total_k = null;
                                            $total_non = null;
                                        @endphp
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 100px">Bulan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 100px">Gaji Pokok</th>
                                            <th colspan="8" style="background-color: #CCD6A6">Tunjangan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">JAMSOSTEK</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px">Tunjangan <br> Uang Pulsa</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px">Tunjangan <br> Uang Vitamin</th>
                                            <th rowspan="2" style="background-color: #CCD6A6; min-width: 120px">Tunjangan <br> Uang Transport</th>
                                        </tr>                                   
                                        <tr style="background-color: #DAE2B6">
                                            <th>Keluarga</th>
                                            <th>Jabatan</th>
                                            <th>penyesuaian</th>
                                            <th>Perumahan</th>
                                            <th style="min-width: 100px">Listrik & Air</th>
                                            <th>Pelaksana</th>
                                            <th>Kemahalan</th>
                                            <th>Kesejahteraan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr>
                                                <td>{{ $bulan[$i] }}</td>
                                                <td>{{ ($gj[$i]['gj_pokok'] != 0) ? rupiah($gj[$i]['gj_pokok']) : '-' }}</td>
                                                <td style="background-color: #FED049">{{ ($gj[$i]['tj_keluarga'] != 0) ? rupiah($gj[$i]['tj_keluarga']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_jabatan'] != 0) ? rupiah($gj[$i]['tj_jabatan']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['gj_penyesuaian'] != 0) ? rupiah($gj[$i]['gj_penyesuaian']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_perumahan'] != 0) ? rupiah($gj[$i]['tj_perumahan']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_telepon'] != 0) ? rupiah($gj[$i]['tj_telepon']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_pelaksana'] != 0) ? rupiah($gj[$i]['tj_pelaksana']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_kemahalan'] != 0) ? rupiah($gj[$i]['tj_kemahalan']) : '-' }}</td>
                                                <td style="background-color: #FED049" >{{ ($gj[$i]['tj_kesejahteraan'] != 0) ? rupiah($gj[$i]['tj_kesejahteraan']) : '-' }}</td>
                                                <td>{{ ($jamsostek[$i] != 0) ?rupiah($jamsostek[$i]) : '-' }}</td>
                                                <td>{{ ($gj[$i]['tj_pulsa'] != 0) ? rupiah($gj[$i]['tj_pulsa']) : '-' }}</td>
                                                <td>{{ ($gj[$i]['tj_vitamin'] != 0) ? rupiah($gj[$i]['tj_vitamin']) : '-' }}</td>
                                                <td>{{ ($gj[$i]['uang_makan'] != 0) ? rupiah($gj[$i]['uang_makan']) : '-' }}</td>
                                                @php
                                                    $total_k += $gj[$i]['tj_keluarga'] + $gj[$i]['tj_jabatan'] + $gj[$i]['gj_penyesuaian'] + $gj[$i]['tj_perumahan'] + $gj[$i]['tj_telepon'] + $gj[$i]['tj_pelaksana'] + $gj[$i]['tj_kemahalan'] +$gj[$i]['tj_kesejahteraan'];
                                                    $total_non += $gj[$i]['gj_pokok'] + $gj[$i]['tj_pulsa'] + $gj[$i]['tj_pulsa'] + $gj[$i]['uang_makan'];
                                                @endphp
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="2">
                                                Total Tunjangan + Keseluruhan
                                            </td>
                                            <td style="background-color: #FED049" colspan="9">{{ rupiah($total_k) }}</td>
                                            <td style="background-color: #54B435" colspan="5">{{ rupiah($total_non) }}</td>
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
                                            <th rowspan="2" style="background-color: #CCD6A6">Tunjangan Uang Lembur</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Pengganti Biaya Kesehatan</th>
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
                                            <td colspan="1">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="6">{{ rupiah(array_sum($total_penghasilan)) }}</td>
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
                                            <td colspan="1">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="3">{{ rupiah(array_sum($total_bonus)) }}</td>
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
        $(document).ready(function () {
            $("#table_export").DataTable({
                scrollX: true,
            });
            
        });       
        
        $(".buttons-excel").attr("class","btn btn-success mb-2");
        
        document.getElementById('btn_export').addEventListener('click', function(){
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll('#table_export'));
        });
    </script>
  </body>
</html>