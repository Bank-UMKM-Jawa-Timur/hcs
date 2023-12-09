@extends('layouts.template')

@section('content')
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Data Roles</h5>
        <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('role.index') }}">Roles</p>
    </div>
    <div class="card-header row mt-3 mr-8 pr-5">
        @can('setting - master - role - create role')
        <a class="mb-3" href="{{ route('role.create') }}">
            <button class="is-btn is-primary">tambah role</button>
        </a>
        @endcan
    </div>
</div>

    <div class="card-body p-3">
        <div class="col">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
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
                            <table class="table whitespace-nowrap" id="table" style="width: 100%">
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
    
                                            <td style="min-width: 130px">
                                                <div class="container">
                                                    <div class="row">
                                                        @can('setting - master - role - edit role')
                                                        <a href="{{ route('role.edit', $item_roles->id) }}" class="btn btn-outline-warning p-1 mr-2">
                                                            Edit
                                                        </a>
                                                        @endcan
                                                        @can('setting - master - role - detail role')
                                                        <a href="{{ route('role.show', $item_roles->id) }}" class="btn btn-outline-info p-1 mr-2">
                                                            Detail
                                                        </a>
                                                        @endcan
                                                        @can('setting - master - role - delete role')
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger p-1" data-toggle="modal" data-target="#confirmHapusModal{{$item_roles->id}}">
                                                                Hapus
                                                        </a>
                                                        {{-- modal hapus --}}
                                                        <div class="modal fade" id="confirmHapusModal{{$item_roles->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Hapus Data</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Apakah Anda Yakin Ingin Menghapus Data Role, <b>{{$item_roles->name}}</b>?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                        <form action="{{ route('role.destroy', $item_roles->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
