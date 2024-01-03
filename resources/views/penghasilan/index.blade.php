@extends('layouts.template')
@include('vendor.select2')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title font-weight-bold">Pajak Penghasilan</h5>
            <p class="card-title"><a href="">Penghasilan </a> > Pajak Penghasilan</p>
        </div>
    </div>
</div>

<div class="card-body mt-3">
    <div class="row m-0">
        <div class="col">
            <h6>Cari Karyawan</h6>
        </div>
    </div>
  <form action="{{ route('get-penghasilan') }}" method="post" class="mt-3">
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
        <div class="col-lg-4 pt-4 pb-4">
          <a href="penghasilan/gajipajak">
            <button class="is-btn is-primary" type="submit">Tampilkan</button>
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
        url: '{{ route('api.select2.karyawan') }}',
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
        if(data.loading) return data.text;
        return $(`
            <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
        `);
    }
});
</script>
@endpush
