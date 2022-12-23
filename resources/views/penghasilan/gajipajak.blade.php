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
                    <h5 class="card-title text-center" id="bulan">JANUARI</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table stripe" id="table_export" style="width: 100%">
                        <tr>
                            <td class="p-0">
                                <h5 class="card-title">PENGHASILAN TERATUR</h5>
                                <table class="table text-center cell-border stripe" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="background-color: #CCD6A6">Gaji Pokok</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">JAMSOSTEK</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Makan</th>
                                            <th colspan="9" style="background-color: #CCD6A6">Tunjangan</th>
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
                                            <th>Khss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td style="background-color: #FED049">125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="6">
                                                Total Tunjangan + Keseluruhan
                                            </td>
                                            <td style="background-color: #FED049" colspan="5">625.000</td>
                                            <td style="background-color: #54B435" colspan="4">1.375.000</td>
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
                                            <th rowspan="2" style="background-color: #CCD6A6">T. Lembur</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">BP Kesehatan</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Uang Duka</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD Pendidikan </th>
                                            <th rowspan="2" style="background-color: #CCD6A6">SPD Pindah Tugas</th>
                                        </tr>                                   
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="4">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="2">750.000</td>
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
                                            <th rowspan="2" style="background-color: #CCD6A6">Tunjangan Hari Raya</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Jasa Produksi</th>
                                            <th rowspan="2" style="background-color: #CCD6A6">Dana Pendidikan</th>
                                        </tr>                                   
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                            <td>125.000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="font-weight: bold">
                                        <tr>
                                            <td colspan="2">
                                                Total
                                            </td>
                                            <td style="background-color: #54B435" colspan="2">375.000</td>
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