@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Import Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a> > Import</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="container m-0">
                    <a href="{{ asset('template_import.xlsx') }}" download>
                        <button class="btn btn-primary">Download Template Excel</button>
                    </a>
                </div>

                <form action="{{ route('upload_karyawan') }}" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="container ml-3">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>  
                        </div>
                        <div class="container ml-3">
                            <button class="is-btn is-primary-light">Import</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12 justify-content-center">
                
                
                @if ($errors->any())
                    <div class="table-responsive justify-content-center container">
                        <table class="table">
                            <tbody>
                                @foreach ($errors->all() as $item)
                                <tr class="justify-content-center">
                                    <td>
                                        {{ $item }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });
    </script>
@endsection