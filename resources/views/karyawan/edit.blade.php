@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard</a> / <a href="/">Karyawan</a> / <a href="/data_karyawan">Data Karyawan</a> / Edit</p>
        </div>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            <div class="row m-0">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
            </div>
            <div class="row m-0">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Nama Karyawan</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
            </div>
            <div class="row m-0">
                <div class="col-5">
                    <div class="form-group">
                        <label for="inputGroupSelect01">Pangkat</label>
                        <select class="form-control" id="inputGroupSelect01">
                          <option selected>Choose...</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                    </div>
                </div>   
            </div>
            <div class="row m-0">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <label class="form-control form-label" for="inputGroupFile01">Choose file</label>
                        <input type="file" class="form-control" id="inputGroupFile01">
                    </div>
                </div>    
            </div>
            <div class="row m-0">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Tanggal Pengangkatan</label>
                        <input type="date" class="form-control" name="" id="">
                    </div>
                </div> 
            </div>   
            <div class="row m-3">
                <button type="submit" class="btn btn-success">Edit Mutasi</button>
            </div>
        </form>
    </div>
@endsection