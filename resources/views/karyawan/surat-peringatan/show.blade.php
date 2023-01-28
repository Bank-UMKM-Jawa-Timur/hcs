@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Detail Surat Peringatan (SP)</h5>
            <p class="card-title">
                <a href="/">Dashboard </a> >
                <a href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a> >
                <a href="">Detail</a>
            </p>
        </div>
    </div>
</div>
<div class="card-body">
    @include('karyawan.surat-peringatan.form', ['ro' => true])
</div>
@endsection
