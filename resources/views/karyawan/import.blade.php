@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard </a> / <a href="/karyawan">Karyawan </a> / Import</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <a href="{{ asset('template_import.xlsx') }}" download>
                    <button class="btn btn-primary">Download Template Excel</button>
                </a>

                <form action="{{ route('upload_karyawan') }}" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Data CSV</label>
                            <label for="csv" class="form-control" ></label>
                            <input type="file" name="upload_csv" class="form-control" accept="">
        
                        </div>
                        <div class="container">
                            <button class="btn btn-info">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection