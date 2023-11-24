@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Demosi</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a href="{{ route('demosi.index') }}">Demosi</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('demosi.create') }}">
                        <button  class="btn btn-primary">Tambah Demosi</button>
                    </a>
                    <div class="table-responsive">
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
                            <table class="table">
                                <thead class="text-primary">
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        NIP
                                    </th>
                                    <th>
                                        Nama Karyawan
                                    </th>
                                    <th>
                                        Tanggal Demosi
                                    </th>
                                    <th>
                                        Jabatan Lama
                                    </th>
                                    <th>
                                        Jabatan Baru
                                    </th>
                                    <th>
                                        Kantor Lama
                                    </th>
                                    <th>
                                        Kantor Baru
                                    </th>
                                    <th>
                                        Bukti SK
                                    </th>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                        $number = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                                        $i = $page == 1 ? 1 : $number;
                                    @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                {{ $i++ }}
                                            </td>
                                            <td>
                                                {{ $item->nip }}
                                            </td>
                                            <td>
                                                {{ $item->nama_karyawan }}
                                            </td>
                                            <td>
                                                <span style="display: none;">{{ date('Ymd', strtotime($item->tanggal_pengesahan)) }}</span>
                                                {{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ ($item->status_jabatan_lama != null) ? $item->status_jabatan_lama.' - ' : '' }}{{ $item->jabatan_lama }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ ($item->status_jabatan_baru != null) ? $item->status_jabatan_baru.' - ' : '' }}{{ $item->jabatan_baru }}
                                            </td>
                                            <td>
                                                {{ $item->kantor_lama ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->kantor_baru ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->bukti_sk }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $data->links('pagination::bootstrap-4') }}
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        });
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
