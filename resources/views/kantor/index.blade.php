@extends('layouts.template')

@section('content')
    
  <div class="card-header">
    <div class="card-header">
      <p class="card-title"><a href="/">Dashboard </a> - Kantor
    </div>
  </div>
  <div class="card-body">
    <a href="{{ route('kantor.create') }}">
      <button class="btn btn-primary">tambah kantor</button>
    </a>
    <div class="table-responsive overflow-hidden">
      <table class="table">
        <thead class=" text-primary">
          <th>
              Id Kantor
          </th>
          <th>
              Nama Kantor
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
                      {{ $item['nama_kantor'] }}
                  </td>
                  <td>
                    <div class="row">
                      <a href="kantor/{{ $item['id'] }}/edit">
                        <button class="btn btn-warning">
                          Edit
                        </button>
                      </a>
                      
                      {{-- <form action="{{ route('kantor.destroy', $item['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                    
                        <button type="submit" class="btn btn-danger btn-block">Delete</button>
                      </form> --}}
                    </div>
                  </td>
              </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection