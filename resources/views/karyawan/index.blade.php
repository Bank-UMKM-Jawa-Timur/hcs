@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Data Karyawan</h5>
        <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a></p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5" >
        <a class="mb-3" href="{{ route('karyawan.create') }}">
            <button class="is-btn is-primary-light">tambah karyawan</button>
        </a>
        <a class="ml-3" href="{{ route('import') }}">
            <button class="is-btn is-primary-light">import karyawan</button>
        </a>
        <a class="ml-3" href="{{ route('klasifikasi_karyawan') }}">
            <button class="is-btn is-primary-light">Export Karyawan</button>
        </a>
    </div>
</div>
<div class="card-body">
        <div class="col">
            <div class="row p-3">
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        @include('components.pagination.header')
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                            <thead class="text-primary">
                        <div class="d-flex justify-content-between mb-4">
                        <div class="p-2 mt-3 w-100">
                            <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                            <select name="page_length" id="page_length"
                                class="border px-3 py-2 cursor-pointer rounded appearance-none text-center">
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
                            <input type="search" name="q" id="q" placeholder="Cari disini..." class="form-control  p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                        </div>
                        </div>
                        <table class="table whitespace-nowrap border text-center" id="table" style="width: 100%">
                            <thead class="text-dark">
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
                            <tbody>
                                @php
                                  $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                  $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                  $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                                  $number = 1;
                                  if ($pagination) {
                                    $number = $pagination['iteration'];
                                  }
                                @endphp
                                @foreach ($karyawan as $krywn)
                                    @if ($krywn->tanggal_penonaktifan === null)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $krywn->nip }}</td>
                                            <td>{{ $krywn->nik }}</td>
                                            <td>{{ $krywn->nama_karyawan }}</td>
                                            <td>{{ $krywn->entitas->type == 2 ? $krywn->entitas->cab->nama_cabang : 'Pusat' }}
                                            </td>
                                            @php
                                                $prefix = match ($krywn->status_jabatan) {
                                                    'Penjabat' => 'Pj. ',
                                                    'Penjabat Sementara' => 'Pjs. ',
                                                    default => '',
                                                };
                                                
                                                if ($krywn->jabatan) {
                                                    $jabatan = $krywn->jabatan->nama_jabatan;
                                                } else {
                                                    $jabatan = 'undifined';
                                                }
                                                
                                                $ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : '';
                                                
                                                if (isset($krywn->entitas->subDiv)) {
                                                    $entitas = $krywn->entitas->subDiv->nama_subdivisi;
                                                } elseif (isset($krywn->entitas->div)) {
                                                    $entitas = $krywn->entitas->div->nama_divisi;
                                                } else {
                                                    $entitas = '';
                                                }
                                                
                                                if ($jabatan == 'Pemimpin Sub Divisi') {
                                                    $jabatan = 'PSD';
                                                } elseif ($jabatan == 'Pemimpin Bidang Operasional') {
                                                    $jabatan = 'PBO';
                                                } elseif ($jabatan == 'Pemimpin Bidang Pemasaran') {
                                                    $jabatan = 'PBP';
                                                } else {
                                                    $jabatan = $krywn->jabatan ? $krywn->jabatan->nama_jabatan : 'undifined';
                                                }
                                            @endphp
                                            <td>{{ $prefix . $jabatan }} {{ $entitas }}
                                                {{ $krywn?->bagian?->nama_bagian }} {{ $ket }}</td>
                                            <td style="min-width: 130px">
                                                <div class="container">
                                                    <div class="row">
                                                        <a href="{{ route('karyawan.edit', $krywn->nip) }}">
                                                            <button class="btn btn-outline-warning p-1 mr-2"
                                                                style="min-width: 60px">
                                                                Edit
                                                            </button>
                                                        </a>

                                                        <a href="{{ route('karyawan.show', $krywn->nip) }}">
                                                            <button class="btn btn-outline-info p-1"
                                                                style="min-width: 60px">
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>

                                                {{-- <form action="{{ route('karyawan.destroy', $krywn->nip) }}" method="POST">
                                                  @csrf
                                                  @method('DELETE')

                                                  <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                                </form> --}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                          <div>
                            Showing {{$start}} to {{$end}} of {{$karyawan->total()}} entries
                          </div>
                          <div>
                            @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $karyawan->links('pagination::bootstrap-4') }}
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
