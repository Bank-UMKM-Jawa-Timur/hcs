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
                            <th>No</th>
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
                              $num = 0;
                              $no = 1;
                          @endphp
                          <tbody>
                            @foreach ($data_pusat as $item)
                              @php
                                $jabatan = 'Pusat';
                              @endphp
                                <tr>
                                    <td>
                                      @php
                                          $num = $no++;
                                      @endphp
                                      {{ $num }}
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
                            {{-- Foreach Data selain Pusat --}}
                            @foreach ($cabang as $item)
                                @php
                                    $data_cabang = DB::table('mst_karyawan')
                                    ->where('kd_entitas', $item->kd_cabang)
                                    ->select(
                                        'mst_karyawan.nip',
                                        'mst_karyawan.nik',
                                        'mst_karyawan.nama_karyawan',
                                        'mst_karyawan.kd_entitas',
                                        'mst_karyawan.kd_jabatan',
                                        'mst_karyawan.kd_bagian',
                                        'mst_karyawan.ket_jabatan',
                                        'mst_karyawan.status_karyawan',
                                        'mst_jabatan.nama_jabatan',
                                        'mst_karyawan.status_jabatan',
                                    )
                                    ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
                                    ->orderBy('kd_jabatan', 'desc')
                                    ->get();
                                @endphp

                                @foreach ($data_cabang as $i)
                                    <tr>
                                      <td>{{ $num++ }}</td>
                                      <td>{{ $i->nip }}</td>
                                      <td>{{ $i->nik }}</td>
                                      <td>{{ $i->nama_karyawan }}</td>
                                      <td>
                                        @php
                                            $data = DB::table('mst_cabang')
                                              ->where('kd_cabang', $i->kd_entitas)
                                              ->first();

                                              if (isset($data)) {
                                                $data = $data->nama_cabang;
                                              } 
                                        @endphp
                                        {{ $data }}
                                      </td>
                                      <td>
                                        @php
                                            $ket = null;
                                            if($i->ket_jabatan != null){
                                              $ket = ' ('.$i->ket_jabatan.')';
                                            }
                                            $st_jabatan = DB::table('mst_jabatan')
                                              ->where('kd_jabatan', $i->kd_jabatan)
                                              ->first();
  
                                            $bagian = '';
                                            if ($i->kd_bagian != null) {
                                              $bagian1 = DB::table('mst_bagian')
                                                ->select('nama_bagian')
                                                ->where('kd_bagian', $i->kd_bagian)
                                                ->first();
  
                                                if (isset($bagian1)) {
                                                  $bagian = $bagian1->nama_bagian;
                                                }
                                            }
                                        @endphp
  
                                        @if ($i->status_jabatan == "Penjabat")
                                            Pj.{{ $i->nama_jabatan . ' ' . $bagian.$ket }} 
                                        @elseif($i->status_jabatan == "Penjabat Sementara")
                                            Pjs.{{ $i->nama_jabatan . ' ' . $bagian.$ket }} 
                                        @else
                                        {{ $i->nama_jabatan . ' ' . $bagian.$ket }} 
                                        @endif
                                      </td>
                                      <td style="min-width: 105px">
                                        <div class="container">
                                          <div class="row">
                                            <a href="{{ route('karyawan.edit', $i->nip) }}">
                                              <button class="btn btn-outline-warning p-1 mr-2" style="min-width: 60px">
                                                Edit
                                              </button>
                                            </a>
    
                                            <a href="{{ route('karyawan.show', $i->nip) }}">
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