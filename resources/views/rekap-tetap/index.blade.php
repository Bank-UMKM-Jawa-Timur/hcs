@extends('layouts.app-template')
@push('style')
    <style>
        table th {
            border: 1px solid #e3e3e3 !important;
        }
    </style>
@endpush

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Rekapitulasi
            </div>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Laporan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Rekapitulasi</a>
            </div>
        </div>
    </div>
</div>
    <div class="body-pages">
        <div class="table-wrapping">
            <div class="">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
                        <form id="form" method="get">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-box">
                                    <label for="">Tahun<span class="text-danger">*</span></label>
                                    <select name="tahun" id="tahun"
                                        class="form-input">
                                        <option value="0">Pilih Tahun</option>
                                        @php
                                            $earliest = 2024;
                                            $tahunSaatIni = date('Y');
                                            $awal = $tahunSaatIni - 5;
                                            $akhir = $tahunSaatIni + 5;
                                        @endphp

                                        @for ($tahun = $earliest; $tahun <= $akhir; $tahun++)
                                            <option {{ Request()->tahun == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                                                {{ $tahun }}</option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <small class="text-red-500 text-xs">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                                <div class="input-box">
                                    <label for="Bulan">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-input">
                                        <option value="0">--- Pilih Bulan ---</option>
                                        <option value='1' @if(\Request::get('bulan') == '1') selected @endif>Januari</option>
                                        <option value='2' @if(\Request::get('bulan') == '2') selected @endif>Februari </option>
                                        <option value='3' @if(\Request::get('bulan') == '3') selected @endif>Maret</option>
                                        <option value='4' @if(\Request::get('bulan') == '4') selected @endif>April</option>
                                        <option value='5' @if(\Request::get('bulan') == '5') selected @endif>Mei</option>
                                        <option value='6' @if(\Request::get('bulan') == '6') selected @endif>Juni</option>
                                        <option value='7' @if(\Request::get('bulan') == '7') selected @endif>Juli</option>
                                        <option value='8' @if(\Request::get('bulan') == '8') selected @endif>Agustus</option>
                                        <option value='9' @if(\Request::get('bulan') == '9') selected @endif>September</option>
                                        <option value='10' @if(\Request::get('bulan') == '10') selected @endif>Oktober</option>
                                        <option value='11' @if(\Request::get('bulan') == '11') selected @endif>November</option>
                                        <option value='12' @if(\Request::get('bulan') == '12') selected @endif>Desember</option>
                                    </select>
                                    @error('bulan')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="flex justify-end w-fit my-3">
                                @if (\Request::has('tahun') && count($data) > 0)
                                    <div class="mr-2" id="btn-download">
                                        <a href="{{ route('download-rekapitulasi') }}" download
                                            class="m-0 btn btn-lg is-btn btn-warning">
                                            <span style="font-size: 14px;">Excel</span>
                                        </a>
                                    </div>
                                @endif
                                <div>
                                    <input type="submit" value="Tampilkan" class="btn btn-primary is-btn is-primary cursor-pointer">
                                </div>
                            </div>
                            @if (\Request::has('tahun'))
                                <div class="layout-component">
                                    <div class="shorty-table">
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
                                    <div class="input-search">
                                        <i class="ti ti-search"></i>
                                        <input type="search" name="q" id="q" placeholder="Cari disini..."
                                            value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                                    </div>
                                </div>
                                @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                                @endphp
                                @if (\Request::has('tahun'))
                                    <div class="table-responsive" style="overflow-x: auto;">
                                        @include('rekap-tetap.table.table')

                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <div>
                                        Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                                    </div>
                                    <div>
                                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $data->appends(\Request::except('page'))->links('pagination::tailwind') }}
                                        @endif
                                    </div>
                                </div>

                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var selected_kantor = $('#kantor').val()
        if (selected_kantor == '0' || selected_kantor == 'pusat') {
            // Hide cabang
            $('.cabang-input').addClass('d-none')
        }
        else {
            // Show cabang
            $('.cabang-input').removeClass('d-none')
        }

        $('#kantor').on('change', function() {
            $('#btn-download').addClass('d-none')
        })

        $('#cabang').on('change', function() {
            $('#btn-download').addClass('d-none')
        })

        $('#bulan').on('change', function() {
            $('#btn-download').addClass('d-none')
        })

        $('#tahun').on('change', function() {
            $('#btn-download').addClass('d-none')
        })

        $('#kategori').on('change', function() {
            $('#btn-download').addClass('d-none')
        })

        const formatRupiahPayroll = (angka) => {
            let reverse = angka.toString().split('').reverse().join('');
            let ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return `${ribuan}`;
        }

        $('.show-data').on('click',function(e) {
            const targetId = $(this).data("target-id");
            const data = $(this).data('json');
            $('#table-tunjangan-tidak > tbody').empty();
            $('#table-tunjangan-total-tidak thead').empty();

            $('#table-tunjangan > tbody').empty();
            $('#table-tunjangan-total thead ').empty();

            $('#nip').html(`${data.nip}`)
            $('#nama').html(`${data.nama_karyawan}`)
            $('#no_rekening').html(`${data.no_rekening != null ? data.no_rekening : '-'}`)

            var nominal = 0;
            var tableTunjangan = `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Gaji Pokok</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gaji['total_gaji'])}</td>
                    </tr>
            `;
            // START TUNJANGAN TERATUR
            $.each(data.tunjangan, function( key, value ) {
                nominal += value.pivot.nominal ;
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="text-left fw-bold">${value.nama_tunjangan}</td>
                        <td class="text-right">${formatRupiahPayroll(value.pivot.nominal)}</td>
                    </tr>
                `
            });
            $("#table-tunjangan tbody").append(tableTunjangan);

            var tableTotalTunjanganTeratur = `
                <tr>
                    <th width="60%">GAJI POKOK + PENGHASILAN TERATUR</th>
                    <th class="text-right ">${formatRupiahPayroll(nominal + data.gaji['total_gaji'])}</th>
                </tr>
            `
            $("#table-tunjangan-total thead").append(tableTotalTunjanganTeratur);
            // END TUNJANGAN TERATUR
        })
        function showModal(identifier) {
        }

        $('#kantor').on('change', function() {
            const selected = $(this).val()

            if (selected == 'cabang') {
                $('.cabang-input').removeClass('d-none')
            }
            else {
                $('#cabang option[value="0"]').attr("selected", "selected");
                $('.cabang-input').addClass('d-none')
            }
        })

        $('#page_length').on('change', function() {
            $('#form').submit()
        })

        $('#form').on('submit', function() {
            $('.loader-wrapper').css('display: none;')
            $('.loader-wrapper').addClass('d-block')
            $(".loader-wrapper").fadeOut("slow");
        })
    </script>
@endpush
