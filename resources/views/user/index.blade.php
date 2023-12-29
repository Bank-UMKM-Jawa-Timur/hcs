@extends('layouts.template')

@section('content')
    <div class="card-header">
        <h5 class="card-title">Data User</h5>
        <p class="card-title"><a href="/">Dashboard</a> > <a href="{{route('user.index')}}">user</a></p>
    </div>

    <div class="card-body">
        <div class="col">
            @can('setting - master - user - create user')
                <div class="row">
                    <a href="{{ route('user.create') }}">
                        <button class="is-btn is-primary">Tambah Data User</button>
                    </a>
                </div>
            @endcan
            <div class="row"> 
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
                                    Nama
                                </th>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Role
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
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->name_user }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->name_role }}</td>
                                        <td>
                                            @can('setting - master - user - edit user')
                                                <a class="is-btn is-warning" href="{{ route('user.edit', $item->id) }}">
                                                    Edit
                                                </a>
                                            @else
                                                -
                                            @endcan
                                            @can('setting - master - user - delete user')
                                                <a class="is-btn is-primary ml-2" href="javascript:void(0)" data-toggle="modal" data-target="#confirmHapusModal{{$item->id}}">
                                                    Delete
                                                </a>
                                                {{-- modal hapus --}}
                                                <div class="modal fade" id="confirmHapusModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Hapus Data</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda Yakin Ingin Menghapus User, <b>{{$item->name_user}}</b>?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcan
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
