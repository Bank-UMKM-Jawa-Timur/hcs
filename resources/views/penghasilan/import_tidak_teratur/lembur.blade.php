@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="card-header">
  <div class="card-header">
      <h5 class="card-title">Lembur</h5>
      <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('list-penghasilan-tidak-teratur') }}">Penghasilan </a> > Import Tidak Teratur > Lembur</p>
  </div>

  <div class="card-body">
      {{-- <div class="row m-0">
          <div class="col">
              <a class="mb-3" href="{{ route('import-penghasilan-index') }}">
                  <button class="btn btn-primary">Import penghasilan</button>
              </a>
          </div>
      </div> --}}
      <form action="{{ route('lembur.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <div class="row m-0">
              <div class="col-md-6 form-group">
                  <label for="nip">Karyawan:</label>
                  <select name="nip" id="nip" class="form-control" required></select>
              </div>
              <div class="col-md-6 form-group" id="grup-date">
                <label for="tanggal">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control">
              </div>
              <div class="col-md-6 form-group">
                  <label for="nominal">Nominal:</label>
                  <input type="text" name="nominal" class="form-control rupiah">
              </div>
              <input type="hidden" name="id_tunjangan" class="form-control" value="16" required>
          </div>

          <div class="row m-0">
              <div class="container">
                  <button type="submit" class="btn btn-info">Tambah</button>
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

      $(document).ready(function() {
            var inputTanggal = $('#tanggal');

            inputTanggal.on('change', function() {
                var tanggalValue = inputTanggal.val();
                
                var tahun = tanggalValue.substring(0, 4);
                var bulan = tanggalValue.substring(5, 7);

                $('#bulan_tahun').remove();

                $('#grup-date').append(`
                  <div class="" id="bulan_tahun"></div>
                `);

                $('#bulan_tahun').append(`
                  <input type="hidden" name="bulan" class="form-control" value="${bulan}">
                  <input type="hidden" name="tahun" class="form-control" value="${tahun}">
                `);
            });
        });
  </script>
@endpush