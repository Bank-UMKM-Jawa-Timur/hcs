@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Data Roles</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('role.index') }}" class="text-sm text-gray-500 font-bold">Roles</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            @if(auth()->user()->can('setting - master - role - create role'))
                <a href="{{ route('role.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Role</a>
            @endif
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form id="form" method="get">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="">Show</label>
                    <select name="page_length" id="page_length">
                        <option value="10" @isset($_GET['page_length']) {{ $_GET['page_length']==10 ? 'selected' : '' }}
                            @endisset>
                            10</option>
                        <option value="20" @isset($_GET['page_length']) {{ $_GET['page_length']==20 ? 'selected' : '' }}
                            @endisset>
                            20</option>
                        <option value="50" @isset($_GET['page_length']) {{ $_GET['page_length']==50 ? 'selected' : '' }}
                            @endisset>
                            50</option>
                        <option value="100" @isset($_GET['page_length']) {{ $_GET['page_length']==100 ? 'selected' : ''
                            }} @endisset>
                            100</option>
                    </select>
                    <label for="">entries</label>
                </div>
                <div class="input-search">
                    <i class="ti ti-search"></i>
                    <input type="search" placeholder="Search" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}" name="q"
                        id="q">
                </div>
            </div>
            <table class="tables" id="table">
                <thead class="text-primary">
                    <th>No</th>
                    <th>
                        Name
                    </th>
                    <th>
                        Guard Name
                    </th>
                    <th>
                        Aksi
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
                    @foreach ($data as $item_roles)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item_roles->name }}</td>
                            <td>{{ $item_roles->guard_name }}</td>
                            <td class="flex justify-center gap-2">
                                @if(auth()->user()->can('setting - master - role - edit role'))
                                <a href="{{ route('role.edit', $item_roles->id) }}" class="btn btn-warning-light">
                                    Edit
                                </a>
                                @endif
                                @if(auth()->user()->can('setting - master - role - detail role'))
                                <a href="{{ route('role.show', $item_roles->id) }}" class="btn btn-primary-light">
                                    Detail
                                </a>
                                @endif
                                @if(auth()->user()->can('setting - master - role - delete role'))
                                <button type="button" class="btn btn-danger-light" data-modal-toggle="modal" data-modal-id="confirmHapusModal{{$item_roles->id}}">
                                        Hapus
                                </button>
                                {{-- modal hapus --}}
                                <div class="modal-layout hidden" id="confirmHapusModal{{$item_roles->id}}" tabindex="-1" aria-hidden="true">
                                    <div class="modal modal-sm">
                                        <div class="modal-head">
                                            <div class="heading">
                                                <h2>Konfirmasi Hapus Data</h2>
                                            </div>
                                            <button data-modal-dismiss="confirmHapusModal{{$item_roles->id}}"  class="modal-close"><i class="ti ti-x"></i></button>
                                        </div>
                                        <div class="modal-body text-left">
                                            <h2>Apakah Anda Yakin Ingin Menghapus Data Role, <b>{{$item_roles->name}}</b>?</h2>
                                        </div>
                                        <div class="modal-footer to-right">
                                            <button data-modal-dismiss="confirmHapusModal{{$item_roles->id}}" class="btn btn-light" type="button">Batal</button>
                                            <form action="{{ route('role.destroy', $item_roles->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                    <button data-modal-dismiss="confirmHapusModal{{$item_roles->id}}" class="btn btn-primary" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
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
