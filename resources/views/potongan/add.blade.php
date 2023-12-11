@extends('layouts.template')
@include('vendor.select2')
@section('content')
@php
    $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
@endphp
<div class="card-header">
    <div class="card-body">
        <div class="card-header">
            <h5 class="card-title">Tambah Potongan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('pajak_penghasilan.index') }}">Penghasilan </a> > Tambah Potongan</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row m-0 ml-0">
            {{-- <div class="col">
                <a class="mb-3" href="{{ route('import-penghasilan-index') }}">
                    <button class="btn btn-primary">Import penghasilan</button>
                </a>
            </div> --}}
        </div>
        <form action="{{ route('potongan.store') }}" method="POST" enctype="multipart/form-data">
            <div class="row m-0">
                @csrf
                <div class="col-md-6 form-group">
                    <label for="nip">Karyawan:</label>
                    <select name="nip" id="nip" class="form-control" required></select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    <div id="month_year">
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tanggal">Kredit Koperasi:</label>
                    <input type="text" name="kredit_koperasi" class="form-control rupiah" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="nominal">Iuran Koperasi:</label>
                    <input type="text" name="iuran_koperasi" class="form-control rupiah2" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="nominal">Kredit Pegawai:</label>
                    <input type="text" name="kredit_pegawai" class="form-control rupiah3" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="nominal">Iuran Ik:</label>
                    <input type="text" name="iuran_ik" class="form-control rupiah4" required>
                </div>
            </div>

            <div class="card-body">
                <div class="row m-0">
                    <button class="is-btn is-primary">Tambah</button>
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

        $('#tanggal').on('change', function () { 
          $('#month_year').empty();
          var tgl = document.getElementById('tanggal');
          var date = new Date(tgl.value);
          var year = moment(date).format('YYYY');
          var month = moment(date).format('MM');
          
          $('#month_year').append(`
            <input type="hidden" name="bulan" id="bulan" value="${month}">
            <input type="hidden" name="tahun" id="tahun" value="${year}">
          `);
        })

        $('#nip').on('change', function () { 

        })

        $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(".rupiah").val(formatRupiah(angka));
        })
        $('.rupiah2').keyup(function(){
            var angka = $(this).val();
            $(".rupiah2").val(formatRupiah(angka));
        })
        $('.rupiah3').keyup(function(){
            var angka = $(this).val();
            $(".rupiah3").val(formatRupiah(angka));
        })
        $('.rupiah4').keyup(function(){
            var angka = $(this).val();
            $(".rupiah4").val(formatRupiah(angka));
        })
    </script>
@endpush
