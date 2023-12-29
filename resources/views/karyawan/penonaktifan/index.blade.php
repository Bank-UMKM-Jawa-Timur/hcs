@extends('layouts.template')
@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
  <div class="card-header">
    <h5 class="card-title">Penonaktifan Karyawan</h5>
    <p class="card-title"><a href="{{ route('karyawan.index') }}">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > Penonaktifan Karyawan</p>
  </div>
  <div class="card-header row mt-3 mr-8 pr-5">
    @if (auth()->user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan'))
      <a class="mb-3" href="{{ route('penonaktifan.create') }}">
        <button class="is-btn is-primary">tambah penonaktifan</button>
      </a>
    @endif
  </div>
</div>

    <div class="card-body p-3">
        <div class="col">
            <form id="form" method="get">
              <div class="row">
              <div class="col-lg-12">
                <div class="d-flex justify-content-between mb-4">
                  <div class="p-2 mt-4 w-100">
                      <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                      <select name="page_length" id="page_length"
                          class="border px-2 py-2 cursor-pointer rounded appearance-none text-center">
                          <option value="10"
                              @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                              10</option>
                          <option value="20"
                              @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                              20</option>
                          <option value="50"
                              @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                              50</option>
                          <option value="100"
                              @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                              100</option>
                      </select>
                      <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                  </div>
                  <div class="p-2 w-25">
                      <label for="q">Cari</label>
                      <input type="search" name="q" id="q" placeholder="Cari disini..."
                          class="form-control p-2" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                  </div>
                </div>
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
                          Kantor Terakhir
                        </th>
                        <th>
                          Jabatan Terakhir
                        </th>
                        <th style="text-align: center">Kategori <br>Penonaktifan</th>
                        <th style="text-align: center">Tanggal Penonaktifan</th>
                      </thead>
                      <tbody>
                        @php
                          $page = isset($_GET['page']) ? $_GET['page'] : 1;
                          $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                          $start = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                          $end = $page == 1 ? $page_length : $start + $page_length - 1;
                          $i = $page == 1 ? 1 : $start;
                        @endphp
                        @foreach ($karyawan as $krywn)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $krywn->nip }}</td>
                                <td>{{ $krywn->nik }}</td>
                                <td>{{ $krywn->nama_karyawan }}</td>
                                <td>{{$krywn->kantor_terakhir}}</td>
                                <td>{{ $krywn->prefix . $krywn->jabatan_result }} {{ $krywn->entitas_result }} {{ $krywn?->bagian?->nama_bagian }} {{ $krywn->ket }}</td>
                                <td>{{ $krywn->kategori_penonaktifan ?? '-' }}</td>
                                <td>{{ $krywn->tanggal_penonaktifan != null ? date('d M Y', strtotime($krywn->tanggal_penonaktifan)) : '-' }}</td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                      <div>
                          Showing {{ $start }} to {{ $end }} of {{ $karyawan->total() }} entries
                      </div>
                      <div>
                          @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                              {{ $karyawan->links('pagination::bootstrap-4') }}
                          @endif
                      </div>
                  </div>
                </div>
              </div>
              </div>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        // Adjust pagination url
        var btn_pagination = $('.pagination').find('a')
        var page_url = window.location.href
        $('.pagination').find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endsection