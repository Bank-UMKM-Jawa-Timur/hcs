@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard</a> / <a href="/">Karyawan</a> / <a href="/data_karyawan">Data Karyawan</a> / Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            <div class="row m-0">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>   
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Nama Karyawan</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kantor">Kantor</label>
                        <select name="kantor" id="kantor" class="form-control">
                            <option value="">--- Pilih Kantor ---</option>
                            <option value="1">Kantor Pusat</option>
                            <option value="2">Kantor Cabang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputGroupSelect01">Cabang</label>
                        <select class="form-control" id="inputGroupSelect01">
                          <option selected>--- Pilih ---</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputGroupSelect01">Divisi</label>
                        <select class="form-control" id="inputGroupSelect01">
                          <option selected>--- Pilih ---</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                    </div>
                </div>      
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputGroupSelect01">Sub divisi</label>
                        <select class="form-control" id="inputGroupSelect01">
                          <option selected>Choose...</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                    </div>
                </div>   
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Alamat KTP</label>
                        <textarea name="" id="" class="form-control"></textarea>
                    </div>
                </div>    
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Alamat sekarang</label>
                        <textarea name="" id="" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tempat Lahir</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="" id="">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Agama</label>
                        <select name="agama" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Pangkat Dan Golongan</label>
                        <select name="panggol" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan</label>
                        <select name="jabatan" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Kewarganegaraan</label>
                        <input type="text" name="" id="" class="form-control">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jenis Kelamin</label>
                        <select name="agama" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Status pernikahan</label>
                        <select name="status pernikahan" id="status" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Pasangan</label>
                        <select name="pasangan" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">KPJ</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">JKN</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Gaji Pokok</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Gaji Penyesuaian</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Status Karyawan</label>
                        <select name="status_karyawan" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">SK Pengangkatan</label>
                        <label for="input_sk" class="form-control"></label>
                        <input type="file" class="form-control" name="input_sk">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengangkatan</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
            </div>   
            <div class="row m-3">
                <button type="submit" class="btn btn-success">Tambah Mutasi</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        let kantor = $('#kantor');
        let status = $('#status');
        let urlCabang = "{{ route('get-cabang') }}";
        let urlDivisi = "{{ route('get-divisi') }}"

        $('#kantor').
    </script>
@endsection