@extends('layouts.template')

@section('content')
    <div class="card-header">
        <h5 class="card-title">Data Penghasilan Teratur</h5>
        <p class="card-title"><a href="">Penghasilan</a> > <a href="{{route('penghasilan.import-penghasilan-teratur.index')}}">Penghasilan Teratur</a> > Detail</p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <h6> Tunjangan : {{$tunjangan->nama_tunjangan}}</h6>
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
                                    NIP
                                </th>
                                <th>
                                    Karyawan
                                </th>
                                <th>
                                    Nominal
                                </th>
                                <th>
                                    Tanggal
                                </th>
                            </thead>
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
                                        <td>{{ $item->nip_tunjangan }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>{{ number_format($item->nominal, 0, ".", ",") }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    </form>
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
