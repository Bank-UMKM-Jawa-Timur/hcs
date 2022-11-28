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
                        <label for="">Karyawan</label>
                        <select name="nip" class="form-control" id="karyawan">
                            <option value="">--- Pilih Karyawan ---</option>
                            @foreach ($data as $item)
                                <option value="{{ $item->nip }}">{{ $item->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>    
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Golongan Lama</label>
                        <input type="text" disabled class="form-control" name="" id="golongan_lama_show">
                        <input type="hidden" name="golongan_lama" id="golongan_lama">
                    </div>
                </div>    
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Pangkat Lama</label>
                        <input type="text" disabled class="form-control" name="" id="pangkat_lama_show">
                        <input type="hidden" name="pangkat_lama" id="pangkat_lama">
                    </div>  
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pangkat dan Golongan Baru</h6>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Pangkat dan Golongan</label>
                        <select name="golongan_baru" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($data_panggol as $item)
                                <option value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
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
            $('#golongan_lama_show').empty()
            $('#golongan_lama').empty()
            $('#pangkat_lama_show').empty()
            $('#pangkat_lama').empty()
            var nip = $(this).val();
           $.ajax({
            type: "GET",
            url: "/getgolongan?nip="+nip,
            datatype: "json",
            success: function(res){
                $('#golongan_lama_show').val(res.golongan)
                $('#golongan_lama').val(res.golongan)
                $('#pangkat_lama_show').val(res.pangkat)
                $('#pangkat_lama').val(res.pangkat)
            }
           }) 
        });
    </script>
@endsection