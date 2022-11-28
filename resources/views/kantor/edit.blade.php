@extends('layouts.template')

@section('content') 
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Edit Kantor</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/kantor">Kantor </a> > Edit {{ $dataKantor['nama_kantor'] }}</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('kantor.update', $dataKantor['id']) }}" id="kantor" method="POST" enctype="multipart/form-data" class="form-group">
                    @csrf
                    @method('PUT')
                    <label for="nama_kantor">Nama Kantor</label>
                    <input type="text" name="nama_kantor" id="nama_kantor" class="form-control" value="{{ $dataKantor['nama_kantor'] }}">
                    <input type="hidden" name="id" value="{{ $dataKantor['id'] }}">

                    <button class="btn btn-info" type="submit">edit</button>
                </form>
            </div>
        </div>
    </div>
@endsection