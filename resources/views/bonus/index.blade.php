@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Bonus</h5>
            <p class="card-title"><a href="{{ route('karyawan.index') }}">Dashboard</a> > Bonus</p>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-start">
                <a class="" href="{{ route('bonus.create') }}">
                    <button class="btn btn-primary">Import Data</button>
                </a>
            </div>
        </div>
@endsection

