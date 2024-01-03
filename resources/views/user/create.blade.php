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
                            <select name="name" id="nama-karyawan" class="@error('name') is_invalid @enderror form-control" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawan as $key => $item)
                                    @php
                                        $daftarUser = \App\Models\User::select('id','name','username','email')->where('username', $item->nip)->get();
                                    @endphp
                                    @if (count($daftarUser))
                                    @else
                                        <option value="{{$item->nip}}">{{$item->nip}} - {{$item->nama_karyawan}}</option>
                                    @endif
                                @endforeach
                                {{-- @foreach ($karyawan as $item)
                                    <option value="{{$item->nip}}">{{$item->nip}} - {{$item->nama_karyawan}}</option>
                                @endforeach --}}
                            </select>
                            @error('name')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="username">Email</label>
                            <input type="text" name="username" id="username" class="@error('username') is_invalid @enderror form-control" value="{{ old('username') }}" value="" required>
                            @error('username')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="username">Role</label>
                            <select name="role" id="role-karyawan" class="@error('role') is_invalid @enderror form-control" required>
                                <option value="">Pilih Role</option>
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
                        <input type="hidden" name="password" id="nip-for-password">
                        <button class="is-btn is-primary" value="submit" type="submit" style="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        $('#nama-karyawan').select2();

        $('#nama-karyawan').on('change', function(){
            var karyawan = $(this).val();
            console.log(karyawan);
            $('#nip-for-password').val(karyawan);
            // document.getElementById('username').value = karyawan + "@mail.com"
        })

        $('#role-karyawan').on('change', function () { 
            let kdCabang = $(this).val()
            if (kdCabang == 4) {
                $('#cabang').append(`
                    <label for="username">Cabang</label>
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
