<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Template payroll!</title>
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
    <div class="p-5 mt-5 mb-5">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="header d-flex justify-content-between gap-5">
                    <img src="{{ asset('style/assets/img/logo.png') }}" width="150px" class="img-fluid">
                    <div class="content">
                        <h4 class="fw-bold p-3 ms-5"> PT HCS</h4>
                        <p class="text-start">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto facere ipsam quod laudantium voluptates commodi error aperiam libero, esse suscipit dicta deleniti consequatur eos, nostrum odit, nemo quas neque? Magnam.</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- <h4 class="fw-bold text-center mt-5 mb-5">SLIP GAJI KARYAWAN</h4> --}}
        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Data Karyawan</h6>
        </div>
        <div class="row">
            <div class="col-lg-4 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">NIP</td>
                        <td>:</td>
                        <td>{{ $data[0]->nip }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>:</td>
                        <td>{{ $data[0]->nama_karyawan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No Rekening</td>
                        <td>:</td>
                        <td>{{ $data[0]->no_rekening }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="content-header bg-utama p-2 rounded">
            <h6 class="fw-bold text-center text-white">Pendapatan</h6>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-3">
                <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <tbody>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Gaji Pokok</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->gaji->gj_pokok }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Jabatan</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->gaji->tj_jabatan }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Penyesuaian</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->gaji->gj_penyesuaian }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Keluarga</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->gaji->tj_keluarga}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Kemahalan</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_kemahalan}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Kesejahteraan</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_kesejahteraan}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Multilevel</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_multilevel}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Pelaksana</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_pelaksana}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Perumahan</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_perumahan}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Pulsa</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_pulsa}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Telepon</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_telepon}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Transport</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_transport}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>Vitamin</td>
                            <td class="text-end">{{ $data[0]->gaji->tj_vitamin}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        <tr>
                            <th width="60%">GAJI POKOK + PENGHASILAN TERATUR</th>
                            <th class="text-end">{{ $data[0]->gaji->total_gaji }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="content-header bg-utama p-2 mt-4 rounded">
            <h6 class="fw-bold text-center text-white">Potongan</h6>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-3">
                <table class="table table-borderless m-0" style="border:1px solid #e3e3e3" id="table-tunjangan">
                    <tbody>
                        <tr style="border:1px solid #e3e3e3">
                            <td>JP BPJS TK 1%</td>
                            <td id="gaji_pokok" class="text-end">{{ number_format($data[0]->potongan->jp_1_persen, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>DPP 5%</td>
                            <td id="gaji_pokok" class="text-end">{{ number_format($data[0]->potongan->dpp, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT KOPERASI</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->potongan_gaji ? number_format($data->potongan_gaji->kredit_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IUARAN KOPERASI	</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->potongan_gaji ? number_format($data->potongan_gaji->iuran_koperasi, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>KREDIT PEGAWAI	</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->potongan_gaji ? number_format($data->potongan_gaji->kredit_pegawai, 0, ',', '.') : 0}}</td>
                        </tr>
                        <tr style="border:1px solid #e3e3e3">
                            <td>IURAN IK</td>
                            <td id="gaji_pokok" class="text-end">{{ $data[0]->potongan_gaji ? number_format($data->potongan_gaji->iuran_ik, 0, ',', '.') : 0}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        <tr>
                            <th width="60%">TOTAL POTONGAN</th>
                            <th class="text-end">{{ $data[0]->total_potongan }}</th>
                        </tr>
                    </thead>
                </table>
                <hr>
                <table class="table table-borderless" id="table-tunjangan-total">
                    <thead>
                        <tr>
                            <th width="60%">TOTAL YANG DITERIMA</th>
                            <th class="text-end">{{ $data[0]->total_yg_diterima > 0 ? $data[0]->total_yg_diterima : '-'}}</th>
                        </tr>
                    </thead>
                </table>


            </div>
        </div>

        <br><br>

        <div class="row">
            <div class="col-lg-4">
                <table class="table table-borderless">
                    <tbody class="text-center">
                        <tr>
                            <td>Mengetahui</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Manajer Keuangan</p>
                                <p>Julio Critiano</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>








    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
