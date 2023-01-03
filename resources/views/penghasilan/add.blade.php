@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Pajak Penghasilan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('penghasilan.index') }}">Pajak Penghasilan </a> > Tambah</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('penghasilan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="accordion">
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingOne">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Data Karyawan</a>
                        </h6>
                    </div>

                    <div class="row m-0 pb-3 col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">NIP</label>
                                <input type="text" class="@error('nip') is-invalid @enderror form-control" name="nip" id="nip" value="{{ old('nip') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama Karyawan</label>
                                <input type="text" id="nama" class="form-control" disabled>
                            </div>
                        </div>
                        @php
                            $already_selected_value = date('y');
                            $earliest_year = 2022;
                        @endphp
                    <div class="col-md-6">
                        <label for="tahun">Tahun</label>
                        <div class="form-group">
                            <select name="tahun" class="form-control">
                                <option value="">--- Pilih Tahun ---</option>
                                @foreach (range(date('Y'), $earliest_year) as $x)
                                    <option value="{{ $x }}">{{ $x }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Bulan">Bulan</label>
                            <select name="bulan" class="form-control">
                                <option value="">--- Pilih Bulan ---</option>
                                <option value='1'>Januari</option>
                                <option value='2'>Februari </option>
                                <option value='3'>Maret</option>
                                <option value='4'>April</option>
                                <option value='5'>Mei</option>
                                <option value='6'>Juni</option>
                                <option value='7'>Juli</option>
                                <option value='8'>Agustus</option>
                                <option value='9'>September</option>
                                <option value='10'>Oktober</option>
                                <option value='11'>November</option>
                                <option value='12'>Desember</option>
                            </select>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingTwo">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <a class="text-decoration-none" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Penghasilan Teratur</a>
                        </h6>
                    </div>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Gaji Pokok</th>
                                            <th>Tj. Keluarga</th>
                                            <th>Penyesuaian</th>
                                            <th>Tj. Perumahan</th>
                                            <th>Tj. Listrik</th>
                                            <th>Tj. Pelaksana</th>
                                            <th>Tj. Kemahalan</th>
                                            <th>Tj. Kesejahteraan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-teratur">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_teratur[]" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($tj as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="nominal_teratur[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow" id="data_is">
                    <div class="card-header" id="headingThree">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Penghasilan Tidak Teratur</a>
                        </h6>
                    </div>

                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_tidak_teratur[]" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($tidak_teratur as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="nominal_tidak_teratur[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>         
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingFour">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">Bonus</a>
                        </h6>
                    </div>

                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_bonus[]" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($bonus as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="nominal_bonus[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <button type="submit" id="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        var x =1;
        var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        function rupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

        function getTunjangan(nip){
            $.ajax({
                type: "get",
                url: "{{ route('getPenghasilanResult') }}?nip="+nip,
                datatype: "json",
                success: function(res){
                    for(var i =0; i < 12; i++){
                        $("#tbl-teratur").append(`
                            <tr>
                                <td>
                                    `+bulan[i] +`    
                                </td>
                                <td>`+(res.gj[i]['gj_pokok'] != 0) ? (res.gj[i]['gj_pokok']) : '-'+`</td>
                                <td style="background-color: #FED049">`+(res.gj[i]['tj_keluarga'] != 0) ? (res.gj[i]['tj_keluarga']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_jabatan'] != 0) ? (res.gj[i]['tj_jabatan']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['gj_penyesuaian'] != 0) ? (res.gj[i]['gj_penyesuaian']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_perumahan'] != 0) ? (res.gj[i]['tj_perumahan']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_telepon'] != 0) ? (res.gj[i]['tj_telepon']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_pelaksana'] != 0) ? (res.gj[i]['tj_pelaksana']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_kemahalan'] != 0) ? (res.gj[i]['tj_kemahalan']) : '-'+`</td>
                                <td style="background-color: #FED049" >`+(res.gj[i]['tj_kesejahteraan'] != 0) ? (res.gj[i]['tj_kesejahteraan']) : '-'+`</td>
                                <td>`+(res.gj[i]['tj_pulsa'] != 0) ? (res.gj[i]['tj_pulsa']) : '-'+`</td>
                                <td>`+(res.gj[i]['tj_vitamin'] != 0) ? (res.gj[i]['tj_vitamin']) : '-'+`</td>
                                <td>`+(res.gj[i]['uang_makan'] != 0) ? (res.gj[i]['uang_makan']) : '-'+`</td>
                            </tr>
                        `)
                    }
                }
            })
        }

        $('#collapseTwo').on('click', "#btn-add", function(){
            $('#collapseTwo').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_teratur[]" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tj as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for=""">Nominal</label>
                                    <input type="number" id="" name="nominal_teratur[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseTwo').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })

        $('#collapseThree').on('click', "#btn-add", function(){
            $('#collapseThree').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_tidak_teratur[]" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tidak_teratur as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="nominal_tidak_teratur[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseThree').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })

        $('#collapseFour').on('click', "#btn-add", function(){
            $('#collapseFour').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="id_bonus[]" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                        @foreach ($bonus as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="nominal_bonus[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
            `);
            x++
        });

        $('#collapseFour').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })

        $("#nip").change(function(e){
            var nip = $(this).val();
            console.log(nip);

            $.ajax({
                type: "GET",
                url: '/getdatapromosi?nip='+nip,
                datatype: "json",
                success: function(res){
                    $("#nama").val(res.nama_karyawan)
                    getTunjangan(nip)
                }
            })
        })
        
    </script>
@endsection