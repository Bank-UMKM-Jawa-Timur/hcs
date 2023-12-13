<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="dompdf.view" content="FitV" />
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Slip Gaji</title>
</head>
<body>
    <style>

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font-family: 'Tinos', serif;
            font: 12pt;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        p, table, ol{
            font-size: 9pt;
        }

        .text-utama{
            color: #DA251D;
        }

        .bg-utama{
            background-color: #DA251D;
        }
        .table td, .table th{
            padding: 3px !important;
        }

        @page {
            margin: 0;  /* Ini akan diterapkan ke setiap halaman */
            size: potrait;
        }

        @page :first {
            margin-top: 10mm;  /* Hanya diterapkan ke halaman pertama */
        }
        @media print {
            body {-webkit-print-color-adjust: exact;}
            .text-utama{
                color: #DA251D;
                print-color-adjust: exact;
            }

            .bg-utama{
                background-color: #DA251D;
                print-color-adjust: exact;
            }
            /* Sembunyikan thead di semua halaman */
            thead {
                display: table-row-group;
            }

            /* thead.no-print {
                display: none;
            } */
            tfoot{ display:table-row-group }

            @page {
                /* Hanya tampilkan thead di halaman pertama */
                margin-top: 0;
            }

            @page :not(:first) {
                margin-top: 0;
            }
            /* html, body {
                width: 210mm;
                height: 297mm;
            } */
            .no-print, .no-print *
            {
                display: none !important;
            }
        /* ... the rest of the rules ... */
        }
    </style>
    <div class="p-2">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="d-flex">
                    <center>
                        <img src="{{public_path('style/assets/img/logo.png') }}" width="150px" class="img-fluid">
                        <h4 class="fw-bold p-3"> PT HCS</h4>
                    </center>
                    <div class="content">
                        <p class="text-start">SLIP GAJI PEGAWAI.</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- <h4 class="fw-bold text-center mt-5 mb-5">SLIP GAJI KARYAWAN</h4> --}}
        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Data Karyawan</h6>
        </div>
        <div class="row mb-5 pb-5">
            <div class="col-lg-4 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">NIP</td>
                        <td>:</td>
                        <td>{{ $data->nip }}</td>
                        <td class="fw-bold">Jabatan</td>
                        <td>:</td>
                        <td>{{$data->status_jabatan}}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>:</td>
                        <td>{{ $data->nama_karyawan }}</td>
                        <td class="fw-bold">Tanggal Bergabung</td>
                        <td>:</td>
                        <td>{{\Carbon\Carbon::parse($data->tanggal_pengangkat)->translatedFormat('d F Y')}}</td>
                    </tr>
                    @php
                        $mulaKerja = Carbon::create($karyawan->tanggal_pengangkat);
                        $waktuSekarang = Carbon::now();

                        $hitung = $waktuSekarang->diff($mulaKerja);
                        $masaKerja = $hitung->format('%y Tahun, %m Bulan');
                    @endphp
                    <tr>
                        <td class="fw-bold">No Rekening</td>
                        <td>:</td>
                        <td>{{ $data->no_rekening }}</td>
                        <td class="fw-bold">Lama Kerja</td>
                        <td>:</td>
                        <td>{{$masaKerja}}</td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Pendapatan</h6>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-3 mb-5 pb-5">
                <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <tbody class="p-3">
                        <tr style="border:1px solid #e3e3e3">
                            <td>Gaji Pokok</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ number_format($data->gaji->gj_pokok , 0, ',', '.')}}</td>
                        </tr>
                        @if ($data->gaji->tj_jabatan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Jabatan</td>
                                <td id="gaji_pokok" style="text-align: right;">{{ number_format($data->gaji->tj_jabatan , 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->gj_penyesuaian)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Penyesuaian</td>
                                <td id="gaji_pokok" style="text-align: right;">{{ number_format($data->gaji->gj_penyesuaian , 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_keluarga)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Keluarga</td>
                                <td id="gaji_pokok" style="text-align: right;">{{ number_format($data->gaji->tj_keluarga, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_kemahalan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Kemahalan</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_kemahalan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_kesejahteraan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Kesejahteraan</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_kesejahteraan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_multilevel)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Multilevel</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_multilevel, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_pelaksana)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Pelaksana</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_pelaksana, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_perumahan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Perumahan</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_perumahan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_telepon)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Telepon</td>
                                <td style="text-align: right;">{{ number_format($data->gaji->tj_telepon, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        <tr>
                            <th width="60%">GAJI POKOK + PENGHASILAN TERATUR</th>
                            <th style="text-align: right;">{{ number_format($data->gaji->total_gaji , 0, ',', '.')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="pt-5 mt-5">
            <div class="content-header bg-utama p-2 mt-5 rounded">
                <h6 class="fw-bold text-center text-white">Potongan</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-3">
                <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <tbody class="p-2">
                        <tr style="border:1px solid #e3e3e3">
                            <td>JP BPJS TK 1%</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ number_format(intval($data->bpjs_tk), 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>DPP 5%</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ $data->potongan ? number_format($data->potongan->dpp, 0, ',', '.') : 0 }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT KOPERASI</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->kredit_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IUARAN KOPERASI	</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->iuran_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT PEGAWAI	</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->kredit_pegawai, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IURAN IK</td>
                            <td id="gaji_pokok" style="text-align: right;">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->iuran_ik, 0, ',', '.') : 0}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        <tr>
                            <th width="60%">TOTAL POTONGAN</th>
                            <th style="text-align: right;">{{ number_format($data->total_potongan, 0, ',', '.')  }}</th>
                        </tr>
                    </thead>
                </table>
                <hr>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        @php
                            $total_diterima = $data->gaji->total_gaji - $data->total_potongan;
                        @endphp
                        <tr>
                            <th width="60%">TOTAL YANG DITERIMA</th>
                            <th style="text-align: right;">{{ $total_diterima > 0 ? number_format($total_diterima, 0, ',', '.') : '-'}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br><br>

        <div class="p-4 my-5" style="display: flex; justify-content: space-around;">
            <div>
                <p class="p-0 mb-5">Mengetahui</p>
                <p class="p-0 m-0 fw-bold text-decoration-underline">SIGIT PURWANTO</p>
                <p class="p-0 m-0">Pemimpin Divisi Umum</p>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>

</html>
