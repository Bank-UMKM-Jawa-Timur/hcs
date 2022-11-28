@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Tambah Tunjangan Karyawan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/tunjangan_karyawan">Tunjangan Karyawan</a> > Tambah</p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('tunjangan_karyawan.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">NIP</label>
                                <input type="text" name="nip" id="karyawan" class="form-control">
                            </div>
                        </div>    
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama_karyawan">Nama Karyawan</label>
                                <input type="text" name="nama" id="nama_karyawan" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tunjangan">Nama Tunjangan</label>
                                <select name="tunjangan" id="" class="form-control" data-mdb-filter="true">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($data as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama_karyawan">Nominal</label>
                                <input type="number" name="nominal" id="nominal" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </form>
            </div>
        </div>
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
            }
           }) 
        });
    </script>
@endsection