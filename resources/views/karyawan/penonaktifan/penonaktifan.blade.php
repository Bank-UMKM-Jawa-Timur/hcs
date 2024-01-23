@extends('layouts.app-template')
@include('vendor.select2')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Penonaktifan Karyawan</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Pergerakan Karir</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('penonaktifan.index') }}" class="text-sm text-gray-500 font-bold">Penonaktifan Karyawan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Tambah</p>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('penonaktifan.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                    <div class="input-box">
                        <label for="">Karyawan:</label>
                        <select name="nip" id="nip" class="form-input @error('nip') is-invalid @enderror"></select>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="tanggal_penonaktifan">Tanggal Penonaktifan:</label>
                        <input type="date" class="form-input @error('tanggal_penonaktifan') is-invalid @enderror" name="tanggal_penonaktifan" id="tanggal_penonaktifan">
                        @error('tanggal_penonaktifan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-box">
                        <label for="tanggal_penonaktifan">Kategori Penonaktifan:</label>
                        <select name="kategori_penonaktifan" id="kategori_penonaktifan" class="form-input @error('kategori_penonaktifan') is-invalid @enderror">
                            <option>-- Pilih Kategori --</option>
                            @foreach (\App\Enum\KategoriPenonaktifan::cases() as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('kategori_penonaktifan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                <div class="input-box">
                    <label for="">SK Pemberhentian <span class="text-red-500 text-sm">*(Pdf)</span></label>
                    <div class="custom-file">
                        <input type="file" class="form-input @error('sk_pemberhentian') is-invalid @enderror" name="sk_pemberhentian" id="sk_pemberhentian" accept="application/pdf">
                        {{-- <label for="sk_pemberhentian" class="custom-file-label">Pilih File (PDF)</label> --}}
                        @error('sk_pemberhentian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mt-5">
                    <button class="btn btn-primary" type="submit">Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}',
            data: function(params) {
                return {
                    search: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true,
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    
    document.querySelector('.custom-file-input').addEventListener('change', function (e) {
        var name = document.getElementById("sk_pemberhentian").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = name
    });
</script>
@endpush
