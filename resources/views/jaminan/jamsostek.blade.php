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
                                    <th>Jumlah Pegawai</th>
                                    <th>JKK</th>
                                    <th>JHT</th>
                                    <th>JKM</th>
                                    <th>Total</th>
                                    <th>JP(1%)</th>
                                    <th>JP(2%)</th>
                                    <th>Total JP</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>-</td>
                                        <td>Kantor Pusat</td>
                                        <td>{{ $count_pusat }}</td>
                                        <td>{{ rupiah(((0.0024 * $total_gaji_pusat))) }}</td>
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
                                            <td>{{ rupiah(((0.0024 * array_sum($total_gaji_cabang)))) }}</td>
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
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            Jumlah
                                        </td>
                                        @php
                                            $total_karyawan = DB::table('mst_karyawan')->get();
                                        @endphp
                                        <td>{{ count($total_karyawan) }}</td>
                                        <td>{{ rupiah(array_sum($total_jkk)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jht)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jkm)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jamsostek)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jp1)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jp2)) }}</td>
                                        <td>{{ rupiah(array_sum($total_jp   )) }}</td>
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
                                    <th>JKK</th>
                                    <th>JHT</th>
                                    <th>JKM</th>
                                    <th>Total</th>
                                    <th>JP(1%)</th>
                                    <th>JP(2%)</th>
                                    <th>Total JP</th>
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
                                                {{ rupiah(($jkk[$i])) }}
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
                                <tfoot>
                                    <tr>
                                        <td colspan="2">Jumlah</td>
                                        <td>{{ rupiah(array_sum($jkk)) }}</td>
                                        <td>{{ rupiah(array_sum($jht)) }}</td>
                                        <td>{{ rupiah(array_sum($jkm)) }}</td>
                                        <td>{{ rupiah((array_sum($jkm) + array_sum($jht) + array_sum($jkm))) }}</td>
                                        <td>{{ rupiah((array_sum($jp1))) }}</td>
                                        <td>{{ rupiah((array_sum($jp2))) }}</td>
                                        <td>{{ rupiah((array_sum($jp1) + array_sum($jp2))) }}</td>
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
