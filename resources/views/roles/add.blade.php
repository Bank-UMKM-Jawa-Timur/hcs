@extends('layouts.app-template')
@push('extraScript')
    <script>
        $("#pilihSemua").change(function(){
            if($(this).prop('checked')){
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', true);
                })
            } else {
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', false);
                })
            }
        })
    </script>
@endpush
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Tambah Roles</h2>
            <div class="breadcrumb">
             <a href="/" class="text-sm text-gray-500">Setting</a>
             <i class="ti ti-circle-filled text-theme-primary"></i>
             <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
             <i class="ti ti-circle-filled text-theme-primary"></i>
             <a href="{{ route('role.index') }}" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>
        <div class="button-wrapper">
            <form id="form" method="get">
                <div class="input-box">
                    <label for="q">Cari</label>
                    <input type="search" name="q" id="q" class="form-input" placeholder="Cari disini..."
                        class="form-control p-2" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                </div>
            </form>
        </div>
    </div>
</div>
<button class="btn-scroll-to-top btn btn-primary hidden absolute bottom-5 right-5 z-20">
    To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
</button>

<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('role.store') }}" id="save-data" method="POST" enctype="multipart/form-data" class="form-group" >
            @csrf
            <div class="flex justify-between items-end">
                <div class="flex-none w-11/12">
                    <div class="input-box">
                        <label for="name">Role</label>
                        <input type="text" class="@error('name') is-invalid @enderror form-input" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Role">
                    </div>
                    @error('name')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn-save btn btn-primary form-input">Simpan</button>
            </div>
            <div class="position-relative form-group mt-4">
                {{-- <label for="Hak Akses" class="text-sm font-bold">Hak Akses</label> --}}
                <table class="tables" id="tableHakAkses">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="text-align: left">Nama</th>
                            <th style="text-align: left"><input type="checkbox" id="pilihSemua"> Pilih Semua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left"> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                                <td style="text-align: left"><input type="checkbox" name="id_permissions[]" id="hak_akses" value="{{ $item->id }}"> Pilih</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-primary">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
    {{-- <div class="card-body ml-3 mr-3">
        <form action="{{ route('role.store') }}" id="save-data" method="POST" enctype="multipart/form-data" class="form-group" >
        <div class="pt-1 pb-5">
            <button class="btn-save is-btn is-primary">Simpan</button>
        </div>
        <div class="row">
            <div class="col">
                    @csrf
                    <label for="name">Role</label>
                    <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Role">
                    @error('name')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="position-relative form-group mt-4">
                        <label for="Hak Akses">Hak Akses</label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tableHakAkses">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th><input type="checkbox" id="pilihSemua"> Pilih Semua</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                                            <td><input type="checkbox" name="id_permissions[]" id="hak_akses" value="{{ $item->id }}"> Pilih</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-primary">Data Kosong</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div> --}}
@endsection
@section('extraScript')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        // $('#btn-save').on('click', function () {
        //     var row_hak_akses = document.getElementById('hak_akses')
        //     $.ajax({
        //         type: "POST",
        //         url: '{{ route('role.store') }}',
        //         headers: {
        //             'X-XSRF-TOKEN': xsrfToken
        //         },
        //         data: {
        //             'name': row_hak_akses
        //         },
        //         success: function (response) {
        //             console.log(response);
        //         }
        //     });
        // })
    </script>
@endsection
