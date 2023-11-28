@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Bonus</h5>
            <p class="card-title"><a href="{{ route('karyawan.index') }}">Dashboard</a> > Penghasilan Lainnya</p>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-center">
                <a class="ml-3" href="{{ route('penghasilan-lainnya.create') }}">
                    <button class="btn btn-primary">Import Penghasilan Lainnya</button>
                </a>
            </div>
        </div>
@endsection

