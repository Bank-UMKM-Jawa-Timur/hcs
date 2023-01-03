@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Pembayaran Gaji Bulanan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > Pembayaran Gaji Bulanan</p>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    <div class="card-body">
        <form action="{{ route('gaji_perbulan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row m-0">
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_bulan">Tahun</label>
                        <select name="tahun" class="form-control">
                            <option value="">--- Pilih Tahun ---</option>
                            @foreach (range(date('Y'), $earliest_year) as $x)
                                <option value="{{ $x }}">{{ $x }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-5">
                    <button class="btn btn-info">proses</button>
                </div>
            </div>
        </form>
    </div>
@endsection