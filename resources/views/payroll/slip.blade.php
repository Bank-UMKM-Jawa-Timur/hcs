@extends('layouts.template')
@include('vendor.select2')
@include('payroll.scripts.slip')
@push('style')
    <style>
        table th {
            border: 1px solid #e3e3e3 !important;
        }
    </style>
@endpush

@section('content')
    <div class="card-header">
        <h5 class="card-title">Payroll</h5>
        <p class="card-title">Payroll > <a href="{{route('payroll.slip')}}">Slip Gaji</a></p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Kantor<span class="text-danger">*</span></label>
                                    <select name="kantor" id="kantor"
                                        class="form-control">
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
                            </div>
                            <div class="col cabang-input @if(\Request::get('kantor') == 'pusat' || \Request::get('kantor') == '0')d-none @endif">
                                <div class="form-group">
                                    <label for="">Cabang</label>
                                    <select name="cabang" id="cabang"
                                        class="form-control select2">
                                        <option value="0">-- Semua Cabang --</option>
                                        @foreach ($cabang as $item)
                                            <option value="{{$item->kd_cabang}}" @if(\Request::get('cabang') == $item->kd_cabang) selected @endif>{{$item->nama_cabang}}</option>
                                        @endforeach
                                    </select>
                                    @error('cabang')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col divisi-input @if(\Request::get('kantor') != 'pusat')d-none @endif">
                                <div class="form-group">
                                    <label for="">Divisi</label>
                                    <select name="divisi" id="divisi"
                                        class="form-control select2">
                                    </select>
                                    @error('divisi')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col sub-divisi-input @if(\Request::get('kantor') != 'pusat')d-none @endif">
                                <div class="form-group">
                                    <label for="">Sub Divisi</label>
                                    <select name="sub_divisi" id="sub_divisi"
                                        class="form-control select2">
                                        <option value="0">-- Semua Sub Divisi --</option>
                                    </select>
                                    @error('sub_divisi')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Bagian</label>
                                    <select name="bagian" id="bagian"
                                        class="form-control select2">
                                        <option value="0">-- Semua Bagian --</option>
                                    </select>
                                    @error('bagian')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Karyawan</label>
                                    <select name="nip" id="nip"
                                        class="form-control select2">
                                        <option value="0">-- Pilih Semua Karyawan --</option>
                                    </select>
                                    @error('nip')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Bulan<span class="text-danger">*</span></label>
                                    <select name="bulan" id="bulan"
                                        class="form-control" required>
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
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Tahun<span class="text-danger">*</span></label>
                                    <select name="tahun" id="tahun"
                                        class="form-control" required>
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
                            @if (\Request::has('kantor') && !empty($data))
                                <div class="mr-2">
                                    <a href="{{ route('payroll.pdf') }}" target="_blank"  class="btn btn-warning">Cetak PDF</a>
                                </div>
                            @endif
                            <div>
                                <input type="submit" value="Tampilkan" class="btn btn-primary">
                            </div>
                        </div>
                        @if (\Request::has('kantor'))
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
                            <div class="table-responsive">
                                @include('payroll.tables.slip', ['data' => $data])
                            </div>
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
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL  --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Slip Gaji</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <div>
                        <input type="text" id="id_nip" name="id_nip" hidden>
                        <a href="{{ route('payroll.slip.pdf') }}" target="_blank" class="btn btn-primary" id="cetak-gaji">Cetak Gaji</a>
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
                                    <td id="data-nip"></td>
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
                    </div>
                    <div class="row">
                        <div class="col-lg-6 m-0">
                            <h4 class="font-weight-bold">Pendapatan</h4>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                                <tbody>

                                </tbody>
                            </table>
                            <table class="table table-borderless" id="table-tunjangan-total">
                                <thead></thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4 class="font-weight-bold">Potongan</h4>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-potongan">
                                <tbody>

                                </tbody>
                            </table>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-total-potongan">
                                <thead>

                                </thead>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-total-diterima">
                                <thead>

                                </thead>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
            {{-- <div class="modal-footer">

            </div> --}}
        </div>
        </div>
    </div>
@endsection
@push('script')
    <script>

        $('.show-data').on('click',function(e) {
            // console.log(e);
            const targetId = $(this).data("target-id");
            const data = $(this).data('json');
            // $('#table-tunjangan-tidak > tbody').empty();
            // $('#table-tunjangan-total-tidak thead').empty();
            $('#id_nip').val(data.nip);
            $('#cetak-gaji').on('click',function(e) {
                var nip =  $('#id_nip').val();
                var kantor = $('#kantor').val();
                var month = $('#bulan').val()
                var year = $('#tahun').val()
                console.log(`${nip}=${kantor}=${month}=${year}`);
                $.ajax({
                        type: "GET",
                        url: `{{ route('payroll.cetak_slip') }}`,
                        data: {
                            request_nip: nip,
                            request_kantor: kantor,
                            request_month: month,
                            request_year: year,
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(response){
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "slip-gaji.pdf";
                            link.click();
                        },
                        // complete: function () {
                        //     // Remove the loading message or indicator after the API call is complete
                        //     $('#loading-message').empty();
                        // }
                });
                // window.location.href = `{{ route('payroll.cetak_slip') }}`
            })
            $('#table-tunjangan > tbody').empty();
            $('#table-tunjangan-total thead ').empty();

            $('#table-potongan > tbody').empty()
            $("#table-total-potongan thead").empty()

            $("#table-total-diterima thead").empty();

            $('#data-nip').html(`${data.nip}`)
            $('#nama').html(`${data.nama_karyawan}`)
            $('#no_rekening').html(`${data.no_rekening != null ? data.no_rekening : '-'}`)

            var nominal = 0;
            // console.log(typeof(data.gaji));
            // Tunjangan
            var tableTunjangan = `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Gaji Pokok</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gaji.total_gaji)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Jabatan</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gaji.tj_jabatan)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Penyesuaian</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gaji.gj_penyesuaian)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Keluarga</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gaji.tj_keluarga)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Kemahalan</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_kemahalan)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Kesejahteraan</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_kesejahteraan)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Multilevel</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_multilevel)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Pelaksana</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_pelaksana)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Perumahan</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_perumahan)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Pulsa</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_pulsa)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Telepon</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_telepon)}</td>
                    </tr>
                   ${
                        !data.gaji.hasOwnProperty('tj_teller') ? (
                            `<tr style="border:1px solid #e3e3e3">
                                <td>Teller</td>
                                <td class="text-right">${formatRupiahPayroll(data.gaji.tj_teller)}</td>
                            </tr>`
                        ) : null
                   }
                    <tr style="border:1px solid #e3e3e3">
                        <td>Transport</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_transport)}</td>
                    </tr>
                    <tr style="border:1px solid #e3e3e3">
                        <td>Vitamin</td>
                        <td class="text-right">${formatRupiahPayroll(data.gaji.tj_vitamin)}</td>
                    </tr>
            `;

            $("#table-tunjangan tbody").append(tableTunjangan);

            var tableTotalTunjanganTeratur = `
                <tr>
                    <th width="60%">GAJI POKOK + PENGHASILAN TERATUR</th>
                    <th class="text-right ">${formatRupiahPayroll(data.gaji.total_gaji)}</th>
                </tr>
            `
            $("#table-tunjangan-total thead").append(tableTotalTunjanganTeratur);
            // END TUNJANGAN TERATUR
            // POTONGAN
            var potongan = `
                <tr style="border:1px solid #e3e3e3">
                    <td>JP BPJS TK 1%</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.potongan.jp_1_persen)}</td>
                </tr>
                <tr style="border:1px solid #e3e3e3">
                    <td>DPP 5%</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.potongan.dpp)}</td>
                </tr>
                <tr style="border:1px solid #e3e3e3">
                    <td>KREDIT KOPERASI</td>
                    <td id="gaji_pokok" class="text-right">${data.potongan_gaji ? formatRupiahPayroll(parseInt(data.potongan_gaji.kredit_koperasi)) : 0}</td>
                </tr>
                <tr style="border:1px solid #e3e3e3">
                    <td>IUARAN KOPERASI	</td>
                    <td id="gaji_pokok" class="text-right">${data.potongan_gaji ? formatRupiahPayroll(parseInt(data.potongan_gaji.iuran_koperasi)) : 0}</td>
                </tr>
                <tr style="border:1px solid #e3e3e3">
                    <td>KREDIT PEGAWAI	</td>
                    <td id="gaji_pokok" class="text-right">${data.potongan_gaji ? formatRupiahPayroll(parseInt(data.potongan_gaji.kredit_pegawai)) : 0}</td>
                </tr>
                <tr style="border:1px solid #e3e3e3">
                    <td>IURAN IK</td>
                    <td id="gaji_pokok" class="text-right">${data.potongan_gaji ? formatRupiahPayroll(parseInt(data.potongan_gaji.iuran_ik)) : 0}</td>
                </tr>
            `
            $('#table-potongan tbody').append(potongan);
            var tableTotalPotongan = `
                <tr>
                    <th width="60%">TOTAL POTONGAN</th>
                    <th class="text-right ">${formatRupiahPayroll(data.total_potongan)}</th>
                </tr>
            `
            $("#table-total-potongan thead").append(tableTotalPotongan);
            // END POTONGAN
            var tableTotalDiterima = `
                <tr>
                    <th width="60%">Total Yang Diterima</th>
                    <th class="text-right ">${data.total_yg_diterima > 0 ? formatRupiahPayroll(data.total_yg_diterima) : '-'}</th>
                </tr>
            `
            $("#table-total-diterima thead").append(tableTotalDiterima);
        })
    </script>
@endpush
