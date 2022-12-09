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
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                              NIP
                            </th>
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
                                Aksi
                            </th>
                          </thead>
                          @php
                              $no = 1;
                          @endphp
                          <tbody>
                            @foreach ($data as $item)
                              @php
                                $jabatan = 'Pusat';
                                $kd_ent = null;
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
                                  $jabatan = 'Pusat';
                                } else if (isset($data2)) {
                                  $jabatan = 'Pusat';
                                } else if (isset($data3)) {
                                  $jabatan = $data3->kd_cabang;
                                } else if ($item->kd_bagian) {
                                  $jabatan = 'Pusat';
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
                                      {{ $jabatan }}
                                    </td>
                                    <td>
                                      @php
                                          $ket = null;
                                          if($item->ket_jabatan != null){
                                            $ket = ' ('.$item->ket_jabatan.')';
                                          }
                                          $st_jabatan = DB::table('mst_jabatan')
                                            ->where('kd_jabatan', $item->kd_jabatan)
                                            ->first();

                                          $bagian = '';
                                          if ($item->kd_bagian != null) {
                                            $bagian1 = DB::table('mst_bagian')
                                              ->select('nama_bagian')
                                              ->where('kd_bagian', $item->kd_bagian)
                                              ->first();

                                              if (isset($bagian1)) {
                                                $bagian = $bagian1->nama_bagian;
                                              }
                                          }
                                      @endphp

                                      @if ($item->status_jabatan == "Penjabat")
                                          Pj.{{ $item->nama_jabatan . ' ' . $bagian.$ket }} 
                                      @elseif($item->status_jabatan == "Penjabat Sementara")
                                          Pjs.{{ $item->nama_jabatan . ' ' . $bagian.$ket }} 
                                      @else
                                      {{ $item->nama_jabatan . ' ' . $bagian.$ket }} 
                                      @endif
                                    </td>
                                    <td style="min-width: 130px">
                                      <div class="container">
                                        <div class="row">
                                          <a href="{{ route('karyawan.edit', $item->nip) }}">
                                            <button class="btn btn-outline-warning p-1 mr-2" style="min-width: 60px">
                                              Edit
                                            </button>
                                          </a>
  
                                          <a href="{{ route('karyawan.show', $item->nip) }}">
                                            <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                              Detail
                                            </button>
                                          </a>
                                        </div>
                                      </div>

                                        {{-- <form action="{{ route('karyawan.destroy', $item->nip) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                      
                                          <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                        </form> --}}
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