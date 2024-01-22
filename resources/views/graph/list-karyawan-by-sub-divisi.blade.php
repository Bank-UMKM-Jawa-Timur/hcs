@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Karyawan Sub Divisi {{$sub_div}}</h5>
                    <p class="card-title"><a href="/">Dashboard </a> > <a href="">Karyawan Per Sub Divisi</a> </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 p-4">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class="text-primary">
                            <th>No</th>
                            <th>NIP</th>
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Kantor</th>
                            <th>Jabatan</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->nip}}</td>
                                    <td>{{$item->nik ? $item->nik : '-'}}</td>
                                    <td>{{$item->nama_karyawan}}</td>
                                    <td>{{$item->kantor}}</td>
                                    <td>{{$item->jabatan ? $item->jabatan : '-'}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Data karyawan belum ada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
