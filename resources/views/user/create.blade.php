@extends('layouts.template')
@include('vendor.select2')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title font-weight-bold">Tambah Data User</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('user.index') }}">User</a> > <a>Tambah</a></p>
            </div>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('user.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name" class="@error('name') is_invalid @enderror form-control" required />
                            @error('name')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="@error('email') is_invalid @enderror form-control" value="{{ old('email') }}" value="" required>
                            @error('email')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="role-karyawan">Role</label>
                            <select name="role" id="role-karyawan" class="@error('role') is_invalid @enderror form-control" required>
                                <option value="0">Pilih Role</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="cabang">
                        </div>
                    </div>
                    <div class="pt-3 pb-3">
                        <button class="is-btn is-primary" value="submit" type="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        $('#role-karyawan').on('change', function () { 
            let kdCabang = $(this).val()
            if (kdCabang == 4) {
                $('#cabang').append(`
                    <label for="data-cabang">Cabang</label>
                    <select name="data_cabang" id="data-cabang" class="@error('role') is_invalid @enderror form-control" required>
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $item)
                            <option value="{{ $item->kd_cabang }}">{{ $item->nama_cabang }}</option>
                        @endforeach
                    </select>
                    @error('cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                `)
            }else{
                $('#cabang').empty();
            }
        })
    </script>
    @endpush
@endsection
