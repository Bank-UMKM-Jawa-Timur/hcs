@extends('layouts.template')
@include('vendor.select2')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Edit Data User</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('user.index') }}">User</a> > <a>Tambah</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('user.update', $data->id) }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name" class="@error('name') is_invalid @enderror form-control"
                                value="{{$data->name}}" required />
                            @error('name')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="@error('email') is_invalid @enderror form-control" value="{{ old('email') == null ? $data->email : old('email') }}">
                            @error('email')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="role-karyawan">Role</label>
                            <select name="role" id="role-karyawan" class="@error('role') is_invalid @enderror form-control">
                                <option value="">Pilih Role</option>
                                @foreach ($role as $item)
                                    @if ($item->name != 'user')
                                        <option value="{{ $item->id }}" {{ $dataRoleId->role_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="cabang">
                            <label for="data-cabang">Cabang</label>
                            <select name="data_cabang" id="data-cabang" class="@error('role') is_invalid @enderror form-control">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $item)
                                    <option value="{{ $item->kd_cabang }}" {{ $data->kd_cabang == $item->kd_cabang ? 'selected' : '' }}>{{ $item->nama_cabang }}</option>
                                @endforeach
                            </select>
                            @error('cabang')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-3 pb-3">
                        <input type="hidden" name="password" id="nip-for-password" value="{{ $data->username }}">
                        <button class="is-btn is-primary" value="submit" type="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        var roleKaryawan = $('#role-karyawan').find(":selected").val();
        if (roleKaryawan != 4) {
            $('#cabang').hide();
        }else{
            $('#cabang').show();
        }

        $('#role-karyawan').on('change', function () { 
            let kdCabang = $(this).val()
            if (kdCabang == 4) {
                $('#cabang').show()
                // $('#cabang').append(`
                //         <label for="username">Cabang</label>
                //         <select name="data_cabang" id="data-cabang" class="@error('role') is_invalid @enderror form-control" required>
                //             <option value="">Pilih Cabang</option>
                //             @foreach ($cabang as $item)
                //                 <option value="{{ $item->kd_cabang }}" {{ $data->kd_cabang == $item->kd_cabang ? 'selected' : '' }}>{{ $item->nama_cabang }}</option>
                //             @endforeach
                //         </select>
                //         @error('cabang')
                //             <div class="mt-2 alert alert-danger">{{ $message }}</div>
                //         @enderror
                // `)
            }else{
                $('#cabang').hide();
            }
        })
    </script>
    @endpush
@endsection
