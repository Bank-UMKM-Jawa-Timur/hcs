@extends('layouts.template')

@section('content')
    <div class="card-header">
        <h5 class="card-title">Data Karyawan</h5>
        <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a></p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <a class="mb-3" href="{{ route('karyawan.create') }}">
                    <button class="btn btn-primary">tambah karyawan</button>
                </a>
                <a class="ml-3" href="{{ route('import') }}">
                    <button class="btn btn-primary">import karyawan</button>
                </a>
                <a class="ml-3" href="{{ route('klasifikasi_karyawan') }}">
                    <button class="btn btn-primary">Export Karyawan</button>
                </a>
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        @include('components.pagination.header')
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
                                            <td>{{ $number++ }}</td>
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
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        @include('components.pagination.table-info', [
                          'obj' => $karyawan,
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
