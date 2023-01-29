@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Pajak Penghasilan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="">Pajak Penghasilan </a></p>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row m-0">
        <div class="col">
            <a class="mb-3" href="{{ route('pajak_penghasilan.create') }}">
                <button class="btn btn-primary">Import penghasilan</button>
            </a>
        </div>
    </div>
    <div class="row m-0">
        <div class="col">
            <hr>
            <h5>Cari Karyawan</h5>
        </div>
    </div>
  <form action="{{ route('get-penghasilan') }}" method="post">
      @csrf
      <div class="row m-0">
          <div class="col-lg-4">
              <div class="form-group">
                  <label for="">Karyawan:</label>
                  <select name="nip" id="nip" class="form-control"></select>
              </div>
          </div>
          <div class="col-lg-4">
            <label for="mode">Mode Lihat Data</label>
            <div class="form-group">
                <select name="mode" class="form-control">
                    <option value="">--- Pilih Mode ---</option>
                    <option value="1">Bukti Pembayaran Gaji Pajak</option>
                    <option value="2">Detail Gaji Pajak</option>
                </select>
            </div>
          </div>
          @php
            $already_selected_value = date('y');
            $earliest_year = 2022;
          @endphp
          <div class="col-lg-4">
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
        <div class="col-lg-4">
          <a href="penghasilan/gajipajak">
            <button class="btn btn-info" type="submit">Tampilkan</button>
          </a>
        </div>
@endsection

@push('script')
<script>
// $(document).ready(function() {
//     var table = $('#table').DataTable({
//         'autoWidth': false,
//         'dom': 'Rlfrtip',
//         'colReorder': {
//             'allowReorder': false
//         }
//     });
// })

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
</script>
@endpush
