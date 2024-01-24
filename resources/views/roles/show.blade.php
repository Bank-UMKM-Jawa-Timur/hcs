@extends('layouts.app-template')
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Show Roles</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Master</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('role.index') }}" class="text-sm text-gray-500 font-bold">Show</a>
            </div>
        </div>
        <div class="button-wrapper">
            <form id="form" method="get">
                <div class="input-box">
                    <label for="q">Cari</label>
                    <input type="search" name="q" id="q" class="form-input" placeholder="Cari disini..."
                        class="form-control p-2" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}">
                </div>
            </form>
        </div>
    </div>
</div>
<button class="btn-scroll-to-top btn btn-primary hidden absolute bottom-5 right-5 z-20">
    To Top <iconify-icon icon="mdi:arrow-top" class="ml-2 mt-1"></iconify-icon>
</button>

<div class="body-pages">
    <div class="table-wrapping">
        <div class="input-box">
            <label for="name">Role</label>
            <input type="text" class="@error('name') is-invalid @enderror form-input" name="name" id="name" value="{{ old('role', $data->name) }}" placeholder="Nama Role" readonly>
        </div>
        <table class="tables mt-5">
            <thead>
                <tr>
                    <th style="text-align: left; padding-left: 25px">No</th>
                    <th style="text-align: left">Nama</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($selected as $item)
                    <tr>
                        <td style="text-align: left; padding-left: 30px">{{ $loop->iteration }}</td>
                        <td style="text-align: left"> {{ ucwords(str_replace('-','/',$item->name)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="2">Data Kosong</th>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('extraScript')
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })

        $('.btn-scroll-to-top').on('click', function () {
            $('#scroll-body').animate({
                scrollTop: $("#scroll-body").offset().top
            }, 400);
        });

        $('#scroll-body').scroll(function () {
            if ($('#scroll-body').scrollTop() > 400) {
                $(".btn-scroll-to-top").removeClass("hidden");
            } else {
                $(".btn-scroll-to-top").addClass("hidden");
            }
        });
    </script>
@endpush
