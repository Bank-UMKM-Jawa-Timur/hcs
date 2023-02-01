@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Migrasi</h5>
            <p class="card-title"><a href="/">Dashboard </a> > Migrasi</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col-md-12">
                
            </div>
            <div class="col-md-12 justify-content-center">
                
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