@extends('layouts.app-template')
@include('vendor.select2')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Tambah Data User</h2>
                <div class="breadcrumb">
                 <a href="/" class="text-sm text-gray-500">Setting</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="{{ route('user.index') }}" class="text-sm text-gray-500 font-bold">User</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <p class="text-sm text-gray-500 font-bold">Tambah User</p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
                <form action="{{ route('user.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-box" id="role">
                            <label for="role-karyawan">Role</label>
                            <select name="role" id="role-karyawan" class="@error('role') is_invalid @enderror form-input" required>
                                <option value="0">Pilih Role</option>
                                @foreach ($role as $item)
                                    {{-- @if ($item->name != 'user') --}}
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    {{-- @endif --}}
                                @endforeach
                            </select>
                            @error('role')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box" id="nama">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name" class="@error('name') is_invalid @enderror form-input" required />
                            @error('name')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box" id="email">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="@error('email') is_invalid @enderror form-input" value="{{ old('email') }}" value="" required>
                            @error('email')
                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="pt-5 pb-3">
                        <button class="btn btn-primary" value="submit" type="submit" style="submit">Simpan</button>
                    </div>
                </form>
        </div>
    </div>

    @push('extraScript')
    <script>
        var nama = `
            <div class="input-box" id="nama">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="@error('name') is_invalid @enderror form-input" required />
                @error('name')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                @enderror
            </div>`;
            
        var email = `
            <div class="input-box" id="email">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="@error('email') is_invalid @enderror form-input" value="{{ old('email') }}" value="" required>
                @error('email')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                @enderror
            </div>`;

        $('#role-karyawan').on('change', function () { 
            let kdCabang = $(this).val()
            if (kdCabang === "4") {
                $('#karyawan').remove();
                $('#nama').remove();
                $('#email').remove();
                $('#role').after(`
                    <div class="input-box" id="cabang">
                        <label for="data-cabang">Cabang</label>
                        <select name="data_cabang" id="data-cabang" class="@error('role') is_invalid @enderror form-input" required>
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                                <option value="{{ $item->kd_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                        @error('cabang')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                `);
                $('#cabang').after(nama);
                $('#nama').after(email);
            }else if(kdCabang === "5"){
                $('#nama').remove();
                $('#email').remove();
                $('#cabang').remove();
                $('#role').after(`
                    <div class="input-box" id="karyawan">
                        <label for="id_label_single">
                            Karyawan
                        </label>
                        <select class="select-2 select-2-tailwind" name="nip" id="nip" required>
                        </select>
                        @error('karyawan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                `)

                $('#nip').select2({
                    placeholder: 'Pilih Nama Karyawan',
                    ajax: {
                        url: '{{ route('api.select2.karyawan2') }}',
                        data: function(params) {
                            const is_cabang = "{{auth()->user()->hasRole('cabang')}}"
                            const cabang = is_cabang ? "{{auth()->user()->kd_cabang}}" : null
                            return {
                                search: params.term || '',
                                page: params.page || 1,
                                cabang: cabang
                            }
                        },
                    },
                    templateResult: function(data) {
                        console.log(data);
                        if(data.loading) return data.text;
                        return $(`
                            <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
                        `);
                    }
                });
            }else{
                $('#nama').remove();
                $('#email').remove();
                $('#cabang').empty();
                $('#role').after(nama);
                $('#nama').after(email);
                $('#karyawan').remove();
            }
        })
    </script>
    @endpush
@endsection
