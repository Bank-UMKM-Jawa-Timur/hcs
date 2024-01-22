@extends('layouts.app-template')

@section('modal')

@include('components.modal.modal')

@endsection

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Modal</h2>
            <div class="breadcrumb">
             <a href="#" class="text-sm text-gray-500">Component</a>
             <i class="ti ti-circle-filled text-theme-primary"></i>
             <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Modal</a>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <button class="btn btn-primary" data-modal-id="default-modal" data-modal-toggle="modal">Default Modal</button>
    <button class="btn btn-primary" data-modal-id="default-no-backdrop" data-modal-toggle="modal">No Backdrop Click</button>
</div>
@endsection