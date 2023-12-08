@extends('layouts.template')

@section('content')
    @php
        $bulanNama = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    @endphp
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Input dan Import Potongan</h5>
            <p class="card-title"><a href="/">Dashboard</a> >Import Potongan</p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('potongan.create') }}">
                      <button class="btn btn-primary">Tampah Potongan</button>
                    </a>
                    <a class="ml-3" href="{{ route('import-potongan') }}">
                      <button class="btn btn-primary">Import Potongan</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                      <form id="form" method="get">
                        <div class="d-flex justify-content-between mb-4">
                          <div class="p-2 mt-4">
                            <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                            <select name="page_length" id="page_length"
                                class="border px-4 py-2 cursor-pointer rounded appearance-none text-center">
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
                          <div class="p-2">
                            <label for="q">Cari</label>
                            <input type="search" name="q" id="q" placeholder="Cari disini..."
                              class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                          </div>
                        </div>
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class="text-primary">
                            <th>No</th>
                            <th>
                                Bulan
                            </th>
                            <th>
                                Tahun
                            </th>
                            <th>
                              JP
                            </th>
                            <th>
                              DPP
                            </th>
                            <th>
                              Kredit Koprasi
                            </th>
                            <th>
                              Iuran Koprasi
                            </th>
                            <th>
                              Kredit Pegawai
                            </th>
                            <th>
                              Iuran IK
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
                            @php
                              $page = isset($_GET['page']) ? $_GET['page'] : 1;
                              $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                              $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                              $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                              $i = $page == 1 ? 1 : $start;
                            @endphp
                            @foreach ($data as $item)
                                <tr>
                                  <td>{{ $i++ }}</td>
                                  <td>{{ $bulanNama[$item->bulan] }}</td>
                                  <td>{{ $item->tahun }}</td>
                                  <td> - </td>
                                  <td> - </td>
                                  <td>Rp {{ number_format($item->kredit_koperasi,0,',','.') }}</td>
                                  <td>Rp {{ number_format($item->iuran_koperasi,0,',','.') }}</td>
                                  <td>Rp {{ number_format($item->kredit_pegawai,0,',','.') }}</td>
                                  <td>Rp {{ number_format($item->iuran_ik,0,',','.') }}</td>
                                  <td style="min-width: 130px">
                                    <div class="container">
                                        <div class="row">
                                            <a href="{{ route('detail-potongan', ['bulan' => $item->bulan, 'tahun' => $item->tahun]) }}"
                                                class="btn btn-info p-1"
                                                style="min-width: 60px">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                  </td>
                                </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </form>
                      <div class="d-flex justify-content-between">
                        <div>
                          Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                        </div>
                        <div>
                          @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                          {{ $data->links('pagination::bootstrap-4') }}
                          @endif
                        </div>
                      </div>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script>
  $('#page_length').on('change', function() {
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
