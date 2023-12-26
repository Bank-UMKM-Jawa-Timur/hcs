@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
  <div class="card-header">
    <h5 class="card-title">Pengkinian Data Karyawan</h5>
    <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > Pengkinian Data</p>
  </div>
  <div class="card-header row mt-3 mr-8 pr-5">
    @if (auth()->user()->hasRole(['hrd']))
        <a class="mb-3" href="{{ route('pengkinian_data.create') }}">
        <button class="is-btn is-primary">Pengkinian Data</button>
        </a>
        <a class="ml-3" href="{{ route('pengkinian-data-import-index') }}">
        <button class="is-btn is-primary">Import Pengkinian</button>
        </a>
    @endif
  </div>
</div>
<div class="card-body p-3">
    <div class="col">
        <div class="row">
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
                                      if ($item->ket_jabatan != null) {
                                          $ket = ' (' . $item->ket_jabatan . ')';
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

                                  @if ($item->status_jabatan == 'Penjabat')
                                      Pj.{{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                  @elseif($item->status_jabatan == 'Penjabat Sementara')
                                      Pjs.{{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                  @else
                                      {{ $item->nama_jabatan . ' ' . $bagian . $ket }}
                                  @endif
                              </td>
                              <td style="min-width: 130px">
                                  <div class="container">
                                    @if (auth()->user()->hasRole(['hrd','admin']))
                                        <a href="{{ route('pengkinian_data.show', $item->nip) }}">
                                            <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                                Detail
                                            </button>
                                        </a>
                                    @endif
                                  </div>
                              </td>
                          </tr>
                        @endforeach
                        {{-- Foreach Data selain Pusat --}}
                        @foreach ($cabang as $item)
                            @php
                                $data_cabang = DB::select(
                                    "SELECT history_pengkinian_data_karyawan.id, history_pengkinian_data_karyawan.nip, history_pengkinian_data_karyawan.nik, history_pengkinian_data_karyawan.nama_karyawan, history_pengkinian_data_karyawan.kd_entitas, history_pengkinian_data_karyawan.kd_jabatan, history_pengkinian_data_karyawan.kd_bagian, history_pengkinian_data_karyawan.ket_jabatan, history_pengkinian_data_karyawan.status_karyawan, mst_jabatan.nama_jabatan, history_pengkinian_data_karyawan.status_jabatan FROM `history_pengkinian_data_karyawan` JOIN mst_jabatan ON mst_jabatan.kd_jabatan = history_pengkinian_data_karyawan.kd_jabatan WHERE history_pengkinian_data_karyawan.kd_entitas = '" .
                                        $item->kd_cabang .
                                        "' ORDER BY CASE WHEN history_pengkinian_data_karyawan.kd_jabatan='PIMDIV' THEN 1 WHEN history_pengkinian_data_karyawan.kd_jabatan='PSD' THEN 2 WHEN history_pengkinian_data_karyawan.kd_jabatan='PC' THEN 3 WHEN history_pengkinian_data_karyawan.kd_jabatan='PBO' THEN 4 WHEN history_pengkinian_data_karyawan.kd_jabatan='PBP' THEN 5 WHEN history_pengkinian_data_karyawan.kd_jabatan='PEN' THEN 6 WHEN history_pengkinian_data_karyawan.kd_jabatan='ST' THEN 7 WHEN history_pengkinian_data_karyawan.kd_jabatan='IKJP' THEN 8 WHEN history_pengkinian_data_karyawan.kd_jabatan='NST' THEN 9 END ASC",
                                );
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
                                            if ($i->ket_jabatan != null) {
                                                $ket = ' (' . $i->ket_jabatan . ')';
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

                                        @if ($i->status_jabatan == 'Penjabat')
                                            Pj.{{ $i->nama_jabatan . ' ' . $bagian . $ket }}
                                        @elseif($i->status_jabatan == 'Penjabat Sementara')
                                            Pjs.{{ $i->nama_jabatan . ' ' . $bagian . $ket }}
                                        @else
                                            {{ $i->nama_jabatan . ' ' . $bagian . $ket }}
                                        @endif
                                    </td>
                                    <td style="min-width: 130px">
                                        <div class="container">
                                            @if (auth()->user()->hasRole(['hrd','admin']))
                                                <a href="{{ route('pengkinian_data.show', $i->nip) }}">
                                                    <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                                        Detail
                                                    </button>
                                                </a>
                                            @endif
                                        </div>
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
