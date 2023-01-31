@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Detail Surat Peringatan (SP)</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="">Reward & Punishment</a> > <a href="/surat-peringatan">Surat Peringatan</a> > Detail</p>
        </div>
    </div>
</div>
<div class="card-body">
    @include('karyawan.surat-peringatan.form', ['ro' => true])
</div>
@endsection
