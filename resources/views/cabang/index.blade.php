@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Data Kantor Cabang</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</p>
        </div>
        
        <div class="card-body">
            <div class="col">
                <div class="row">
                  <div class="pt-3 pb-3">
                    <a class="mb-3" href="{{ route('cabang.create') }}">
                      <button class="is-btn is-primary">tambah cabang</button>
                    </a>
                  </div>
                    <div class="table-responsive overflow-hidden content-center">
                      <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class=" text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Nama Cabang
                            </th>
                            <th>
                                Alamat
                            </th>
                            <th class="text-center">
                                Aksi
                            </th>
                          </thead>
                          @php
                              $no = 1;
                          @endphp
                          <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        {{ $no++ }}
                                    </td>
                                    <td>
                                        {{ $item->nama_cabang }}
                                    </td>
                                    <td>
                                        {{ $item->alamat_cabang }}
                                    </td>
                                    <td class="text-center">
                                      {{-- <div class="row"> --}}
                                        <p style="margin-bottom: 0.4rem !important;">
                                          <a href="{{ route('cabang.edit', $item->kd_cabang) }}">
                                            <button class="btn btn-warning">
                                              @if ($item->kode_cabang_profil) Edit @else Lengkapi Profil Kantor @endif
                                            </button>
                                          </a>
                                        </p>
                                        @if ($item->kode_cabang_profil)
                                          <a href="{{ route('penambahan-bruto.index') }}?profil_kantor={{$item->profil_id}}" class="mt-2">
                                            <button class="btn btn-info">
                                              Master Penambahan Bruto
                                            </button>
                                          </a>
                                          <a href="{{ route('pengurangan-bruto.index') }}?profil_kantor={{$item->profil_id}}">
                                            <button class="btn btn-info">
                                              Master Pengurangan Bruto
                                            </button>
                                          </a>
                                        @endif
                                        {{-- <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                      
                                          <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                        </form> --}}
                                      {{-- </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                          </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
  <script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
    });
  </script>
@endsection 