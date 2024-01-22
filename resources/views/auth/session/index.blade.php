@extends('layouts.app-template')

@section('content')
@include('auth.session.modal')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Master Session</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Session</a>
            </div>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="table-wrapping">
        <form action="" method="get">
            <div class="layout-component">
                <div class="shorty-table">
                    <label for="">Show</label>
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
                    <label for="">entries</label>
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
                        <th>IP Address</th>
                        <th>Email / NIP</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Cabang</th>
                        <th>Lama Login</th>
                        <th>Status</th>
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
                    @forelse ($data as $key => $item)
                        @php
                            // Waktu login pengguna
                            $startTime = new DateTime($item->created_at);
                            
                            // Waktu saat ini
                            $endTime = new DateTime('now');
                        
                            // Hitung perbedaan waktu
                            $interval = $endTime->diff($startTime);
                        
                            // Format waktu
                            $hours = $interval->h;
                            $minutes = $interval->i;
                            $seconds = $interval->s;
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->ip_address }}</td>
                            <td>{{ $item->nip ?? $item->email }}</td>
                            <td>{{ $item->nama_karyawan ?? $item->name }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <span class="clock_{{$item->id}}"></span>
                                <script>
                                    currentTime({{$hours}}, {{$minutes}}, {{$seconds}}, "clock_{{$item->id}}")
                                    function currentTime(h, m, s, widget_id) {
                                        let hh = parseInt(h);
                                        let mm = parseInt(m);
                                        let ss = parseInt(s);
                                        ss++;
                            
                                        if (ss > 59) {
                                            mm++;
                                            ss = 0;
                                        }
                            
                                        if (mm > 59) {
                                            hh++;
                                            mm = 0;
                                        }
                            
                                        hh = (hh < 10) ? "0" + hh : hh;
                                        mm = (mm < 10) ? "0" + mm : mm;
                                        ss = (ss < 10) ? "0" + ss : ss;
                            
                                        let time = hh + ":" + mm + ":" + ss;
                                        document.querySelector(`.${widget_id}`).innerHTML = time;
                                        var t = setTimeout(function(){ currentTime(hh, mm, ss, `${widget_id}`) }, 1000); 
                                    }
                                </script>
                            </td>
                            <td>Aktif</td>
                            <td class="flex justify-center">
                                @if ($item->user_id == 1)
                                    -
                                @else
                                    <button type="button" class="btn btn-primary-light btnModal" data-id="{{ $item->id }}" data-modal-toggle="modal" data-modal-id="confirmResetModal">Reset</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">Mohon maaf data belum tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="table-footer">
                <div class="showing">
                    Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                </div>
                <div class="pagination">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->appends(Request()->except('page'))->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>
        </form>
        {{-- <div class="table-footer">
            <div class="showing">
                Showing {{ $start }} to {{ $end }} of {{ $karyawan->total() }} entries
            </div>
            <div class="pagination">
                @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $karyawan->links('pagination::tailwind') }}
                @endif
            </div>
        </div> --}}
    </div>
</div>
@endsection
@push('script')
    <script>
        $(".btnModal").on('click', function(){
            $("#confirmResetModal").removeClass('hidden')
            let id = $(this).data('id')
            console.log(id);
            $("#idReset").val(id)
        })
    </script>
@endpush