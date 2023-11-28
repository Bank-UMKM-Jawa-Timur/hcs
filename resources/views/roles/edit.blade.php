@extends('layouts.template')
@push('script')
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
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Roles</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('role.index') }}">Roles</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('role.update',$data->id) }}" method="POST" enctype="multipart/form-data" class="form-group" >
                    @method('put')
                    @csrf
                    <label for="name">Role</label>
                    <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" id="name" value="{{ old('role', $data->name) }}" placeholder="Nama Role">
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
                                        <th><input type="checkbox" id="pilihSemua" {{ count($dataPermissions) == count($dataPermissionsSelected) ? 'checked' : '' }}> Pilih Semua</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPermissions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                                            <td><input type="checkbox" name="id_permissions[]" id="hak_akses" value="{{ $item->id }}" data-id="{{ $item->id }}" data-selected="{{ in_array($item->id, $dataPermissionsSelected) ? 'true' : 'false' }}" {{ in_array($item->id, $dataPermissionsSelected) ? 'checked' : '' }}> Pilih</td>
                                                    @if (in_array($item->id, $dataPermissionsSelected))
                                                        <input type="hidden" name="fieldToDelete[]" value="{{ $item->id }}" id="fieldToDelete-{{ $item->id }}" disabled>
                                                    @else
                                                        <input type="hidden" name="fieldToInsert[]" value="{{ $item->id }}" id="fieldToInsert-{{ $item->id }}" disabled>
                                                    @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button class="btn btn-info">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
