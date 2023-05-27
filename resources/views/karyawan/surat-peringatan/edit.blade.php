@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Edit Surat Peringatan (SP)</h5>
            <p class="card-title"><a href="">Manajemen Karyawan </a> > <a href="">Reward & Punishment</a> > <a href="{{ route('surat-peringatan.index') }}">Surat Peringatan</a> > Edit</p>
        </div>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('surat-peringatan.update', $sp) }}" method="post">
        @method('PUT')
        @include('karyawan.surat-peringatan.form')
    </form>
</div>
@endsection
