@extends('layouts.app-template')

@section('content')
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
        <div class="layout-component">
            <div class="shorty-table">
                <label for="">Show</label>
                <select name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <label for="">entries</label>
            </div>
            <div class="input-search">
                <i class="ti ti-search"></i>
                <input type="search" placeholder="Search" name="q" id="q">
            </div>
        </div>
        <table class="tables">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Id Address</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Cabang</th>
                    <th>Lama Login</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>127.0.0.1</td>
                    <td>admin@bprjatim.com</td>
                    <td>Admin</td>
                    <td>Admin</td>
                    <td>Pusat</td>
                    <td>08:03:20</td>
                    <td>Aktif</td>
                    <td class="flex justify-center"><button class="btn btn-primary-light">Resset</button></td>
                </tr>
            </tbody>
        </table>
        <div class="table-footer">
            <div class="showing">
                Showing 1 to 5 of 2450 entries
            </div>
            <div class="pagination">
                <a href="" class="item-pg item-pg-prev">
                    Prev
                </a>
                <a href="#" class="item-pg active-pg">
                    1
                </a>
                <a href="#" class="item-pg">
                    2
                </a>
                <a href="#" class="item-pg">
                    3
                </a>
                <a href="#" class="item-pg">
                    4
                </a>
                <a href="#" class="item-pg of-the-data">
                    of 100
                </a>
                <a href="" class="item-pg item-pg-next">
                    Next
                </a>
            </div>
        </div>
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