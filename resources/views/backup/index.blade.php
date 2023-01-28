@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Backup Database</h5>
            <p class="card-title"><a href="/">Dashboard</a> > Backup Database</p>
        </div>
    </div>

    <h1>{{ \Illuminate\Support\Facades\Artisan::call('inspire') }}</h1>
    <div class="card-body">
        <form action="{{ route('backup.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <button class="btn btn-info">Backup Database</button>
        </form>
    </div>
    
@endsection