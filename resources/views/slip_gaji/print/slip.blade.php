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
    @php
        $date = \Request::get('request_year').'-'.\Request::get('request_month');
        $mulaKerja = Carbon::create($data->tanggal_pengangkat);
        $waktuSekarang = Carbon::now();

        $hitung = $waktuSekarang->diff($mulaKerja);
        $masaKerja = $hitung->format('%y Tahun, %m Bulan');
    @endphp
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
            h6 {
                font-weight: 700;
            }
        /* ... the rest of the rules ... */
        }
    </style>
    <div class="p-2">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <div>
                    <img src="{{ asset('style/assets/img/logo.png') }}" width="100px" class="img-fluid">
                </div>
                <div class="pl-2">
                    <h4>SLIP GAJI PEGAWAI</h4>
                    <h6>Periode {{Carbon::parse($date)->translatedFormat('F Y')}}</h6>
                    <h6>Bank BPR Jatim</h6>
                </div>
            </div>
        </div>
        <hr>
        {{--  Data Karyawan  --}}
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td>{{$data->nip}}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{$data->nama_karyawan}}</td>
                            </tr>
                            <tr>
                                <td>No Rekening</td>
                                <td>:</td>
                                <td>{{$data->no_rekening}}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td>{{$data->status_jabatan}}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Bergabung</td>
                                <td>:</td>
                                <td>{{Carbon::parse($data->tanggal_pengangkat)->translatedFormat('d F Y')}}</td>
                            </tr>
                            <tr>
                                <td>Lama Kerja</td>
                                <td>:</td>
                                <td>{{$masaKerja}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        {{--  Pendapatan & Potongan  --}}
        <div class="row">
            <div class="col">
                <table class="table table-bordered m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <thead class="bg-utama text-white">
                        <tr>
                            <th class="text-center px-3">Pendapatan</th>
                            <th class="text-center px-3">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Gaji Pokok</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ number_format($data->gaji->gj_pokok , 0, ',', '.')}}</td>
                        </tr>
                        @if ($data->gaji->tj_jabatan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Jabatan</td>
                                <td id="gaji_pokok" class="text-right px-3">{{ number_format($data->gaji->tj_jabatan , 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->gj_penyesuaian)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Penyesuaian</td>
                                <td id="gaji_pokok" class="text-right px-3">{{ number_format($data->gaji->gj_penyesuaian , 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_keluarga)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Keluarga</td>
                                <td id="gaji_pokok" class="text-right px-3">{{ number_format($data->gaji->tj_keluarga, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_jabatan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Kemahalan</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_kemahalan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->gj_penyesuaian)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Kesejahteraan</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_kesejahteraan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_perumahan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Multilevel</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_multilevel, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_pelaksana)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Pelaksana</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_pelaksana, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_kemahalan)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Perumahan</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_perumahan, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                        @if ($data->gaji->tj_telepon)
                            <tr style="border:1px solid #e3e3e3">
                                <td>Telepon</td>
                                <td class="text-right px-3">{{ number_format($data->gaji->tj_telepon, 0, ',', '.')}}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="px-3">TOTAL (THP)</th>
                            <th class="text-right px-3">Rp {{ number_format($data->gaji->total_gaji , 0, ',', '.')}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col">
                <table class="table table-bordered m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <thead class="bg-utama text-white">
                        <tr>
                            <th class="text-center px-3">Potongan</th>
                            <th class="text-center px-3">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border:1px solid #e3e3e3">
                            <td>JP BPJS TK 1%</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ number_format(intval($data->bpjs_tk), 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>DPP 5%</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ $data->potongan ? number_format($data->potongan->dpp, 0, ',', '.') : 0 }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT KOPERASI</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->kredit_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IUARAN KOPERASI	</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->iuran_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT PEGAWAI	</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->kredit_pegawai, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IURAN IK</td>
                            <td id="gaji_pokok" class="text-right px-3">{{ $data->potongan_gaji ? number_format($data->potongan_gaji->iuran_ik, 0, ',', '.') : 0}}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="px-3">TOTAL POTONGAN</th>
                            <th class="text-right px-3">Rp {{ number_format($data->total_potongan, 0, ',', '.')  }}</th>
                        </tr>
                    </tfoot>
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