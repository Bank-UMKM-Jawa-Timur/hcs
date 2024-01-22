@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Bonus
            </div>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('pajak_penghasilan.index') }}" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('bonus.index') }}" class="text-sm text-gray-500">Bonus</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Detail Bonus</a>
            </div>
        </div>
    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="">
                <h6 class="font-bold text-gray-400"> Tunjangan : <b class="text-black">{{$tunjangan->nama_tunjangan}}</b></h6>
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        <input type="hidden" name="tanggal" id="tanggal" value="{{\Request::get('tanggal')}}">
                        <input type="hidden" name="entitas" id="entitas" value="{{\Request::get('entitas')}}">
                        <div class="layout-component">
                          <div class="shorty-table">
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
                          <div class="input-search">
                            <i class="ti ti-search"></i>
                            <input type="search" name="q" id="q" placeholder="Cari disini..."
                              class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                          </div>
                        </div>
                        <table class="tables whitespace-nowrap" id="table" style="width: 100%">
                            <thead class="text-primary">
                                <th>No</th>
                                <th>NIP</th>
                                <th>Karyawan</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
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
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td>Rp {{ number_format($item->nominal, 0,',','.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="table-footer">
                            <div class="showing">
                                Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                            </div>
                            <div class="pagination">
                                @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $data->links('pagination::tailwind') }}
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
            if (page_url.includes('tanggal')) {
                btn_pagination[i].href += `&tanggal=${$('#tanggal').val()}`
            }
            if (page_url.includes('entitas')) {
                btn_pagination[i].href += `&entitas=${$('#entitas').val()}`
            }
        })
    </script>
@endsection
