@extends('layouts.template')

@section('content')
<div class="card-header">
  <div class="card-header">
      <h5 class="card-title">Cari Data Karyawan</h5>
  </div>
</div>

<div class="card-body">
    <div class="row m-0">
        <div class="col">
            <a class="mb-3" href="{{ route('penghasilan.create') }}">
                <button class="btn btn-primary">tambah penghasilan</button>
            </a>
        </div>
    </div>
  <form action="{{ route('get-penghasilan') }}" method="post">
      @csrf
      <div class="row m-0">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="">NIP</label>
                  <input type="text" class="@error('nip') is-invalid @enderror form-control" name="nip" id="nip" value="{{ old('nip') }}">
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="">NIP</label>
                  <input type="text" class="form-control" name="nama" id="nama" value="" disabled>
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

    $("#nip").change(function(e){
        var nip = $(this).val();

        $.ajax({
            url: "/getdatapromosi?nip="+nip,
            type: "GET",
            datatype: "json",
            success: function(res){
                $("#nama").val(res.nama_karyawan)
            }
        })
    })
  </script>
@endsection 