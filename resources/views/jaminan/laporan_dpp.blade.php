<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export Laporan BPJS</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>	
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>

    <div class="row m-0 mt-3" id="row-baru">
        <div class="col-lg-12">
            <div class="container mt-3">
                @if ($status != null)
                    @php
                        function rupiah($angka){
                            $hasil_rupiah = number_format($angka, 0, ",", ",");
                            return $hasil_rupiah;
                        }
                    @endphp
                    @if ($status == 1)
                        <div class="table-responsive">
                            <table class="table text-center" id="table_export">
                                <thead>
                                    <th>Kode Kantor</th>
                                    <th>Nama Kantor</th>
                                    <th>DPP</th>
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
                                                    $data_tunjangan_keluarga = DB::table('tunjangan_karyawan')
                                                        ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                                                        ->where('nip', $i->nip)
                                                        ->where('mst_tunjangan.id', 1)
                                                        ->sum('nominal');

                                                    $data_tunjangan_kesejahteraan = DB::table('tunjangan_karyawan')
                                                        ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                                                        ->where('nip', $i->nip)
                                                        ->where('mst_tunjangan.id', 8)
                                                        ->sum('nominal');
                                                        
                                                    array_push($total_tunjangan_keluarga, $data_tunjangan_keluarga);
                                                    array_push($total_tunjangan_kesejahteraan, $data_tunjangan_kesejahteraan);
                                                    array_push($total_gj_cabang, ($i->gj_pokok));
                                                }

                                                $gj_cabang = (array_sum($total_gj_cabang) + array_sum($total_tunjangan_keluarga) + (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13;
                                                
                                                array_push($total_dpp, $gj_cabang);
                                            @endphp
                                            <td>{{ rupiah($gj_cabang) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            Jumlah
                                        </td>
                                        <td>{{ rupiah(array_sum($total_dpp)) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @elseif($status == 2)
                        <div class="table-responsive">
                            <table class="table text-center" id="table_export">
                                <thead>
                                    <th>NIP</th>
                                    <th>Nama Karyawan</th>
                                    <th>DPP</th>
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
                                <tfoot>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td>{{ array_sum($dpp) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                    
                @endif
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
                    title: 'Bank UMKM Jawa Timur',
                    text:'Excel' 
                }
            ]
        });

        $(".buttons-excel").attr("class","btn btn-success");

        document.getElementById('btn_export').addEventListener('click', function(){
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll('#table_export'));
        });
    </script>
  </body>
</html>