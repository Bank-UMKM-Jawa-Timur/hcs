@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
      <div class="card-header">
        <p class="card-title"><a href="/">Dashboard </a> > <a href="/divisi">Divisi </a> > Data Master</p>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive overflow-hidden">
        <table class="table">
          <thead class=" text-primary">
            <th>
                Id Divisi
            </th>
            <th>
                Nama Divisi
            </th>
            <th>
                Aksi
            </th>
          </thead>
          <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>
                        {{ $item['id'] }}
                    </td>
                    <td>
                        {{ $item['nama_divisi'] }}
                    </td>
                    <td>
                        <button class="btn btn-warning">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection