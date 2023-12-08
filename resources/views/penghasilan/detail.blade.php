@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Penghasilan Tidak Teratur</h5>
            <p class="card-title"><a href="">Penghasilan </a> > <a href="{{ route('penghasilan-tidak-teratur.index') }}">Penghasilan Tidak Teratur</a> > Detail</p>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive overflow-hidden content-center">
                <form action="" id="form" method="get">
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
                    <table class="table" id="table" style="width: 100%">
                        <thead class=" text-primary">
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Kantor</th>
                                <th>Nominal</th>
                            </tr>
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
                                    <td>{{ $item->entitas->type == 2 ? $item->entitas->cab->nama_cabang : 'Pusat' }}</td>
                                    <td>{{$item->display_jabatan}}</td>
                                    <td>{{ rupiah($item->nominal) }}</td>
                                </tr>
                            @empty
                                
                            @endforelse
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
