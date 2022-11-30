@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/data_karyawan">Karyawan</a> > Edit</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('karyawan.update', $data->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            @method('PUT')
            <div id="accordion">
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingOne">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Biodata Diri Karyawan</a>
                        </h6>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIP</label>
                                    <input type="text" class="form-control" name="nip" id="nip" value="{{ $data->nip }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="text" class="form-control" name="nik" id="" value="{{ $data->nik }}">
                                </div>
                            </div>   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nama Karyawan</label>
                                    <input type="text" class="form-control" name="nama" id="" value="{{ $data->nama_karyawan }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tempat Lahir</label>
                                    <input type="text" class="form-control" name="tmp_lahir" id="" value="{{ $data->tmp_lahir }}">
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tgl_lahir" id="" value="{{ $data->tgl_lahir }}">
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Agama</label>
                                    <select name="agama" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($agama as $item)
                                            <option value="{{ $item->kd_agama }}" {{ ($data->kd_agama == $item->kd_agama) ? 'selected' : '' }}>{{ $item->agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jk" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        <option value="Laki-laki" {{ ($data->jk == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ ($data->jk == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status pernikahan</label>
                                    <select name="status_pernikahan" id="status" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        <option value="Kawin" {{ ($data->status == 'Kawin') ? 'selected' : '' }}>Kawin</option>
                                        <option value="Belum Kawin" {{ ($data->status == 'Belum Kawin') ? 'selected' : '' }}>Belum Kawin</option>
                                        <option value="Janda" {{ ($data->status == 'Janda') ? 'selected' : '' }}>Janda</option>
                                        <option value="Duda" {{ ($data->status == 'Duda') ? 'selected' : '' }}>Duda</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id_is" value="{{ $data->id_is }}">
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Kewarganegaraan</label>
                                    <input type="text" name="kewarganegaraan" id="" class="form-control" value="{{ $data->kewarganegaraan }}">
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat KTP</label>
                                    <textarea name="alamat_ktp" id="" class="form-control">{{ $data->alamat_ktp }}</textarea>
                                </div>
                            </div>    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat sekarang</label>
                                    <textarea name="alamat_sek" id="" class="form-control">{{ $data->alamat_sek }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingTwo">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <a class="text-decoration-none" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Data Status Karyawan</a>
                        </h6>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">KPJ</label>
                                    <input type="text" class="form-control" name="kpj" value="{{ $data->kpj }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">JKN</label>
                                    <input type="text" class="form-control" name="jkn" value="{{ $data->jkn }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gaji Pokok</label>
                                    <input type="text" class="form-control" name="gj_pokok" value="{{ $data->gj_pokok }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gaji Penyesuaian</label>
                                    <input type="text" class="form-control" name="gj_penyesuaian" value="{{ $data->gj_penyesuaian }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Status Karyawan</label>
                                    <select name="status_karyawan" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        <option value="Tetap" {{ ($data->status_karyawan == 'Tetap') ? 'selected' : ''}}>Tetap</option>
                                        <option value="IKJP" {{ ($data->status_karyawan == 'IKJP') ? 'selected' : ''}}>IKJP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">SK Pengangkatan</label>
                                    <input type="text" class="form-control" name="skangkat" value="{{ $data->skangkat }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Pengangkatan</label>
                                    <input type="date" class="form-control" name="tanggal_pengangkat" value="{{ $data->tanggal_pengangkat }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 ml-3 mr-3 shadow" id="data_is">
                    <div class="card-header" id="headingThree">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Data Suami / Istri</a>
                        </h6>
                    </div>

                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is">Pasangan</label>
                                    <select name="is" id="is" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        <option value="Suami">Suami</option>
                                        <option value="Istri">Istri</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_nama">Nama</label>
                                    <input type="text" name="is_nama" id="is_nama" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_tgl_lahir">Tanggal Lahir</label>
                                    <input type="date" name="is_tgl_lahir" class="form-control" id="is_tgl_lahir">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="is_alamat">Alamat</label>
                                <textarea name="is_alamat" class="form-control" id="is_alamat"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="is_pekerjaan">Pekerjaan</label>
                                <input type="text" class="form-control" name="is_pekerjaan" id="is_pekerjaan">
                            </div>
                            <div class="col-md-6">
                                <label for="is_jumlah_anak">Jumlah Anak</label>
                                <input type="number" class="form-control" name="is_jml_anak" id="is_jml_anak">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingFour">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">Data Tunjangan</a>
                        </h6>
                    </div>

                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        @if (count($data->tunjangan) < 1)
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is">Tunjangan</label>
                                    <select name="tunjangan[]" id="tunjangan" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is_nama">Nominal</label>
                                    <input type="number" id="nominal" name="nominal_tunjangan[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    +
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    -
                                </button>
                            </div>
                        </div>
                        @endif
                        @foreach ($data->tunjangan as $tj)
                            <div class="row m-0 pb-3 col-md-12" id="row_tunjangan">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="is">Tunjangan</label>
                                        <select name="tunjangan[]" id="tunjangan" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                            @foreach ($tunjangan as $item)
                                                <option value="{{ $item->id }}" {{ ($item->id == $tj->id_tunjangan) ? 'selected' : '' }}>{{ $item->nama_tunjangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id_tk[]" id="id_tk" value="{{ $tj->id }}">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="is_nama">Nominal</label>
                                        <input type="number" id="nominal" name="nominal_tunjangan[]" class="form-control" value="{{ $tj->nominal }}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info" type="button" id="btn-add">
                                        +
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info" type="button" id="btn-delete">
                                        -
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>  

            <div class="row m-3">
                <button type="submit" class="btn btn-info">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        let status = $("#status");
        var nip = $("#nip").val()
        var x = {{ $data->count_tj }};
        cekStatus();
        console.log(x);

        function cekStatus(){
            if(status.val() == "Kawin"){
                $('#data_is').show();

                $.ajax({
                    type: "GET",
                    url: "/getis?nip="+nip,
                    datatype: "json",
                    success: function(res){
                        if(res == null){
                        } else{
                            $('#is').val(res.enum);
                            $('#is_nama').val(res.is_nama);
                            $('#is_tgl_lahir').val(res.is_tgl_lahir);
                            $('#is_alamat').val(res.is_alamat);
                            $('#is_pekerjaan').val(res.is_pekerjaan);
                            $('#is_jml_anak').val(res.is_jml_anak);
                        }
                    }
                })
            }else{
                $('#data_is').hide();
            }
        }

        $('#status').change(function(){
            var stat = $(this).val();

            if(stat == 'Kawin'){
                $('#data_is').show();
            } else{
                $('#data_is').hide();
            }
        })

        $('#collapseFour').on('click', "#btn-add", function(){
            $('#collapseFour').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is">Tunjangan</label>
                                    <select name="tunjangan[]" id="tunjangan" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is_nama">Nominal</label>
                                    <input type="number" id="nominal" name="nominal_tunjangan[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    +
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    -
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseFour').on('click', "#btn-delete", function(){
            var row = $(this).closest('.row')
            var value = row.children('#id_tk').val()
            console.log(value);
            if(x > 1){
                if(value != null){
                    $.ajax({
                        type: "GET",
                        url: "/deleteEditTunjangan?id_tk="+value,
                        datatype: "json",
                        success: function(res){
                            if(res == "sukses"){
                                row.remove()
                                x--;
                            }
                        }
                    })
                } else{
                    $(this).closest('.row').remove()
                    x--;
                }
            }
        })
    </script>
@endsection