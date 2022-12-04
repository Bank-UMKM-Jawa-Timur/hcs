@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data BPJS</h5>
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
                        <table class="table overflow-scroll" id="table">
                            @php
                                $a = 1;
                            @endphp
                            <thead>
                                <th>No</th>
                                <th>Kode Kantor</th>
                                <th>Nama Kantor</th>
                                <th>JKK</th>
                                <th>JHT</th>
                                <th>JKM</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $a++ }}</td>
                                    <td>-</td>
                                    <td>Kantor Pusat</td>
                                    <td>{{ round((0.0024 * $data_pusat)) }}</td>
                                    <td>{{ round((0.057 * $data_pusat)) }}</td>
                                    <td>{{ round((0.003 * $data_pusat)) }}</td>
                                    <td>{{ (round((0.0024 * $data_pusat)) + round((0.057 * $data_pusat))) + round((0.003 * $data_pusat)) }}</td>
                                </tr>
                                @foreach ($data_cabang as $item)
                                    @php
                                        $nama_cabang = DB::table('mst_cabang')
                                            ->where('kd_cabang', $item->kd_entitas)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $a++ }}</td>
                                        <td>{{ $item->kd_entitas }}</td>
                                        <td>{{ $nama_cabang->nama_cabang }}</td>
                                        <td>{{ round((0.0024 * $item->nominal)) }}</td>
                                        <td>{{ round((0.057 * $item->nominal)) }}</td>
                                        <td>{{ round((0.003 * $item->nominal)) }}</td>
                                        <td>{{ (round((0.0024 * $item->nominal)) + round((0.057 * $item->nominal))) + round((0.003 * $item->nominal)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
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
                        <table class="table overflow-scroll" id="table">
                            @php
                                $a = 1;
                            @endphp
                            <thead>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>JKK</th>
                                <th>JHT</th>
                                <th>JKM</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($karyawan); $i++)
                                    <tr>
                                        <td>
                                            {{ $a++ }}
                                        </td>
                                        <td>
                                            {{ $karyawan[$i]->nip }}
                                        </td>
                                        <td>
                                            {{ $karyawan[$i]->nama_karyawan }}
                                        </td>
                                        <td class="uang">
                                            {{ round($jkk[$i]) }}
                                        </td>
                                        <td class="uang">
                                            {{ round($jht[$i]) }}
                                        </td>
                                        <td class="uang">
                                            {{ round($jkm[$i]) }}
                                        </td>
                                        <td>
                                            {{ round($jkk[$i] + $jht[$i] + $jkm[$i]) }}
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
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
        //     item.val(formatRupiah(item.val(), 'Rp. '));
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

        // function formatRupiah(angka, prefix){
        //     var number_string = angka.replace(/[^,\d]/g, '').toString(),
        //     split = number_string.split(','),
        //     sisa = split[0].length % 3,
        //     rupiah = split[0].substr(0, sisa),
        //     ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
        //     // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
        //     if(ribuan){
        //         separator = sisa ? '.' : '';
        //         rupiah += separator + ribuan.join('.');
        //     }
 
        //     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        //     return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        // }
    </script>
@endsection