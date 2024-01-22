@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data User</h2>
                <div class="breadcrumb">
                 <a href="/" class="text-sm text-gray-500">Setting</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
                 <i class="ti ti-circle-filled text-theme-primary"></i>
                 <a href="{{ route('user.index') }}" class="text-sm text-gray-500 font-bold">User</a>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Tambah Data User</a>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <form action="" id="form" method="get">
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
                <table class="tables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Aksi</th>
                        </tr>
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
                                <td>{{ $item->name_user }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->name_role }}</td>
                                <td>{{ $item->nama_cabang ?? '-' }}</td>
                                <td class="flex gap-1">
                                    @if ($item->first_login)
                                        @can('setting - master - user - edit user')
                                            <a class="btn btn-warning-light" href="{{ route('user.edit', $item->id) }}">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('setting - master - user - delete user')
                                            @if ($item->id != auth()->user()->id)
                                            <button type="button" class="btn btn-danger-light" data-modal-id="confirmHapusModal{{$item->id}}" data-modal-toggle="modal">
                                                Hapus
                                            </button>
                                            {{-- modal hapus --}}
                                            <div class="modal-layout hidden" id="confirmHapusModal{{$item->id}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal modal-sm">
                                                    <div class="modal-head">
                                                        <div class="heading">
                                                            <h2>Konfirmasi Hapus Data</h2>
                                                        </div>
                                                        <button data-modal-dismiss="confirmHapusModal{{$item->id}}"  class="modal-close"><i class="ti ti-x"></i></button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <h2>Apakah Anda Yakin Ingin Menghapus User, <b>{{$item->name_user}}</b>?</h2>
                                                    </div>
                                                    <div class="modal-footer to-right">
                                                        <button data-modal-dismiss="confirmHapusModal{{$item->id}}" class="btn btn-light" type="button">Cancel</button>
                                                        <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                                <button data-modal-dismiss="confirmHapusModal{{$item->id}}" class="btn btn-primary" type="submit">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endcan
                                    @else
                                        @can('setting - master - user - edit user')
                                            <a class="btn btn-warning-light" href="{{ route('user.edit', $item->id) }}">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('setting - master - user - delete user')
                                            @if ($item->id != auth()->user()->id)
                                                <button type="button" class="btn btn-danger-light" data-modal-id="confirmHapusModal{{$item->id}}" data-modal-toggle="modal">
                                                    Hapus
                                                </button>
                                                {{-- modal hapus --}}
                                                <div class="modal-layout hidden" id="confirmHapusModal{{$item->id}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal modal-sm">
                                                        <div class="modal-head">
                                                            <div class="heading">
                                                                <h2>Konfirmasi Hapus Data</h2>
                                                            </div>
                                                            <button data-modal-dismiss="confirmHapusModal{{$item->id}}"  class="modal-close"><i class="ti ti-x"></i></button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <h2>Apakah Anda Yakin Ingin Menghapus User, <b>{{$item->name_user}}</b>?</h2>
                                                        </div>
                                                        <div class="modal-footer to-right">
                                                            <button data-modal-dismiss="confirmHapusModal{{$item->id}}" class="btn btn-light" type="button">Cancel</button>
                                                            <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                    <button data-modal-dismiss="confirmHapusModal{{$item->id}}" class="btn btn-primary" type="submit">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endcan
                                    @endif
                                    @can('setting - master - user - reset password user')
                                        <button type="button" class="btn btn-primary-light" data-modal-id="confirmResetModal{{$item->id}}" data-modal-toggle="modal">
                                            Reset Password
                                        </button>
                                        {{-- modal reset password --}}
                                        <div class="modal-layout hidden" id="confirmResetModal{{$item->id}}" tabindex="-1" aria-hidden="true">
                                            <div class="modal modal-sm">
                                                <div class="modal-head">
                                                    <div class="heading">
                                                        <h2>Reset Password</h2>
                                                    </div>
                                                    <button data-modal-dismiss="confirmResetModal{{$item->id}}"  class="modal-close"><i class="ti ti-x"></i></button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    <h2>Apakah Anda yakin ingin mereset password pengguna, <b>{{$item->name_user}}</b> dengan email, <b>{{ $item->email }}</h2>
                                                </div>
                                                <div class="modal-footer to-right">
                                                    <button data-modal-dismiss="confirmResetModal{{$item->id}}" class="btn btn-light" type="button">Cancel</button>
                                                    <form action="{{ route('password.reset.user', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                            <button data-modal-dismiss="confirmResetModal{{$item->id}}" class="btn btn-primary" type="submit">Reset</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </td>
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
                        Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                    </div>
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $data->links('pagination::tailwind') }}
                    @endif
        
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
        })
    </script>
@endpush
