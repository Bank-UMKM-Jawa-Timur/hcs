@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Laporan BPJS</h5>
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
                            <option value="">--- Pilih Kategori ---</option>
                            <option value="1">Rekap Keseluruhan</option>
                            <option value="2">Rekap Kantor / Cabang</option>
                        </select>
                    </div>
                </div>
                <div id="kantor_col">
                </div>
                <div id="cabang_col">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-info" type="submit">cari data</button>
                </div>
            </div>
        </form>
        <div class="row m-0" id="row-baru">
            @if ($status != null)
                @php
                    function rupiah($angka){
                        $hasil_rupiah = number_format($angka, 0, ",", ".");
                        return $hasil_rupiah;
                    }
                @endphp
                @if ($status == 1)
                    <div class="row m-0">
                        <div class="col-md-4">
                            <button class="btn btn-info" type="button">Export</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger" type="button" id="clear">Clear</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center" id="table">
                            @php
                                $a = 1;
                            @endphp
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
                                    <td>{{ count($data_pusat) }}</td>
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
                                                    
                                                array_push($total_gaji_cabang, ($data_gaji + $i->gj_pokok));
                                            }
                                            foreach($total_gaji_cabang as $i){
                                                array_push($jp1_cabang, ((($i >  9077600) ?  9077600 * 0.01 : $i * 0.01)));
                                                array_push($jp2_cabang, ((($i >  9077600) ?  9077600 * 0.02 : $i * 0.02)));
                                            }
                                        @endphp
                                        <td>{{ count($karyawan) }}</td>
                                        <td>{{ rupiah(((0.0024 * $item->nominal))) }}</td>
                                        <td>{{ rupiah(((0.057 * $item->nominal))) }}</td>
                                        <td>{{ rupiah(((0.003 * $item->nominal))) }}</td>
                                        <td>{{ rupiah((((0.0024 * $item->nominal)) + ((0.057 * $item->nominal))) + ((0.003 * $item->nominal))) }}</td>
                                        <td>{{ rupiah(array_sum($jp1_cabang)) }}</td>
                                        <td>{{ rupiah(array_sum($jp2_cabang)) }}</td>
                                        <td>{{ rupiah((array_sum($jp1_cabang) + array_sum($jp2_cabang))) }}</td>

                                        @php
                                            array_push($total_jamsostek, (((0.0024 * $item->nominal)) + ((0.057 * $item->nominal))) + ((0.003 * $item->nominal)));
                                            array_push($total_jkk, ((0.0024 * $item->nominal)));
                                            array_push($total_jht, ((0.057 * $item->nominal)));
                                            array_push($total_jkm, ((0.003 * $item->nominal)));

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
                    <div class="row m-0">
                        <div class="col-md-4 m-0">
                            <button class="btn btn-info" type="button">Export</button>
                        </div>
                        <div class="col-md-4 m-0">
                            <button class="btn btn-danger" type="button" id="clear">Clear</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center" id="table">
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
@endsection

@section('custom_script')
    <script>
        // $(document).ready( function () {
        //     $('#table').DataTable();
        // });
        // $.each('.uang', function(i, item){
        //     item.text(formatRupiah(item.text()))
        // })

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
                            <option value="">--- Pilih Kantor ---</option>
                            <option value="Pusat">Pusat</option>
                            <option value="Cabang">Cabang</option>
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
                                    $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.kd_cabang + ' - ' +item.nama_cabang+'</option>')
                                })
                            }
                        })
                    }else {
                        $("#cabang_col").removeClass("col-md-4");
                        $("#cabang_col").empty();
                    }
                })
            }
        })

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
    </script>
@endsection