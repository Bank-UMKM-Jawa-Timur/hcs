@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Data Karyawan</h5>
        <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a></p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5" >
        @can('manajemen karyawan - data karyawan - create karyawan')
            <a class="mb-3" href="{{ route('karyawan.create') }}">
                <button class="is-btn is-primary">Tambah</button>
            </a>
        @endcan
        @can('manajemen karyawan - data karyawan - import karyawan')
            <a class="ml-3" href="{{ route('import') }}">
                <button class="is-btn is-primary">Import</button>
            </a>
        @endcan
        @can('manajemen karyawan - data karyawan - export karyawan')
            <a class="ml-3" href="{{ route('klasifikasi_karyawan') }}">
                <button class="is-btn is-primary">Export</button>
            </a>
        @endcan
    </div>
</div>
<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="table-responsive overflow-hidden content-center">
                <form id="form" method="get">
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
                    {{-- @php
                        // dump(auth()->user());
                        // dump(auth()->user()->hasRole('cabang'));
                    @endphp --}}
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
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
                                $start = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                                $end = $page == 1 ? $page_length : $start + $page_length - 1;
                                $i = $page == 1 ? 1 : $start;
                            @endphp
                            @foreach ($karyawan as $krywn)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $krywn->nip }}</td>
                                    <td>{{ $krywn->nik != '0' ? $krywn->nik : '-' }}</td>
                                    <td>{{ $krywn->nama_karyawan }}</td>
                                    <td>{{ $krywn->entitas->type == 2 ? $krywn->entitas->cab->nama_cabang : 'Pusat' }}
                                    </td>
                                    <td>{{$krywn->display_jabatan}}</td>
                                    <td style="min-width: 130px">
                                        <div class="container">
                                            <div class="row">
                                                @can('manajemen karyawan - data karyawan - edit karyawan')
                                                    <a href="{{ route('karyawan.edit', $krywn->nip) }}"
                                                        class="btn btn-outline-warning p-1 mr-2"
                                                        style="min-width: 60px">
                                                        Edit
                                                    </a>
                                                @elsecan('manajemen karyawan - data karyawan - edit karyawan - edit potongan')
                                                    <a href="{{ route('karyawan.edit', $krywn->nip) }}"
                                                        class="btn btn-outline-warning p-1 mr-2"
                                                        style="min-width: 60px">
                                                        Edit Potongan
                                                    </a>
                                                @endcan
                                                @can('manajemen karyawan - data karyawan - detail karyawan')
                                                    <a href="{{ route('karyawan.show', $krywn->nip) }}"
                                                        class="btn btn-outline-info"
                                                        style="min-width: 60px">
                                                        Detail
                                                    </a>
                                                @endcan
                                                @can('manajemen karyawan - data karyawan - reset password - karyawan')
                                                    <a class="is-btn btn-info ml-2 modal-reset-button" href="javascript:void(0)" data-toggle="modal" data-target="#confirmResetModal{{$krywn->nip}}" data-formid="{{$krywn->nip}}" data-formname="{{ $krywn->nama_karyawan }}">
                                                        <input type="hidden" id="nip-karywan" name="nip_karyawan" value="{{ $krywn->nip }}">
                                                        Reset Password
                                                    </a>
                                                    {{-- modal reset password --}}
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
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
                </form>
            </div>
        </div>
    </div>
    <div id="modalReset"></div>
</div>
@endsection

@section('custom_script')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })

        $('.modal-reset-button').on('click', function() {
            var formId = $(this).data('formid');
            var formname = $(this).data('formname');

            $('#modalReset').empty();
            $('#modalReset').append(`
                <div class="modal fade" id="confirmResetModal${formId}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Reset Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-left">Apakah Anda yakin ingin mereset password pengguna, <b>${formname}</b> dengan nip, <b>${formId}</b>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <form id="form-reset" action="{{ route('reset-password-karyawan') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="formId" value="${formId}">
                                    <button type="submit" class="btn btn-danger">Reset</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `)
        });

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