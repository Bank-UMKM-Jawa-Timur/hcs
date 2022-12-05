@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Promosi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/promosi">Promosi</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('promosi.store') }}" method="POST" enctype="multipart/form-data" name="divis" class="form-group">
            @csrf
            <div class="row">
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
                        <input type="text" name="" id="nama_karyawan_show" disabled class="form-control" value="{{ old('nama_karyawan') }}">
                        <input type="hidden" name="nama_karyawan" id="nama_karyawan" value="{{ old('nama_karyawan') }}">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" disabled class="form-control" name="" id="jabatan_show" value="{{ old('jabatan_lama') }}">
                        <input type="hidden" name="jabatan_lama" id="jabatan_lama" value="{{ old('jabatan_lama') }}">
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
                        <select name="jabatan_baru" id="" class="@error('jabatan_baru') @enderror form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($jabatan as $item)
                                <option {{ old('jabatan_baru') == $item->kd_jabatan ? 'selected' : '' }} value="{{ $item->kd_jabatan }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        @error('jabatan_baru')
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="form-control" name="@error('tanggal_pengesahan') @enderror tanggal_pengesahan" id="" value="{{ old('tanggal_pengesahan') }}">
                        @error('tanggal_pengesahan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class= "col-md-6">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <input type="text" class="@error('bukti_sk') @enderror form-control" name="bukti_sk" id="inputGroupFile01" value="{{ old('bukti_sk') }}">
                        @error('bukti_sk')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Keterangan Jabatan</label>
                        <textarea name="ket_jabatan" class="form-control" id="ket_jabatan"></textarea>
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
            var nip = $(this).val();
           $.ajax({
            type: "GET",
            url: "/getdatatunjangan?nip="+nip,
            datatype: "json",
            success: function(res){
                $("#nama_karyawan_show").val(res.nama_karyawan)
                $("#nama_karyawan").val(res.nama_karyawan)
                $("#jabatan_lama").val(res.kd_jabatan)
                $("#jabatan_show").val(res.nama_jabatan)
            }
           }) 
        });
    </script>
@endsection