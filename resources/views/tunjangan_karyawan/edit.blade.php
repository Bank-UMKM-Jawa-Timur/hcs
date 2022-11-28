@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Edit Tunjangan Karyawan</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/tunjangan_karyawan">Tunjangan Karyawan</a> > Edit</p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <form action="{{ route('tunjangan_karyawan.update', $data->id) }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">NIP</label>
                                <input type="text" name="nip" id="karyawan" class="form-control" value="{{ $data->nip }}">
                            </div>
                        </div>    
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama_karyawan">Nama Karyawan</label>
                                <input type="text" name="nama" id="nama_karyawan" disabled class="form-control" value="{{ $data->nama_karyawan }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tunjangan">Nama Tunjangan</label>
                                <select name="tunjangan" id="" class="form-control">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($data_tunjangan as $item)
                                        <option value="{{ $item->id }}" {{ ($data->id_tunjangan == $item->id) ? 'selected' : '' }}>{{ $item->nama_tunjangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama_karyawan">Nominal</label>
                                <input type="number" name="nominal" id="nominal" class="form-control" value="{{ $data->nominal }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
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