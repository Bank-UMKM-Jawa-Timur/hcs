@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Penghasilan Teratur</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{route('penghasilan.import-penghasilan-teratur.index')}}"
                    class="text-sm text-gray-500 font-bold">Penghasilan Teratur </a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500">Detail</p>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form id="form" action="">
            {{-- <h6 class="text-lg text-neutral-600"> Tunjangan : {{$tunjangan->nama_tunjangan}}</h6> --}}
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex gap-7" role="alert">
                <h6 class="text-sm text-blue-900 font-semibold"> Tunjangan : <b>{{$tunjangan->nama_tunjangan}}</b></h6>
                <h6 class="text-sm text-blue-900 font-semibold"> Cabang : <b>{{$nameCabang->nama_cabang ?? '-'}}</b></h6>
            </div>
            <input type="hidden" name="tanggal" value="{{\Request::get('tanggal')}}">
            <input type="hidden" name="createdAt" value="{{\Request::get('createdAt')}}">
            <input type="hidden" name="kdEntitas" value="{{\Request::get('kdEntitas')}}">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="page_length">Show</label>
                    <select name="page_length" class="mr-3 text-sm text-neutral-400 page_length" id="page_length">
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
                    <label for="page_length">entries</label>
                </div>
                <div class="input-search">
                    <i class="ti ti-search"></i>
                    <input type="search" placeholder="Search" name="q" id="q"
                        value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                </div>
            </div>
            <table class="tables">
                <thead>
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
                    @forelse ($data as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->nip_tunjangan }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td>{{ number_format($item->nominal, 0, ",", ".") }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <th colspan="5">Data Kosong</th>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="table-footer">
                <div class="showing">
                    Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                </div>
                <div class="pagination">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->links('pagination::tailwind', [
                            'tanggal' => \Request::get('tanggal'),
                            'createdAt' => \Request::get('createdAt')
                            ]) }}
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('extraScript')
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
                var tanggal = "{{\Request::get('tanggal')}}"
                btn_pagination[i].href += `&tanggal=${tanggal}`
            }
            if (page_url.includes('createdAt')) {
                var createdAt = "{{\Request::get('createdAt')}}"
                btn_pagination[i].href += `&createdAt=${createdAt}`
            }
            if (page_url.includes('kdEntitas')) {
                var kdEntitas = "{{\Request::get('kdEntitas')}}"
                btn_pagination[i].href += `&kdEntitas=${kdEntitas}`
            }
        })
</script>
@endpush
