@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Demosi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/demosi">Demosi</a> > Edit</p>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="" method="POST" enctype="multipart/form-data" name="" class="form-group">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jabatan Baru</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>  
                </div>
            </div>
            <div class="row align-content-center justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputGroupSelect01">Divisi</label>
                        <select class="form-control" id="inputGroupSelect01">
                        <option selected>Choose...</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        </select>
                    </div>
                </div>    
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Sub Divisi Lama</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
                <div class="col-md-4">
                    <label for="inputGroupSelect01">Sub Divisi Baru</label>
                    <select class="form-control" id="inputGroupSelect01">
                    <option selected>Choose...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row align-content-center justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Cabang Lama</label>
                        <input type="text" class="form-control" name="" id="">
                    </div>
                </div>    
                <div class="col-md-6">
                    <label for="inputGroupSelect01">Cabang Baru</label>
                    <select class="form-control" id="inputGroupSelect01">
                    <option selected>Choose...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row align-content-center justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="form-control" name="" id="">
                    </div>
                </div>    
                <div class= "col-md-4">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <label class="form-control" for="inputGroupFile01">Choose file</label>
                        <input type="file" class="custom-file-input" id="inputGroupFile01">
                    </div>
                </div>
                <div class= "col-md-4">
                    <div class="form-floating form-group">
                        <label for="">Keterangan</label>
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-info">Update</button>
            </div>
        </form>
    </div>
@endsection