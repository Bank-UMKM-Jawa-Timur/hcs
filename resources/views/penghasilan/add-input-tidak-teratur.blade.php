@extends('layouts.app-template')
@include('vendor.select2')
@section('content')
@php
    $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
@endphp
    <div class="head">
        <div class="heading">
            <h2>Tambah Penghasilan Tidak Teratur</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('pajak_penghasilan.index') }}" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500">Tambah</a>
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="card">
            <form action="{{ route('insert-penghasilan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="grid lg:grid-cols-3 gap-5 items-center md:grid-cols-2 grid-cols-1">
                        <div class="input-box">
                            <label for="id_label_single">
                                Karyawan
                            </label>
                            <select class="select-2" id="nip" name="nip" required>
                                <option value="">-- Pilih Nama Karyawan --</option>
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="datefield">Tanggal</label>
                            <input type="date" name="tanggal"  id="datefield" class="form-input" required>
                            <h6 class="mt-2 text-red-500" id="error-message"></h6>
                            @error('tanggal')
                                <div class="mt-2 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="selectfield">Penghasilan</label>
                            <select name="id_tunjangan" class="form-input" id="nip">
                                <option value="">--- Pilih Penghasilan ---</option>
                                @foreach ($data as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="textfield-nominal">Nominal</label>
                            <input type="text" id="textfield-nominal" name="nominal" class="form-input rupiah">
                            @error('nominal')
                            <div class="mt-2 text-red-500">{{ $message }}</div>
                        @enderror
                        </div>
                        <div class="input-box">
                            <label for="textfield-keterangan">Keterangan</label>
                            <input type="text" name="keterangan" id="textfield-keterangan" class="form-input">
                            @error('keterangan')
                            <div class="mt-2 text-red-500">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="pt-8">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-plus"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('extraScript')
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

        $(`#datefield`).on('change', function (){
            const tanggal = $(this).val();
            const nip = $(`#nip`).val();

            $.ajax({
                type: "GET",
                url: `{{ url('penghasilan-tidak-teratur/validasi-insert') }}`,
                data: {
                    nip: nip,
                    tanggal: tanggal,
                },
                success: function(res){
                    console.log(res);
                    if (res.kode == 1) {
                        $(`#error-message`).removeClass('d-none').html(res.message)
                        $(`#datefield`).val('')
                    }
                    else {
                        $(`#error-message`).addClass('d-none')
                    }
                }
            })
        })
        $(`#nip`).on('change', function (){
            const nip = $(this).val();
            const tanggal = $(`#datefield`).val();

            if (tanggal != '') {
                $.ajax({
                    type: "GET",
                    url: `{{ url('penghasilan-tidak-teratur/validasi-insert') }}`,
                    data: {
                        nip: nip,
                        tanggal: tanggal,
                    },
                    success: function(res){
                        console.log(res);
                        if (res.kode == 1) {
                            $(`#error-message`).removeClass('d-none').html(res.message)
                            $(`#datefield`).val('')
                        }
                        else {
                            $(`#error-message`).addClass('d-none')
                        }
                    }
                })

            }
        })
    </script>
@endpush
