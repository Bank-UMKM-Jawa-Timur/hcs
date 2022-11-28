@extends('layouts.template')

@section('content')
    <form action="{{ route('tunjangan_karyawan.update', $data->id) }}" enctype="multipart/form-data" method="post">
        @csrf
        @method('PUT')
        <div class="row m-0">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">NIP</label>
                    <input type="text" name="nip" id="karyawan" class="form-control" value="{{ $data->nip }}">
                </div>
            </div>    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_karyawan">Nama Karyawan</label>
                    <input type="text" name="nama" id="nama_karyawan" disabled class="form-control" value="{{ $data->nama_karyawan }}">
                </div>
            </div>
            <div class="col-md-6">
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_karyawan">Nominal</label>
                    <input type="number" name="nominal" id="nominal" class="form-control" value="{{ $data->nominal }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="update ml-auto mr-auto">
            <button type="submit" class="btn btn-success">update</button>
            </div>
        </div>
    </form>
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