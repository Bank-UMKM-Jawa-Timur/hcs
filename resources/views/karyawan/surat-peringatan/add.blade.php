@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="lg:flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Tambah Surat Peringatan (SP)</h2>
            <div class="breadcrumb">
                <a href="{{route('surat-peringatan.index')}}" class="text-sm text-gray-500">Surat Peringatan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Tambah</a>
            </div>
        </div>
    </div>
</div>
<div class="p-5">
    <div class="card">
        <form action="{{ route('surat-peringatan.store') }}" enctype="multipart/form-data" method="post">
            @include('karyawan.surat-peringatan.form', ['sp' => null, 'ro' => false])
        </form>
    </div>
</div>
@endsection
