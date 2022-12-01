@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/karyawan">Karyawan </a></p>
        </div>
    
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('karyawan.create') }}">
                      <button class="btn btn-primary">tambah karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('import') }}">
                      <button class="btn btn-primary">import karyawan</button>
                    </a>
                    <div class="table-responsive overflow-scroll">
                        <table class="table" id="table">
                          <thead class=" text-primary">
                            <th>
                                No
                            </th>
                            <th>NIP</th>
                            <th>
                              NIK
                            </th>
                            <th>
                                Nama karyawan
                            </th>
                            <th>
                              Kantor
                            </th>
                            <th>
                              Jabatan
                            </th>
                            <th>
                              Bagian
                            </th>
                            <th>
                                Aksi
                            </th>
                          </thead>
                          @php
                              $no = 1;
                          @endphp
                          <tbody>
                            @foreach ($data as $item)
                              @php
                                $jabatan = null;
                                $data1 = DB::table('mst_divisi')
                                    ->where('kd_divisi', $item->kd_entitas)
                                    ->first();
                                $data2 = DB::table('mst_sub_divisi')
                                    ->where('kd_subdiv', $item->kd_entitas)
                                    ->first();
                                $data3 = DB::table('mst_cabang')
                                    ->where('kd_cabang', $item->kd_entitas)
                                    ->first();

                                if (isset($data1)) {
                                  $jabatan = $data1->nama_divisi;
                                } else if (isset($data2)) {
                                  $jabatan = $data2->nama_subdivisi;
                                } else if (isset($data3)) {
                                  $jabatan = $data3->nama_cabang;
                                }
                              @endphp
                                <tr>
                                    <td>
                                        {{ $no++ }}
                                    </td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->nik }}</td>
                                    <td>
                                      {{ $item->nama_karyawan }}
                                    </td>
                                    <td>
                                      @php
                                          $kantor = 'Pusat';
                                          if ($item->kd_bagian == null) {
                                            $kantor = 'Cabang';
                                          }
                                      @endphp
                                      {{ $kantor }}
                                    </td>
                                    <td>
                                      @php
                                          $ket = null;
                                          if($item->ket_jabatan != null){
                                            $ket = ' ('.$item->ket_jabatan.')';
                                          }
                                      @endphp

                                      @if ($item->status_jabatan == "Penjabat")
                                          Pj.{{ $item->nama_jabatan }} 
                                      @elseif($item->status_jabatan == "Penjabat Sementara")
                                          Pjs.{{ $item->nama_jabatan  }} 
                                      @else
                                      {{ $item->nama_jabatan }} 
                                      @endif
                                    </td>
                                    <td>
                                      @php
                                          $st_jabatan = DB::table('mst_jabatan')
                                            ->where('kd_jabatan', $item->kd_jabatan)
                                            ->first();

                                          $bagian = '-';
                                          if ($item->kd_bagian != null) {
                                            $bagian = DB::table('mst_bagian')
                                              ->where('kd_bagian', $item->kd_bagian)
                                              ->first();

                                            $bagian = $bagian->nama_bagian;
                                          }
                                      @endphp
                                      {{ $bagian.$ket }}
                                    </td>
                                    <td>
                                      <div class="row">
                                        <a href="{{ route('karyawan.edit', $item->nip) }}">
                                          <button class="btn btn-warning">
                                            Edit
                                          </button>
                                        </a>
                                        
                                        {{-- <form action="{{ route('karyawan.destroy', $item->nip) }}" method="POST">
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
        </div>
    </div>
@endsection

@section('custom_script')
  <script>
    $(document).ready( function () {
      $('#table').DataTable();
    });
  </script>
@endsection 