@extends('layouts.template')
@include('vendor.select2')
@section('content')
@php
    $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
@endphp
<div class="card-header">
    <div class="card-header">
        <h5 class="card-title">Tambah Penghasilan Tidak Teratur</h5>
        <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('pajak_penghasilan.index') }}">Penghasilan </a> > Tambah</p>
    </div>

    <div class="card-body pl-0">
        <form action="{{ route('insert-penghasilan') }}" method="POST" enctype="multipart/form-data">
            <div class="row m-0">
                @csrf
                <div class="col-md-6 form-group">
                    <label for="nip">Karyawan:</label>
                    <select name="nip" id="nip" class="form-control" required></select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    <h6 class="mt-2 text-danger d-none" id="error-message"></h6>
                    @error('tanggal')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
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
                    <input type="text" name="nominal" class="form-control rupiah" required>
                    @error('nominal')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="nominal">Keterangan:</label>
                    <input type="text" name="keterangan" class="form-control" required>
                    @error('keterangan')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pl-3">
                    <button class="is-btn is-primary">Tambah</button>
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

        $(`#tanggal`).on('change', function (){
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
                    // console.log(typeof(res.kode));
                    console.log(res);
                    if (res.kode == 1) {
                        $(`#error-message`).removeClass('d-none').html(res.message)
                        $(`#tanggal`).val('')
                    }
                    else {
                        $(`#error-message`).addClass('d-none')
                    }
                }
            })
        })
    </script>
@endpush
