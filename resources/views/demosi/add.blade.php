@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Demosi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/demosi">Demosi</a> > Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('demosi.store') }}" method="POST" enctype="multipart/form-data" name="divis" class="form-group">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" name="nip" id="karyawan" class="form-control">
                    </div>
                </div>    
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="nama" id="nama_karyawan" disabled class="form-control">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" disabled class="form-control" name="" id="jabatan_show">
                        <input type="hidden" name="jabatan_lama" id="jabatan_lama">
                    </div>
                </div>    
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Jabatan Baru</h6>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Jabatan</label>
                        <select name="jabatan_baru" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->kd_jabatan }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>  
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pengesahan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="form-control" name="tanggal_pengesahan" id="">
                    </div>
                </div>    
                <div class= "col-md-6">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <input type="text" class="form-control" name="bukti_sk" id="inputGroupFile01">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Keterangan Jabatan</label>
                        <textarea name="ket_jabatan" class="form-control" id="ket_jabatan"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="update ml-auto mr-auto">
                <button type="submit" class="btn btn-success">Tambah Demosi</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#karyawan').change(function(e){
            var nip = $(this).val();
           $.ajax({
            type: "GET",
            url: "/getdatatunjangan?nip="+nip,
            datatype: "json",
            success: function(res){
                $("#nama_karyawan").val(res.nama_karyawan)
                $("#jabatan_lama").val(res.kd_jabatan)
                $("#jabatan_show").val(res.nama_jabatan)
            }
           }) 
        });
    </script>
@endsection