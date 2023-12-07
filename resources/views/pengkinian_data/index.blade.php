@extends('layouts.template')

@section('content')

      <div class="card-header">
        <div class="d-lg-flex justify-content-between w-100 ">
          <div class="card-header">
              <h5 class="card-title font-weight-bold">Pengkinian Data Karyawan</h5>
              <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > Pengkinian Data</p>
          </div>
          <div class="card-header row mt-3 mr-8 pr-4" >

              <a class="mb-3" href="{{ route('pengkinian_data.create') }}">
                <button class="is-btn is-primary">Pengkinian Data</button>
              </a>
              <a class="ml-3" href="{{ route('pengkinian-data-import-index') }}">
                <button class="is-btn is-primary">Import Pengkinian</button>
              </a>
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
                          @foreach ($data as $item)
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
                                <a href="{{ route('pengkinian_data.show', $item->nip) }}">
                                  <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                    Detail
                                  </button>
                                </a>
                              </div>
                            </div>
                          </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
              @include('components.pagination.table-info', [
                'obj' => $data,
                'page_length' => $pagination['page_length'],
                'start' => $pagination['start'],
                'end' => $pagination['end']
              ])
            </form>
          </div>
        </div>
      </div>
  </div>
@endsection

@section('custom_script')
  <script>
    $('#page_length').on('change', function() {
      $(".loader-wrapper").removeAttr("style"); // show loading
      $('#form').submit()
    })
    
    // Adjust pagination url
    var btn_pagination = $(`.pagination`).find('a')
    var page_url = window.location.href
    $(`.pagination`).find('a').each(function(i, obj) {
        if (page_url.includes('page_length')) {
            btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
        }
        if (page_url.includes('q')) {
            btn_pagination[i].href += `&q=${$('#q').val()}`
        }
    })
  </script>
@endsection
