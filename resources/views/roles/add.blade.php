@extends('layouts.template')
@push('script')
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
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Tambah Roles</h5>
        <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('role.index') }}">Roles</a> > <a>Tambah</a></p>
    </div>
    <div class="card-header row mr-8 pr-5">
        <form id="form" method="get">
            <label for="q">Cari</label>
            <input type="search" name="q" id="q" placeholder="Cari disini..."
                class="form-control p-2" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
        </form>
    </div>
</div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                    <form action="{{ route('role.store') }}" id="save-data" method="POST" enctype="multipart/form-data" class="form-group" >
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
    
                        <button class="btn-save" class="is-btn is-primary">Simpan</button>
                    {{-- </form> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
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