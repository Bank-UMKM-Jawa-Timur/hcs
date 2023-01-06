@extends('layouts.template')
@include('vendor.select2')
@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Penonaktifan Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="">Penonaktifan Karyawan </a></p>
        </div>
    </div>
</div>

<div class="card-body">
  <form action="{{ route('karyawan.penonaktifan') }}" method="post">
      @csrf
      <div class="row m-0">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Karyawan:</label>
                <select name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror"></select>
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="tanggal_penonaktifan">Tanggal Penonaktifan:</label>
                <input type="date" class="form-control @error('tanggal_penonaktifan') is-invalid @enderror" name="tanggal_penonaktifan" id="tanggal_penonaktifan">
                @error('tanggal_penonaktifan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        </div>
        <div class="col-md-4">
          <a href="penghasilan/gajipajak">
            <button class="btn btn-info" type="submit">Proses</button>
          </a>
        </div>
  </form>
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
</script>
@endpush
