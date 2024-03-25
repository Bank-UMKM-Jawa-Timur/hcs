@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Penghasilan Tidak Teratur</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('penghasilan-tidak-teratur.index') }}"
                    class="text-sm text-gray-500 font-bold">Penghasilan Tidak Teratur </a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500">Detail</p>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form id="form" action="">
            <input type="hidden" name="user_id" value="{{ \Request::get('user_id') }}">
            <input type="hidden" name="status" value="{{ \Request::get('status') }}">
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex gap-7" role="alert">
                <h6 class="text-sm text-blue-900 font-semibold"> Tunjangan : <b>{{$tunjangan->nama_tunjangan}}</b></h6>
                <h6 class="text-sm text-blue-900 font-semibold"> Cabang : <b>{{$nameCabang->nama_cabang}}</b></h6>
                @if (auth()->user()->hasRole('kepegawaian'))
                    <h6 class="text-sm text-blue-900 font-semibold"> Status Data : <b>{{$status == 1 ? 'Gabungan' : 'Split'}}</b></h6>
                @endif
            </div>
            <input type="hidden" name="bulan" value="{{\Request::get('bulan')}}">
            <input type="hidden" name="createdAt" value="{{\Request::get('createdAt')}}">
            <input type="hidden" name="kd_entitas" value="{{\Request::get('kd_entitas')}}">
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
                    <th>Nama Karyawan</th>
                    @if (!auth()->user()->hasRole('cabang'))
                        <th>Kantor</th>
                    @endif
                    <th>Jabatan</th>
                    <th>Nominal</th>
                </thead>
                <tbody>
                    @php
                        function rupiah($num){
                            return number_format($num, 0, '.', '.');
                        }
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                        $i = $page == 1 ? 1 : $start;
                    @endphp
                    @forelse ($data as $key => $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            @if (!auth()->user()->hasRole('cabang'))
                                <td>{{ $item->entitas->type == 2 ? $item->entitas->cab->nama_cabang : 'Pusat' }}</td>
                            @endif
                            <td>{{$item->display_jabatan}}</td>
                            <td>{{ rupiah($item->nominal) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="6">Data Kosong</th>
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
        $('#page_length').on('change', function () {
            $('#form').submit();
        })

        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
            if (page_url.includes('bulan')) {
                var bulan = "{{\Request::get('bulan')}}"
                btn_pagination[i].href += `&bulan=${bulan}`
            }
            if (page_url.includes('createdAt')) {
                var createdAt = "{{\Request::get('createdAt')}}"
                btn_pagination[i].href += `&createdAt=${createdAt}`
            }
            if (page_url.includes('kd_entitas')) {
                var kd_entitas = "{{\Request::get('kd_entitas')}}"
                btn_pagination[i].href += `&kd_entitas=${kd_entitas}`
            }
            if (page_url.includes('user_id')) {
                var user_id = "{{\Request::get('user_id')}}"
                btn_pagination[i].href += `&user_id=${user_id}`
            }
            if (page_url.includes('status')) {
                var status = "{{\Request::get('status')}}"
                btn_pagination[i].href += `&status=${status}`
            }
        })
    </script>
@endpush
