@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/pangkat_golongan">Pangkat dan Golongan </a> > Edit</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="container">
                    <form action="{{ route('pangkat_golongan.store') }}" class="form-group" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="pangkat">Pangkat</label>
                                <input type="text" name="pangkat" id="pangkat" class="form-control">
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="Golongan">Golongan</label>
                                <input type="text" name="golongan" id="golongan" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-info" value="submit" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection