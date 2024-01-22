@extends('layouts.app-template')
@section('content')
    <div class="p-5 space-y-7">
        <div class="pb-5">
            <h2 class="text-2xl font-bold">Button Component's</h2>
        </div>
        <div class="flex gap-5">
            <button class="btn btn-primary">Primary</button>
            <button class="btn btn-secondary">Secondary</button>
            <button class="btn btn-success">Success</button>
            <button class="btn btn-warning">Warning</button>
            <button class="btn btn-danger">Danger</button>
            <button class="btn btn-light">Light</button>
            <button class="btn btn-dark">Dark</button>
            <button class="btn btn-transparent">Transparent</button>
        </div>
        <div class="flex gap-5">
            <button class="btn btn-primary-light">Primary</button>
            <button class="btn btn-secondary-light">Secondary</button>
            <button class="btn btn-success-light">Success</button>
            <button class="btn btn-warning-light">Warning</button>
            <button class="btn btn-danger-light">Danger</button>
            
        </div>
    </div>
@endsection