@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Tambah Surat Peringatan (SP)</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="">Reward & Punishment</a> > <a href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a> > Tambah</p>
        </div>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('surat-peringatan.store') }}" method="post">
        @include('karyawan.surat-peringatan.form', ['sp' => null])
    </form>
</div>
@endsection
