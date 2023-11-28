@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Show Roles</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('role.index') }}">Roles</a> > <a>Show Data</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <label for="name">Role</label>
                <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" id="name" value="{{ old('role', $data->name) }}" placeholder="Nama Role" readonly>

                <div class="position-relative form-group mt-4">
                    <label for="Hak Akses">Hak Akses</label>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableHakAkses">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selected as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
