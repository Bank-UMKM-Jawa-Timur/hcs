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
                Payroll
            </div>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Penghasilan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="/" class="text-sm text-gray-500 font-bold">Payroll</a>
            </div>
        </div>
    </div>
</div>

    <div class="body-pages">
        <div class="table-wrapping">
            <form id="form" method="get">
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                    @if (auth()->user()->hasRole('cabang'))
                        <input type="hidden" name="kantor" value="cabang">
                    @else
                        <div class="input-box">
                                <label for="">Kantor<span class="text-danger">*</span></label>
                                <select name="kantor" id="kantor"
                                    class="form-input">
                                    <option value="0">-- Pilih kantor --</option>
                                    <option value="pusat" @if(\Request::get('kantor') == 'pusat') selected @endif
                                        {{old('kantor') == 'pusat' ? 'selected' : ''}}>Pusat</option>
                                    <option value="cabang" @if(\Request::get('kantor') != '' && \Request::get('kantor') != 'pusat') selected @endif
                                        {{old('kantor') == 'cabang' ? 'selected' : ''}}>Cabang</option>
                                </select>
                                @error('kantor')
                                    <small class="text-danger">{{ucfirst($message)}}</small>
                                @enderror
                        </div>
                    @endif
                    @if (auth()->user()->hasRole('cabang'))
                        <input type="hidden" name="cabang" value="{{auth()->user()->kd_cabang}}">
                    @else
                        <div class="input-box cabang-input @if(\Request::get('kantor') == 'pusat' || \Request::get('kantor') == '0')d-none @endif">
                            <label for="">Cabang<span class="text-danger">*</span></label>
                            <select name="cabang" id="cabang"
                                class="form-input form-control select2">
                                <option value="0">-- Pilih cabang --</option>
                                @foreach ($cabang as $item)
                                    <option value="{{$item->kd_cabang}}" @if(\Request::get('cabang') == $item->kd_cabang) selected @endif>{{$item->nama_cabang}}</option>
                                @endforeach
                            </select>
                            @error('cabang')
                                <small class="text-danger">{{ucfirst($message)}}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="input-box">
                            <label for="">Kategori Gaji Pegawai<span class="text-danger">*</span></label>
                            <select name="kategori" id="kategori"
                                class="form-input">
                                <option value="0">-- Pilih kategori --</option>
                                <option value="rincian" @if(\Request::get('kategori') == 'rincian') selected @endif
                                    {{old('kategori') == 'rincian' ? 'selected' : ''}}>Rincian</option>
                                <option value="payroll" @if(\Request::get('kategori') == 'payroll') selected @endif
                                    {{old('kategori') == 'payroll' ? 'selected' : ''}}>Payroll</option>
                            </select>
                            @error('kategori')
                                <small class="text-danger">{{ucfirst($message)}}</small>
                            @enderror
                    </div>
                    <div class="input-box">
                            <label for="">Bulan<span class="text-danger">*</span></label>
                            <select name="bulan" id="bulan"
                                class="form-input">
                                <option value="0">-- Pilih bulan --</option>
                                <option value="1" @if(\Request::get('bulan') == '1') selected @endif>Januari</option>
                                <option value="2" @if(\Request::get('bulan') == '2') selected @endif>Februari</option>
                                <option value="3" @if(\Request::get('bulan') == '3') selected @endif>Maret</option>
                                <option value="4" @if(\Request::get('bulan') == '4') selected @endif>April</option>
                                <option value="5" @if(\Request::get('bulan') == '5') selected @endif>Mei</option>
                                <option value="6" @if(\Request::get('bulan') == '6') selected @endif>Juni</option>
                                <option value="7" @if(\Request::get('bulan') == '7') selected @endif>Juli</option>
                                <option value="8" @if(\Request::get('bulan') == '8') selected @endif>Agustus</option>
                                <option value="9" @if(\Request::get('bulan') == '9') selected @endif>September</option>
                                <option value="10" @if(\Request::get('bulan') == '10') selected @endif>Oktober</option>
                                <option value="11" @if(\Request::get('bulan') == '11') selected @endif>November</option>
                                <option value="12" @if(\Request::get('bulan') == '12') selected @endif>Desember</option>
                            </select>
                            @error('bulan')
                                <small class="text-danger">{{ucfirst($message)}}</small>
                            @enderror
                    </div>
                    <div class="input-box">
                            <label for="">Tahun<span class="text-danger">*</span></label>
                            <select name="tahun" id="tahun"
                                class="form-input">
                                <option value="">Pilih Tahun</option>
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
                                <small class="text-danger">{{ucfirst($message)}}</small>
                            @enderror
                    </div>
                </div>
                <div class="flex items-start gap-2 w-fit">
                    @can('penghasilan - payroll - download')
                        @if (\Request::has('kantor') && count($data) > 0)
                            <div class="mr-2 mt-5" id="btn-download w-fit">
                                <a href="{{ route('payroll.pdf') }}" target="_blank"
                                    class="m-0 btn btn-lg is-btn btn-warning">
                                    <span style="font-size: 14px;">Download PDF</span>
                                </a>
                            </div>
                        @endif
                    @endcan
                    <div>
                        <input type="submit" value="Tampilkan" class="btn btn-primary cursor-pointer mt-5">
                    </div>
                </div>
                @if (\Request::has('kantor') && \Request::has('bulan'))
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
                            <input type="search" name="q" id="q" placeholder="Cari nama karyawan disini..."
                                class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}"
                                style="width: 300px;">
                        </div>
                    </div>
                    @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                    @endphp
                    @if (\Request::get('kategori') == 'payroll')
                        <div class="relative overflow-x-auto">
                            @include('payroll.tables.payroll', ['data' => $data, 'total' => $total])
                        </div>
                    @elseif (\Request::get('kategori') == 'rincian')
                        <div class="relative overflow-x-auto">
                            @include('payroll.tables.rincian', ['data' => $data])
                        </div>
                    @else
                        <span class="text-warning">Harap pilih kategori yang benar!</span>
                    @endif
                    <div class="table-footer">
                        <div class="showing">
                            Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                        </div>
                        <div class="pagination">
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind') }}
                            @endif
                        </div>
                    </div>

                @endif
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal-layout hidden" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal modal-lg">
            <div class="modal-head">
                <h5 class="modal-title" id="exampleModalLabel">Slip Gaji</h5>
                <button data-modal-dismiss="default-modal"  class="modal-close"><i class="ti ti-x"></i></button>

            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <div>
                        <button type="button" class="is-btn is-primary">Cetak Gaji</button>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <div>
                        <img src="{{ asset('style/assets/img/logo.png') }}" width="100px" class="img-fluid">
                        <p class="pt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="row">
                        <div class="col-lg-12 mt-3">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">NIP</td>
                                    <td>:</td>
                                    <td id="nip"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Karyawan</td>
                                    <td>:</td>
                                    <td id="nama"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No Rekening</td>
                                    <td>:</td>
                                    <td id="no_rekening"></td>
                                </tr>
                            </table>
                            <hr>
                        </div>
                        <div class="col-lg-12 m-0">
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                                <thead>
                                    <th>Nama</th>
                                    <th class="text-right">Nominal</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <table class="table table-borderless" id="table-tunjangan-total">
                                <thead></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('extraScript')
    <script>
        var selected_kantor = $('#kantor').val()
        if (selected_kantor == '0' || selected_kantor == 'pusat') {
            // Hide cabang
            $('.cabang-input').addClass('hidden')
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
                $('.cabang-input').removeClass('hidden')
            }
            else {
                $('#cabang option[value="0"]').attr("selected", "selected");
                $('.cabang-input').addClass('hidden')
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

        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('kantor')) {
                btn_pagination[i].href += `&kantor=${$('#kantor').val()}`
            }
            if (page_url.includes('kategori')) {
                btn_pagination[i].href += `&kategori=${$('#kategori').val()}`
            }
            if (page_url.includes('cabang')) {
                var cabang = "{{\Request::get('cabang')}}"
                btn_pagination[i].href += `&cabang=${cabang}`
            }
            if (page_url.includes('bulan')) {
                btn_pagination[i].href += `&bulan=${$('#bulan').val()}`
            }
            if (page_url.includes('tahun')) {
                btn_pagination[i].href += `&tahun=${$('#tahun').val()}`
            }
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endpush
