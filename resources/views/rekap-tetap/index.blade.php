@extends('layouts.template')
@push('style')
    <style>
        table th {
            border: 1px solid #e3e3e3 !important;
        }
    </style>
@endpush

@section('content')
    <div class="d-lg-flex justify-content-between w-100 p-3">
        <div class="card-header">
            <h5 class="card-title">Rekap Tetap</h5>
            <p class="card-title"><a href="">Laporan </a> > Rekap Tetap</p>
        </div>
    </div>
    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive overflow-hidden content-center">
                        <form id="form" method="get">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Tahun<span class="text-danger">*</span></label>
                                        <select name="tahun" id="tahun"
                                            class="form-control">
                                            <option value="0">-- Pilih tahun --</option>
                                            @php
                                                $sekarang = date('Y');
                                                $awal = $sekarang - 5;
                                                $akhir = $sekarang + 5;
                                            @endphp
                                            @for($i=$awal;$i<=$akhir;$i++)
                                                <option value="{{$i}}" @if(\Request::get('tahun') == $i) selected @endif>{{$i}}</option>
                                            @endfor
                                        </select>
                                        @error('tahun')
                                            <small class="text-danger">{{ucfirst($message)}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if (\Request::has('tahun') && count($data) > 0)
                                    <div class="mr-2" id="btn-download">
                                        <a href="{{ route('download-rekap-tetap') }}" download
                                            class="m-0 btn btn-lg is-btn btn-warning">
                                            <span style="font-size: 14px;">Excel</span>
                                        </a>
                                    </div>
                                @endif
                                <div>
                                    <input type="submit" value="Tampilkan" class="is-btn is-primary">
                                </div>
                            </div>
                            @if (\Request::has('tahun'))
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
                                @if (\Request::has('tahun'))
                                    <div class="table-responsive">
                                        @include('rekap-tetap.table.table')

                                    </div>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <div>
                                        Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                                    </div>
                                    <div>
                                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        {{ $data->appends(\Request::except('page'))->links('pagination::bootstrap-4') }}
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
