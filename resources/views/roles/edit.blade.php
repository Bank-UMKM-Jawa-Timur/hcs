@extends('layouts.app-template')
@push('extraScript')
    <script>
        $("#pilihSemua").change(function(){
            if($(this).prop('checked')){
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', true);
                    var id = $(this).data("id");
                    var selected = $(this).data("selected");
                    console.log(id);
                    console.log(selected);
                    if(selected){
                        $(this).parent().parent().find(`input[type=hidden]`).attr("disabled", true);
                    } else{
                        $(this).parent().parent().find(`input[type=hidden]`).removeAttr("disabled");
                    }
                })
            } else {
                $('#tableHakAkses tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', false);
                    var id = $(this).data("id")
                    var selected = $(this).data("selected");
                    console.log(id);
                    console.log(selected);
                    if(selected){
                        $(this).parent().parent().find(`input[type=hidden]`).removeAttr("disabled")
                    } else{
                        $(this).parent().parent().find(`input[type=hidden]`).attr("disabled", true)
                    }
                })
            }
        })

        $('#tableHakAkses tbody tr td input[type="checkbox"]').change(function(){
            if($(this).prop("checked")){
                var id = $(this).data("id");
                var selected = $(this).data("selected");
                console.log(id);
                console.log(selected);
                if(selected){
                    $(this).parent().parent().find(`input[type=hidden]`).attr("disabled", true);
                } else{
                    $(this).parent().parent().find(`input[type=hidden]`).removeAttr("disabled");
                }
            } else {
                var id = $(this).data("id")
                var selected = $(this).data("selected");
                console.log(id);
                console.log(selected);
                if(selected){
                    $(this).parent().parent().find(`input[type=hidden]`).removeAttr("disabled")
                } else{
                    $(this).parent().parent().find(`input[type=hidden]`).attr("disabled", true)
                }
            }
        })
    </script>
@endpush
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Edit Roles</h2>
                <div class="breadcrumb">
                 <a href="#" class="text-sm text-gray-500">Setting</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="{{ route('role.index') }}" class="text-sm text-gray-500 font-bold">Edit</a>
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

    <button class="btn-scroll-to-top btn btn-primary hidden fixed bottom-5 right-5 z-20">
        To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
    </button>

    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('role.update',$data->id) }}" method="POST" enctype="multipart/form-data" class="form-group" >
                @method('put')
                @csrf
                <div class="flex justify-between items-end">
                    <div class="flex-none w-11/12">
                        <div class="input-box">
                            <label for="name">Role</label>
                            <input type="text" class="@error('name') is-invalid @enderror form-input" name="name" id="name" value="{{ old('role', $data->name) }}" placeholder="Nama Role">
                        </div>
                        @error('name')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn-save btn btn-primary">Simpan</button>
                </div>
                <table class="tables mt-5" id="tableHakAkses">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="text-align: left">Nama</th>
                            <th style="text-align: left"><input type="checkbox" id="pilihSemua" {{ count($dataPermissions) == count($dataPermissionsSelected) ? 'checked' : '' }}> Pilih Semua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataPermissions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left"> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                                <td style="text-align: left"><input type="checkbox" name="id_permissions[]" id="hak_akses" value="{{ $item->id }}" data-id="{{ $item->id }}" data-selected="{{ in_array($item->id, $dataPermissionsSelected) ? 'true' : 'false' }}" {{ in_array($item->id, $dataPermissionsSelected) ? 'checked' : '' }}> Pilih</td>
                                        @if (in_array($item->id, $dataPermissionsSelected))
                                            <input type="hidden" name="fieldToDelete[]" value="{{ $item->id }}" id="fieldToDelete-{{ $item->id }}" disabled>
                                        @else
                                            <input type="hidden" name="fieldToInsert[]" value="{{ $item->id }}" id="fieldToInsert-{{ $item->id }}" disabled>
                                        @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
