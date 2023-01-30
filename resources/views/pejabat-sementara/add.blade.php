@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-title">
        <h5 class="card-title">Tambah Pejabat Sementara (PJS)</h5>
        <p class="card-title">
            <a href="/">Dashboard </a> >
            <a href="{{ route('pejabat-sementara.index') }}">Pejabat Sementara</a> >
            Tambah
        </p>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('pejabat-sementara.store') }}" method="post" enctype="multipart/form-data">
        @include('pejabat-sementara.form')
    </form>
</div>
@endsection
