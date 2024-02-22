@extends('layouts.app-template')

@section('modal')
<div id="modalReset"></div>
@endsection

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Karyawan</h2>
                <div class="breadcrumb">
                    <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Karyawan</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                @can('manajemen karyawan - data karyawan - export karyawan')
                    <a href="{{ route('klasifikasi_karyawan') }}" class="btn btn-light"><i class="ti ti-file-export"></i>
                        Export</a>
                @endcan
                @can('manajemen karyawan - data karyawan - import karyawan')
                    <a href="{{ route('import') }}" class="btn btn-primary-light"><i class="ti ti-file-import"></i> Import</a>
                @endcan
                @can('manajemen karyawan - data karyawan - create karyawan')
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="body-pages">
        <div class="table-wrapping">
            <form id="form" method="get">
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
                        <tr>
                            <th>No.</th>
                            <th>NIP</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Kantor</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
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
                                <td>{{ str_contains($krywn->nip, 'U') ? '-' : $krywn->nip }}</td>
                                <td>{{ $krywn->nik != '0' ? $krywn->nik : '-' }}</td>
                                <td>{{ $krywn->nama_karyawan }}</td>
                                <td>{{ $krywn->entitas->type == 2 ? $krywn->entitas->cab->nama_cabang : 'Pusat' }}
                                </td>
                                <td>{{ implode(' ', array_unique(explode(' ', $krywn->display_jabatan))) }}</td>
                                <td style="min-width: 130px">
                                    <div class="container">
                                        <div class="flex gap-1 justify-center">
                                            @can('manajemen karyawan - data karyawan - edit karyawan')
                                                <a href="{{ route('karyawan.edit', $krywn->nip) }}"
                                                    class="btn btn-warning-light mr-2" style="min-width: 60px">
                                                    Edit
                                                </a>
                                            @elsecan('manajemen karyawan - data karyawan - edit karyawan - edit potongan')
                                                <a href="{{ route('karyawan.edit', $krywn->nip) }}"
                                                    class="btn btn-warning-light mr-2" style="min-width: 60px">
                                                    Edit Potongan
                                                </a>
                                            @endcan
                                            @can('manajemen karyawan - data karyawan - detail karyawan')
                                                <a href="{{ route('karyawan.show', $krywn->nip) }}"
                                                    class="btn btn-primary-light" style="min-width: 60px">
                                                    Detail
                                                </a>
                                            @endcan
                                            @can('manajemen karyawan - data karyawan - reset password - karyawan')
                                                <a class="btn btn-primary ml-2 modal-reset-button" href="javascript:void(0)"
                                                data-modal-toggle="modal" data-modal-id="modal-confirm-{{ $krywn->nip }}"
                                                    data-formid="{{ $krywn->nip }}"
                                                    data-formname="{{ $krywn->nama_karyawan }}">
                                                    <input type="hidden" id="nip-karywan" name="nip_karyawan"
                                                        value="{{ $krywn->nip }}">
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
                <div class="table-footer">
                    <div class="showing">
                        Showing {{ $start }} to {{ $end }} of {{ $karyawan->total() }} entries
                    </div>
                    <div class="pagination">
                        @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $karyawan->links('pagination::tailwind') }}
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        $('.page_length').on('change', function() {
            $('#form').submit()
        })

        function closeModal(id){
            setTimeout(function () {
                    $(".modal").css("animation", "swipe-out 0.2s ease-in-out");
                    $(".modal-layout").css(
                        "animation",
                        "opacity-out 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67)"
                    );
                }, 200);
                setTimeout(function () {
                    $("#" + id).addClass("hidden");
                }, 400);
        }
        $('.modal-reset-button').on('click', function() {
            var formId = $(this).data('formid');
            var formname = $(this).data('formname');

            $('#modalReset').empty();
            $('#modalReset').html(`
                <div class="modal-layout hidden" id="modal-confirm-${formId}" tabindex="-1" aria-hidden="true">
                    <div class="modal modal-sm">
                        <div class="modal-head">
                            <h2>
                                Konfirmasi Reset Password
                            </h2>
                            <button data-modal-dismiss="modal-confirm-${formId}"  class="modal-close" onClick="closeModal()"><i class="ti ti-x"></i></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-left">Apakah Anda yakin ingin mereset password pengguna,
                            <b>${formname}</b> dengan nip, <b>${formId}</b>?</p>
                        </div>
                        <div class="modal-footer to-right">
                            <button data-modal-dismiss="modal-confirm-${formId}" onClick="closeModal('modal-confirm-${formId}')" class=" btn btn-light" type="button">Cancel</button>
                            <form id="form-reset" action="{{ route('reset-password-karyawan') }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="formId" value="${formId}">
                                <button type="submit" class="btn btn-primary">Reset</button>
                            </form>
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
@endpush
