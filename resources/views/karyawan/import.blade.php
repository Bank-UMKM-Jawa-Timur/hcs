@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Import Data Karyawan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500">Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Import</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a href="{{ asset('template_import.xlsx') }}" download class="btn btn-outline-excel"> <i class="ti ti-download"></i>Download Template Excel</a>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <div class="col-md-12">
                <div class="col-md-12 justify-content-center">
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                            <span class="alert-link">Terjadi Kesalahan</span>
                            <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside">
                                @foreach ($errors->all() as $item)
                                <li class="">
                                    {{ $item }}

                                </li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                </div>
                <form action="{{ route('upload_karyawan') }}" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
                        <div class="input-box">
                            <label for="">Data Excel</label>
                            <div class="input-group">
                                <input type="file" name="upload_csv" class="form-upload" id="upload_csv" accept=".xlsx, .xls">
                                <button class="upload-group-icon">
                                    <label for="file-penghasilan">
                                        <i class="ti ti-upload"></i>
                                    </label>
                                </button>
                            </div>

                        </div>
                        {{-- <div class="input-box">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>
                        </div> --}}
                        <div class="container ml-3">
                            <button class="btn btn-primary btn-import is-btn is-primary btn-import">Import</button>
                        </div>
                    </div>
                </form>
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
