@extends('layouts.app-template')

@section('modal')
    @include('gaji_perbulan.modal.new.proses')
    @include('gaji_perbulan.modal.new.penghasilan-kantor')
    @include('gaji_perbulan.modal.new.modal-upload')
    @include('gaji_perbulan.modal.new.perbarui')
    @include('gaji_perbulan.modal.new.hapus')
    @include('gaji_perbulan.modal.new.kembalikan')
    {{--
    --}}
    @include('gaji_perbulan.script.index')
    @include('gaji_perbulan.modal.new.rincian')
    @include('gaji_perbulan.modal.new.payroll')
    @include('gaji_perbulan.modal.new.lampir-gaji')
@endsection
@push('style')
    <style>
        table.dataTable>thead>tr>th{
            text-align: center;
        }
    </style>
@endpush

@section('content')
<div class="head mt-5">
    <div class="lg:flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Proses Penggajian</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penggajian</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-500 font-bold">Proses Penggajian</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3 lg:mt-0 mt-5">
            @if (auth()->user()->hasRole(['kepegawaian','hrd','admin']))
                <button class="btn btn-warning-light lg:text-base text-xs filter" data-modal-id="filter-modal" data-modal-toggle="modal"><i class="ti ti-file-search"></i> Filter</button>
                @if (\Request::has('cabang'))
                    <button class="btn btn-danger-light lg:text-base text-xs">
                        <a href="{{route('gaji_perbulan.index')}}">
                            Reset
                        </a>
                    </button>
                @endif
            @endif
            @if (auth()->user()->hasRole('kepegawaian'))
                <button class="btn btn-primary-light lg:text-base text-xs penghasilan-all-kantor" data-modal-id="penghasilan-kantor-modal" data-modal-toggle="modal"><i class="ti ti-file-import"></i> Penghasilan Semua Kantor</button>
            @endif
            @can('penghasilan - proses penghasilan - proses')
                {{--  @if (auth()->user()->hasRole('kepegawaian'))
                    <button class="btn btn-warning btn-proses lg:text-base text-xs" data-modal-id="proses-modal"
                        data-modal-toggle="modal" data-is_pegawai="false">
                        <i class="ti ti-plus"></i> Proses Penggajian Lainnya
                    </button>
                @endif  --}}
                <button class="btn btn-primary btn-proses lg:text-base text-xs" data-modal-id="proses-modal"
                    data-modal-toggle="modal" data-is_pegawai="true">
                    <i class="ti ti-plus"></i> Proses Penggajian
                </button>
            @endcan
        </div>
    </div>
</div>
@php
    $already_selected_value = date('Y');
    $earliest_year = 2022;
    $i = 1;
    $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
    $total_bruto = 0;
    $total_potongan = 0;
    $total_netto = 0;
    $total_pph = 0; // bentukan
    $total_pajak_insentif = 0;
    $total_hasil_pph = 0; // bentukan - pajak insentif
@endphp
<div class="p-5">
    <div id="alert-additional-content-1" class="p-4 mb-4 text-blue-500 border border-blue-300 rounded-lg bg-blue-50  " role="alert">
    <div class="flex items-center">
        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <h3 class="text-lg font-medium">Pemberitahuan</h3>
    </div>
        <div class="mt-2 mb-4 text-sm">
            Pastikan data <b>penghasilan teratur sudah ter-upload semua</b>, karena akan berpengaruh pada pembentukan pajak.
        </div>
    </div>
</div>
<div class="body-pages">
    <form id="form-filter" action="{{route('gaji_perbulan.index')}}" method="get">
        <input type="hidden" name="tab" id="tab" value="{{\Request::has('tab') ? \Request::get('tab') : 'proses'}}">
        <div class="tab-wrapper nav-tabs">
            <button type="button" class="btn-tab @if(!\Request::has('tab')) active-tab @endif @if (\Request::has('tab'))
            @if(\Request::get('tab') == 'proses') active-tab @endif
            @endif" data-tab="proses">Proses</button>
            <button type="button" class="btn-tab @if(\Request::get('tab') == 'final') active-tab @else  @endif" data-tab="final">Final</button>
            @if (auth()->user()->hasRole('admin'))
                <button type="button" class="btn-tab @if(\Request::get('tab') == 'sampah') active-tab @else  @endif" data-tab="sampah">Sampah</button>
            @endif
        </div>
        <div id="myTabContent">
            <div class="tab-content table-wrapping @if(!\Request::has('tab')) block @endif @if (\Request::has('tab'))
                @if(\Request::get('tab') == 'proses') active show @else hidden @endif
                @endif" id="proses">
                @include('gaji_perbulan.tab-contents.proses-tab')
            </div>
            <div class="tab-content table-wrapping @if(\Request::get('tab') == 'final') block @else hidden @endif" id="final" id="final">
                @include('gaji_perbulan.tab-contents.final-tab')
            </div>
            @if (auth()->user()->hasRole('admin'))
                <div class="tab-content table-wrapping @if(\Request::get('tab') == 'sampah') block @else hidden @endif" id="sampah" id="sampah">
                    @include('gaji_perbulan.tab-contents.trash-tab')
                </div>
            @endif
        </div>
        @include('gaji_perbulan.modal.new.filter')
    </form>
</div>
<form id="form-final" action="{{route('gaji_perbulan.proses_final')}}" method="post">
    <input type="hidden" name="_token" id="token">
    <input type="hidden" name="batch_id" id="batch_id">
</form>
@endSection

@push('extraScript')
<script>
    $(document).ready(function() {
        $('#page_length_proses, #page_length_final, #page_length_sampah').on('change', function(e) {
            e.preventDefault();
            // Set the active tab value before submitting the form
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();
        });
        $('#filter-modal #filter').on('click', function(e) {
            e.preventDefault();
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();
        });
        let tab = $('.btn-tab.active-tab').data('tab');
        if (tab == 'final') {
            $('.page_length_proses').prop('disabled', 'disabled');
            $('#page_length_final').prop('disabled', false);
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);
        }else if (tab == 'proses') {
            $('.page_length_proses').prop('disabled', false);
            $('#page_length_final').prop('disabled', 'disabled');
            $('#page_length_sampah').prop('disabled', 'disabled');
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);
        }else{
            $('.page_length_proses').prop('disabled', 'disabled');
            $('#page_length_final').prop('disabled', 'disabled');
            $('#page_length_sampah').prop('disabled', false);
            $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
            $("#myTabContent .block").find('#q').prop('disabled', false);

        }
        $('.q-proses').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })
        $('.q-final').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })
        $('.q-sampah').on('change',function() {
            $('#tab').val($('.btn-tab.active-tab').data('tab'));
            $('#form-filter').submit();

        })

        $('#form-filter').on('submit', function() {
            $('.loader-wrapper').css('display: none;')
            $('.loader-wrapper').addClass('d-block')
            $(".loader-wrapper").fadeOut("slow");
        })

        $('#filter').on('click', function() {
            $('#filter-modal #cabang_req').select2()
            $('#filter-modal').removeClass('hidden')
        })

        $(".btn-tab").on("click", function (e) {
            $('#tab').val($(this).data('tab'))
            let tab = $(this).data('tab');
            if (tab == 'final') {
                $('.page_length_proses').prop('disabled', 'disabled');
                $('#page_length_final').prop('disabled', false);
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                $('#form-filter').submit();
                refreshPagination();
            }else if (tab == 'proses') {
                $('.page_length_proses').prop('disabled', false);
                $('#page_length_final').prop('disabled', 'disabled');
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                refreshPagination();
                $('#form-filter').submit();
            }else{
                $('.page_length_proses').prop('disabled', 'disabled');
                $('#page_length_final').prop('disabled', 'disabled');
                $('#page_length_sampah').prop('disabled', false);
                $("#myTabContent .hidden").find('#q').prop('disabled', 'disabled');
                $("#myTabContent .block").find('#q').prop('disabled', false);
                refreshPagination();
            }
        })
        refreshPagination();
        // Adjust pagination url
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }

    });
        function refreshPagination() {
            var btn_pagination = $("#myTabContent .block .pagination").find("a");
            var page_url = window.location.href;
            console.log(btn_pagination);

            // Your custom query parameter and its value
            let tab = $('#tab').val();
            let cabang_req = $('#cabang_req').val();
            var customParam = "";
            customParam += "&tab=" + $('#tab').val();
            if (tab == 'proses') {
                customParam += "&page_length_proses=" + $('.page_length_proses').val();
                customParam += "&q_proses=" + $('#q').val();
                customParam += "&cabang=" + cabang_req;
            } else if(tab == 'final'){
                customParam += "&page_length_final=" + $('.page_length_final').val();
                customParam += "&q_final=" + $('#q').val();
                customParam += "&cabang=" + cabang_req;
            } else {
                customParam += "&page_length_final=" + $('.page_length_sampah').val();
                customParam += "&q_sampah=" + $('#q').val();
                customParam += "&cabang=" + cabang_req;
            }

            btn_pagination.each(function (i, obj) {
                // Clone the original href to avoid modifying the original link
                var href = $(this).attr("href");

                // Check if the href already contains a question mark
                var separator = href.includes("?") ? "&" : "?";

                // Append the custom query parameter and its value
                var updatedHref = href + separator + customParam;
                updatedHref = updatedHref.replaceAll('&&', '&')

                // Update the href attribute of the pagination link
                $(this).attr("href", updatedHref);
            });
        }

    $(`.btn-delete`).on('click', function(){
        const target = '#delete-modal';
        const kantor = $(this).data("kantor");
        const bulan = $(this).data("bulan");
        const tahun = $(this).data("tahun");
        const id = $(this).data("batch_id");
        console.log(id);

        $(`${target} #kantor`).html(kantor);
        $(`${target} #bulan`).html(bulan);
        $(`${target} #tahun`).html(tahun);
        $(`${target} #id`).val(id);
        $(`${target}`).removeClass('hidden');
    })
    $(`.btn-restore`).on('click', function(){
        const target = '#restore-modal';
        const kantor = $(this).data("kantor");
        const bulan = $(this).data("bulan");
        const namaBulan = new Date(2022, bulan - 1, 1).toLocaleString('id-ID', { month: 'long' });
        const tahun = $(this).data("tahun");
        const id = $(this).data("batch_id");

        $(`${target} #kantor`).html(kantor);
        $(`${target} #bulan`).html(namaBulan);
        $(`${target} #tahun`).html(tahun);
        $(`${target} #id`).val(id);
        $(`${target}`).removeClass('hidden');
    })
</script>
@endpush
