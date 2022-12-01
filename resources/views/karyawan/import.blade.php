@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/karyawan">Karyawan </a> > Import</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="container">
                    <a href="{{ asset('template_import.xlsx') }}" download>
                        <button class="btn btn-primary">Download Template Excel</button>
                    </a>
                </div>

                <form action="{{ route('upload_karyawan') }}" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="container">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>  
                        </div>
                        <div class="container">
                            <button class="btn btn-info">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });
    </script>
@endsection