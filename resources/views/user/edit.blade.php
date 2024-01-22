@extends('layouts.app-template')
@include('vendor.select2')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Edit Data User</h2>
                <div class="breadcrumb">
                 <a href="/" class="text-sm text-gray-500">Setting</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="{{ route('user.index') }}" class="text-sm text-gray-500 font-bold">User</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <p class="text-sm text-gray-500 font-bold">Edit User</p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form action="{{ route('user.update', $data->id) }}" class="form-group" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
                    <div class="input-box">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="@error('name') is_invalid @enderror form-input"
                            value="{{$data->name}}" required />
                        @error('name')
                            <div class="mt-2 text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="@error('email') is_invalid @enderror form-input" value="{{ old('email') == null ? $data->email : old('email') }}">
                        @error('email')
                            <div class="mt-2 text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="role-karyawan">Role</label>
                        <select name="role" id="role-karyawan" class="@error('role') is_invalid @enderror form-input">
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
                    <div class="input-box">
                        <label for="data-cabang">Cabang</label>
                        <select name="data_cabang" id="data-cabang" class="@error('role') is_invalid @enderror form-input">
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
                <div class="mt-5">
                    <input type="hidden" name="password" id="nip-for-password" value="{{ $data->username }}">
                    <button class="btn btn-primary" value="submit" type="submit" style="submit">Simpan</button>
                </div>
            </form>
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
