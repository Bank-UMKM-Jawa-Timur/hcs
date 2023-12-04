@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Import Data Migrasi Surat Peringatan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > Insert Data Migrasi Surat Peringatan</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col-md-12">
                <form action="{{ route('migrasiStore') }}" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="d-flex justify-between">
                        <input type="hidden" name="tipe" value="sp">
                        <div class="w-50">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>  
                            <div class="pt-4 pb-4">
                                <button class="is-btn is-primary-light">Import</button>
                            </div>
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
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });
    </script>
@endsection