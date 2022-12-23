@extends('layouts.template')

@section('content')
<div class="card-header">
  <div class="card-header">
      <h5 class="card-title">Cari Data Karyawan</h5>
  </div>
</div>

<div class="card-body">
  <form action="" method="post">
      @csrf
      <div class="row m-0">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="">NIP</label>
                  <input type="text" class="@error('nip') is-invalid @enderror form-control" name="nip" id="nip" value="{{ old('nip') }}">
              </div>
          </div>
          @php
              $already_selected_value = 2022;
              $earliest_year = 2010;
          @endphp
          <div class="col-md-4">
              <label for="tahun">Tahun</label>
              <div class="form-group">
                  <select name="tahun" class="form-control">
                      <option value="">--- Pilih Tahun ---</option>
                      @foreach (range(date('Y'), $earliest_year) as $x)
                          <option value="{{ $x }}">{{ $x }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="Bulan">Bulan</label>
                  <select name="bulan" class="form-control">
                      <option value="">--- Pilih Bulan ---</option>
                      <option value='1'>Januari</option>
                      <option value='2'>Februari </option>
                      <option value='3'>Maret</option>
                      <option value='4'>April</option>
                      <option value='5'>Mei</option>
                      <option value='6'>Juni</option>
                      <option value='7'>Juli</option>
                      <option value='8'>Agustus</option>
                      <option value='9'>September</option>
                      <option value='10'>Oktober</option>
                      <option value='11'>November</option>
                      <option value='12'>Desember</option>
                  </select>
              </div>
          </div>
        </div>
        <div class="col-md-4">
          <a href="penghasilan/gajipajak">
            <button class="btn btn-info" type="submit">Tampilkan</button>
          </a>
        </div>
@endsection

@section('custom_script')
  <script>
   $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
    });
  </script>
@endsection 