@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Mutasi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/mutasi">Mutasi</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('mutasi.store') }}" method="POST" enctype="multipart/form-data" name="divisi" class="form-group">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <h6>Karyawan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" name="nip" id="karyawan" class="@error('nip') is-invalid @enderror form-control" value="{{ old('nip') }}">
                        @error('nip')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="nama" id="nama_karyawan" disabled class="form-control">
                    </div>
                </div>
                <div class="" id="kantor_lama">

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" class="form-control" disabled name="" id="jabatan_lama">
                        <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan Data</h6>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kantor">Kantor</label>
                        <select name="kantor" id="kantor" class="@error('kantor') @enderror form-control">
                            <option {{ old('kantor') == '-' ? 'selected' : '' }} value="-">--- Pilih Kantor ---</option>
                            <option {{ old('kantor') == '1' ? 'selected' : '' }} value="1">Kantor Pusat</option>
                            <option {{ old('kantor') == '2' ? 'selected' : '' }} value="2">Kantor Cabang</option>
                        </select>
                        @error('kantor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4" id="kantor_row1">

                </div>
                <div class="col-md-4"  id="kantor_row2">
                    
                </div>  
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan Baru</label>
                        <select name="id_jabatan_baru" id="" class="@error('id_jabatan_baru') @enderror form-control">
                            <option value="-">--- Pilih jabatan baru ---</option>
                            @foreach ($data_jabatan as $item)
                                <option {{ old('id_jabatan_baru') == $item->kd_jabatan ? 'selected' : '-' }} value="{{ $item->kd_jabatan }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        @error('id_jabatan_baru')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Keterangan Jabatan</label>
                        <input type="text" class="@error('ket_jabatan') @enderror form-control" id="ket_jabatan" name="ket_jabatan" value="{{ old('ket_jabatan') }}">
                        @error('ket_jabatan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pengesahan</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="@error('tanggal_pengesahan') @enderror form-control" name="tanggal_pengesahan" id="" value="{{ old('tanggal_pengesahan') }}">
                        @error('tanggal_pengesahan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class= "col-md-4">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <input type="text" class="@error('bukti_sk') @enderror form-control" name="bukti_sk" id="inputGroupFile01" value="{{ old('bukti_sk') }}">
                        @error('bukti_sk')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class= "col-md-4">
                    <div class="form-floating form-group">
                        <label for="">Keterangan</label>
                        <textarea class="@error('keterangan') @enderror form-control" name="keterangan" placeholder="Keterangan" id="floatingTextarea">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#karyawan').change(function(e){
            $('#kantor_lama').empty();
            var nip = $(this).val();
           $.ajax({
            type: "GET",
            url: "/getdatakaryawan?nip="+nip,
            datatype: "json",
            success: function(res){
                $('#kantor_lama').addClass('col-md-6')
                if(res.kd_cabang!= null){
                    $('#kantor_lama').append(`
                            <div class="form-group">
                                <label for="">Cabang Lama</label>
                                <input type="text" class="form-control" name="" value="${res.nama_cabang}" id="" disabled>
                                <input type="hidden" name="id_kantor_lama" value="${res.kd_cabang}">
                            </div>
                    `);
                }else if(res.id_subdivisi != null){
                    $('#kantor_lama').append(`
                            <div class="form-group">
                                <label for="">Sub Divisi Lama</label>
                                <input type="text" class="form-control" name="" value="${res.nama_subdivisi}" id="" disabled>
                                <input type="hidden" name="id_kantor_lama" value="${res.id_subdivisi}">
                            </div>
                    `);
                }

                $('#jabatan_lama').val(res.nama_jabatan)
                $('#id_jabatan_lama').val(res.id_jabatan)
                $("#nama_karyawan").val(res.nama_karyawan)
                $('#ket_jabatan').val(res.ket_jabatan)
            },
            error: function(){
                $('#kantor_lama').removeClass('col-md-6')
            }
           }) 
        });

        $('#kantor').change(function(){
            var kantor_id = $(this).val();

            if(kantor_id == 1){
                $.ajax({
                    type: "GET",
                    url: '/getdivisi',
                    datatype: 'JSON',
                    success: function(res){
                        $("#kantor_row1").empty();
                        $("#kantor_row1").append(`
                                <div class="form-group">
                                    <label for="divisi">Divisi</label>
                                    <select name="divisi" id="divisi" class="form-control">
                                        <option value="">--- Pilih divisi ---</option>
                                    </select>
                                </div>`
                        );
                        $.each(res, function(i, item){
                            $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
                        });

                        $("#kantor_row2").append(`
                                <div class="form-group">
                                    <label for="sub_divisi">Sub divisi</label>
                                    <select name="id_subdiv_baru" id="sub_divisi" class="form-control">
                                        <option value="">--- Pilih sub divisi ---</option>
                                    </select>
                                </div>`
                        );
        
                        $("#divisi").change(function(){
                            var divisi = $(this).val();

                            if(divisi){
                                $.ajax({
                                    type: "GET",
                                    url: "/getsubdivisi?divisiID="+divisi,
                                    datatype: "JSON",
                                    success: function(res){
                                        $('#sub_divisi').empty();
                                        $.each(res, function(i, item){
                                            $('#sub_divisi').append('<option value="'+item.kd_subdiv+'">'+item.nama_subdivisi+'</option>')
                                        })
                                    }
                                })
                            }
                        })
                    }
                })
            } else if(kantor_id == 2){
                $.ajax({
                    type: "GET",
                    url: '/getcabang',
                    datatype: 'JSON',
                    success: function(res){
                        $("#kantor_row1").empty();
                        $("#kantor_row2").empty();
                        $("#kantor_row1").append(`
                                <div class="form-group">
                                    <label for="Cabang">Cabang</label>
                                    <select name="id_cabang_baru" id="cabang" class="form-control">
                                        <option value="">--- Pilih Cabang ---</option>
                                    </select>
                                </div>`
                        );
                        $.each(res[0], function(i, item){
                            $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.nama_cabang+'</option>')
                        })
                    }
                })
            }
        });
    </script>
@endsection