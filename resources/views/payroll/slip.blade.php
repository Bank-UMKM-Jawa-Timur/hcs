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
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title">Gaji</h5>
        <p class="card-title">Gaji > <a href="{{route('payroll.slip')}}">Slip Gaji</a></p>
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
                            <div class="d-flex justify-content-end my-3">
                                <input type="submit" value="Tampilkan" class="is-btn is-primary">
                            </div>
                            @if (\Request::has('nip') && \Request::has('tahun'))
                                <h5>Slip Gaji {{$karyawan->nama_karyawan}} Tahun {{\Request::get('tahun')}}.</h5>
                                <div class="table-responsive">
                                    @include('payroll.tables.slip', ['data' => $data])
                                </div>
                            @endif
                        </form>
                    </div>
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
                        <button class="is-btn is-primary" id="cetak-gaji">Cetak Gaji</button>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <div>
                        <img src="{{ asset('style/assets/img/logo.png') }}" width="100px" class="img-fluid">
                        <p class="pt-3">SLIP GAJI PEGAWAI. </p>
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
                                    <td class="fw-bold">Jabatan</td>
                                    <td>:</td>
                                    <td id="data-jabatan"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Karyawan</td>
                                    <td>:</td>
                                    <td id="nama"></td>
                                    <td class="fw-bold">Tanggal Bergabung</td>
                                    <td>:</td>
                                    <td id="tanggal-bergabung"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No Rekening</td>
                                    <td>:</td>
                                    <td id="no_rekening"></td>
                                    <td class="fw-bold">Lama Kerja</td>
                                    <td>:</td>
                                    <td id="lama-kerja"></td>
                                </tr>
                            </table>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 m-0">
                            <div class="d-flex justify-content-around bg-primary text-white p-1 rounded">
                                <h4 class="font-weight-bold ">Pendapatan</h4>
                                <h4 class="font-weight-bold ">Jumlah</h4>
                            </div>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                                <tbody>

                                </tbody>
                            </table>
                            <table class="table table-borderless" id="table-tunjangan-total">
                                <thead></thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-around bg-primary text-white p-1 rounded">
                                <h4 class="font-weight-bold ">Potongan</h4>
                                <h4 class="font-weight-bold ">Jumlah</h4>
                            </div>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-potongan">
                                <tbody>

                                </tbody>
                            </table>
                            <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-total-potongan">
                                <thead>

                                </thead>

                            </table>
                        </div>
                        <br>
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
        function convertToTerbilang(number) {
            var bilangan = [
                '',
                'Satu',
                'Dua',
                'Tiga',
                'Empat',
                'Lima',
                'Enam',
                'Tujuh',
                'Delapan',
                'Sembilan',
                'Sepuluh',
                'Sebelas'
            ];

            var terbilang = '';

            if (number < 12) {
                terbilang = bilangan[number];
            } else if (number < 20) {
                terbilang = convertToTerbilang(number - 10) + ' Belas';
            } else if (number < 100) {
                terbilang = convertToTerbilang(Math.floor(number / 10)) + ' Puluh ' + convertToTerbilang(number % 10);
            } else if (number < 200) {
                terbilang = ' Seratus ' + convertToTerbilang(number - 100);
            } else if (number < 1000) {
                terbilang = convertToTerbilang(Math.floor(number / 100)) + ' Ratus ' + convertToTerbilang(number % 100);
            } else if (number < 2000) {
                terbilang = ' Seribu ' + convertToTerbilang(number - 1000);
            } else if (number < 1000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000)) + ' Ribu ' + convertToTerbilang(number % 1000);
            } else if (number < 1000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000)) + ' Juta ' + convertToTerbilang(number % 1000000);
            } else if (number < 1000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000)) + ' Miliar ' + convertToTerbilang(number % 1000000000);
            } else if (number < 1000000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000000)) + ' Triliun ' + convertToTerbilang(number % 1000000000000);
            }

            return terbilang ;
        }

        function generatePendapatanItem(data) {
            var tableTunjangan = ``;

            // Gaji Pokok
            if (data.gj_pokok > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Gaji Pokok</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gj_pokok)}</td>
                    </tr>
                `
            }
            // Jabatan
            if (data.tj_jabatan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Jabatan</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.tj_jabatan)}</td>
                    </tr>
                `
            }
            // Gaji Penyesuaian
            if (data.gj_penyesuaian > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Penyesuaian</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.gj_penyesuaian)}</td>
                    </tr>
                `
            }
            // T. Keluarga
            if (data.tj_keluarga > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Keluarga</td>
                        <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.tj_keluarga)}</td>
                    </tr>
                `
            }
            // T. Kemahalan
            if (data.tj_kemahalan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Kemahalan</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_kemahalan)}</td>
                    </tr>
                `
            }
            // T. Kesejahteraan
            if (data.tj_kesejahteraan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Kesejahteraan</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_kesejahteraan)}</td>
                    </tr>
                `
            }
            // T. Multilevel
            if (data.tj_multilevel > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Multilevel</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_multilevel)}</td>
                    </tr>
                `
            }
            // T. Pelaksana
            if (data.tj_pelaksana > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Pelaksana</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_pelaksana)}</td>
                    </tr>
                `
            }
            // T. Perumahan
            if (data.tj_perumahan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Perumahan</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_perumahan)}</td>
                    </tr>
                `
            }
            // T. Pulsa
            if (data.tj_pulsa > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Pulsa</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_pulsa)}</td>
                    </tr>
                `
            }
            // T. Telepon
            if (data.tj_telepon > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td>Telepon</td>
                        <td class="text-right">${formatRupiahPayroll(data.tj_telepon)}</td>
                    </tr>
                `
            }
            // T. Teller
            /*if (data.tj_teller > 0) {
                tableTunjangan += `
                    ${
                        !data.hasOwnProperty('tj_teller') ? (
                            `<tr style="border:1px solid #e3e3e3">
                                <td>Teller</td>
                                <td class="text-right">${formatRupiahPayroll(data.tj_teller)}</td>
                            </tr>`
                        ) : null
                    }
                `
            }*/

            return tableTunjangan;
        }

        function lamaBekerja(date) {
            var dateFormat = /^\d{4}-\d{2}-\d{2}$/;

            if (!date.match(dateFormat)) {
                console.error("Please use the format 'Y-m-d'.");
                return null;
            }

            var dateParts = date.split("-");
            var mulaiKerja = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

            var now = new Date();

            var differenceInMilliseconds = now - mulaiKerja;

            var years = Math.floor(differenceInMilliseconds / (365.25 * 24 * 60 * 60 * 1000));
            var months = Math.floor((differenceInMilliseconds % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));

            return {
                tahun: years,
                bulan: months,
            };
        }

        $('.show-data').on('click',function(e) {
            const targetId = $(this).data("target-id");
            const nip = "{{\Request::get('nip')}}";
            const tahun = "{{\Request::get('tahun')}}";
            const nama = $(this).data("nama");
            const norek = $(this).data("no_rekening");
            const jabatan = $(this).data("status_jabatan");
            const tanggalPengangkat = $(this).data("tanggal_pengangkat");
            const result = lamaBekerja(tanggalPengangkat);
            const data = $(this).data('json');
            const bulan = data.bulan;


            $('#cetak-gaji').on('click',function(e) {
                $.ajax({
                        type: "GET",
                        url: `{{ route('payroll.cetak_slip') }}`,
                        data: {
                            request_nip: nip,
                            request_month: bulan,
                            request_year: tahun,
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        // beforeSend: function () {
                        //     $('#loading-message').html(`
                        //             <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        //                 Loading Data...
                        //                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        //                     <span aria-hidden="true">&times;</span>
                        //                 </button>
                        //             </div>
                        //     `);
                        // },
                        success: function(response){
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "slip-gaji.pdf";
                            link.click();
                        },
                });
            })
            $('#table-tunjangan > tbody').empty();
            $('#table-tunjangan-total thead ').empty();

            $('#table-potongan > tbody').empty()
            $("#table-total-potongan thead").empty()

            $("#table-total-diterima thead").empty();

            $('#data-nip').html(`${nip}`)
            $('#nama').html(`${nama}`)
            $('#no_rekening').html(`${norek != null ? norek : '-'}`)
            $('#data-jabatan').html(`${jabatan}`)
            $('#tanggal-bergabung').html(`${tanggalPengangkat}`)

            if (result !== null) {
                const { tahun, bulan } = result;
                const settDate = `${tahun} tahun, ${bulan} bulan`;
                $('#lama-kerja').html(settDate);
            } else {
                $('#lama-kerja').html("Invalid date format");
            }

            var nominal = 0;
            // Tunjangan
            var tableTunjangan = generatePendapatanItem(data);

            $("#table-tunjangan tbody").append(tableTunjangan);

            var tableTotalTunjanganTeratur = `
                <tr>
                    <th width="60%">Total (THP)</th>
                    <th class="text-right ">${formatRupiahPayroll(data.total_gaji)}</th>
                </tr>
            `
            $("#table-tunjangan-total thead").append(tableTotalTunjanganTeratur);
            // END TUNJANGAN TERATUR
            // POTONGAN
            var kredit_koperasi = data.kredit_koperasi ? data.kredit_koperasi : 0;
            var iuran_koperasi = data.iuran_koperasi ? data.iuran_koperasi : 0;
            var kredit_pegawai = data.kredit_pegawai ? data.kredit_pegawai : 0;
            var iuran_ik = data.iuran_ik ? data.iuran_ik : 0;
            var bpjs_tk = data.bpjs_tk ? data.bpjs_tk : 0;
            var potongan_dpp = data.potongan.dpp ? data.potongan.dpp : 0;
            var total_potongan = parseInt(data.bpjs_tk) + parseInt(data.potongan.dpp) + parseInt(kredit_koperasi) + parseInt(iuran_koperasi) + parseInt(kredit_pegawai) + parseInt(iuran_ik);
            var total_diterima = parseInt(data.total_gaji) - total_potongan;
            var potongan = ``;

            if (bpjs_tk > 0 ) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td>JP BPJS TK 1%</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(parseInt(data.bpjs_tk))}</td>
                </tr>`
            }
            if (potongan_dpp > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td>DPP 5%</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(data.potongan.dpp)}</td>
                </tr>`
            }
            if (kredit_koperasi > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td>KREDIT KOPERASI</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(parseInt(data.kredit_koperasi))}</td>
                </tr>`
            }
            if (iuran_koperasi > 0) {
                potongan += `<tr style="border:1px solid #e3e3e3">
                    <td>IUARAN KOPERASI	</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(parseInt(data.iuran_koperasi))}</td>
                </tr>`
            }
            if (kredit_pegawai > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td>KREDIT PEGAWAI	</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(parseInt(data.kredit_pegawai))}</td>
                </tr>`
            }
            if (iuran_ik > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td>IURAN IK</td>
                    <td id="gaji_pokok" class="text-right">${formatRupiahPayroll(parseInt(data.iuran_ik))}</td>
                </tr>`
            }
            $('#table-potongan tbody').append(potongan);
            var tableTotalPotongan = `
                <tr>
                    <th width="60%">TOTAL POTONGAN</th>
                    <th class="text-right ">${formatRupiahPayroll(total_potongan.toString())}</th>
                </tr>
            `
            $("#table-total-potongan thead").append(tableTotalPotongan);
            // END POTONGAN
            var totalGaji = total_diterima > 0 ? formatRupiahPayroll(total_diterima.toString()) : '-';
            var terbilang = total_diterima > 0 ? total_diterima : '-';
            var tableTotalDiterima = `
                <tr class="bg-primary text-white p-1 rounded">
                    <th colspan="2" width="40%">Total Yang Diterima</th>
                </tr>
                <tr>
                    <th width="40%">Jumlah</th>
                    <th>${totalGaji}</th>
                </tr
                <tr>
                    <th width="40%">Terbilang</th>
                    <th>${convertToTerbilang(terbilang) + " Rupiah"}</th>
                </tr
            `
            $("#table-total-diterima thead").append(tableTotalDiterima);
        })
    </script>
@endpush
