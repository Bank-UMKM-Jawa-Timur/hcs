@extends('layouts.template')
@include('vendor.select2')
@section('content')
@php
    $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
@endphp
<div class="card-header">
    <div class="card-header">
        <h5 class="card-title">Pajak Penghasilan</h5>
        <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('pajak_penghasilan.index') }}">Penghasilan </a> > Tambah</p>
    </div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col">
                <a class="mb-3" href="{{ route('import-penghasilan-index') }}">
                    <button class="btn btn-primary">Import penghasilan</button>
                </a>
            </div>
        </div>
        <form action="{{ route('insert-penghasilan') }}" method="POST" enctype="multipart/form-data">
            <div class="row m-0">
                @csrf
                <div class="col-md-6 form-group">
                    <label for="nip">Karyawan:</label>
                    <select name="nip" id="nip" class="form-control" required></select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="tanggal" id="" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="id_tunjangan">Penghasilan:</label>
                    <select name="id_tunjangan" id="" class="form-control" required>
                        <option value="">--- Pilih Penghasilan ---</option>
                        @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="nominal">Nominal:</label>
                    <input type="text" name="nominal" class="form-control rupiah">
                </div>
            </div>

            <div class="row m-0">
                <div class="container">
                    <button class="btn btn-info">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
    <script>
        $('#nip').select2({
            ajax: {
                url: '{{ route('api.select2.karyawan') }}'
            },
            templateResult: function(data) {
                if(data.loading) return data.text;
                return $(`
                    <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
                `);
            }
        });

        $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(".rupiah").val(formatRupiah(angka));
        })
    </script>
@endpush
