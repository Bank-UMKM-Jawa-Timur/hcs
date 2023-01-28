@extends('layouts.template')

@php
    $firstYear = $firstData?->date?->format('Y') ?? date('Y');
@endphp

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Laporan Surat Peringatan</h5>
            <p class="card-title">
                <a href="/">Dashboard </a> >
                <a href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a> >
                <a href="">Laporan</a>
            </p>
        </div>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('surat-peringatan.report') }}" method="get">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="year">Tahun</label>
                <select name="tahun" id="year" class="form-control">
                    <option value="">-- Semua --</option>
                    @for ($year = $firstYear; $year <= date('Y'); $year++)
                        <option value="{{ $year }}" @selected($year == $request->tahun)>{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </div>
    </form>

    <div class="card m-3 shadow">
        <div class="table-responsive overflow-hidden pt-2">
            <table class="table text-center cell-border stripe" id="sp-table" style="width: 100%;">
                <thead style="background-color: #CCD6A6;">
                    <tr>
                        <th class="text-center">No. SP</th>
                        <th class="text-center">Tanggal SP</th>
                        <th class="text-center">Karyawan</th>
                        <th class="text-center">Pelanggaran</th>
                        <th class="text-center">Sanksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($report as $sp)
                    <tr>
                        <td>{{ $sp->no_sp }}</td>
                        <td>{{ $sp->tanggal_sp->format('d/m/Y') }}</td>
                        <td>{{ $sp->karyawan->nama_karyawan }}</td>
                        <td>{{ $sp->pelanggaran }}</td>
                        <td>{{ $sp->sanksi }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#sp-table').DataTable();
</script>
@endpush
