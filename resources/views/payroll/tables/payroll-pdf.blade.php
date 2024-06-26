<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- fontawesome  -->
    <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
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
        thead th{
            font-size: 8pt;
            display:flex,
            align-items:center,
        }
        @page {
            margin: 0;  /* Ini akan diterapkan ke setiap halaman */
            size: landscape;
        }

        @page :first {
            margin-top: 10mm;  /* Hanya diterapkan ke halaman pertama */
        }
        @media print {
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
</head>
<body>
</body>
@php
    $bulan = [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
@endphp
    <div class="container">
        <h5 class="fw-bold text-center">RINCIAN GAJI PEGAWAI</h5>
        <h5 class="fw-bold text-center">{{strtoupper($kantor)}}</h5>
        <h5 class="fw-bold text-center">BANK BPR JATIM BANK UMKM JAWA TIMUR</h5>
        <h6 class="fw-bold text-center">{{ $bulan[Session::get('month')] }} {{ Session::get('year') }}</h6>
        <center style="font-size: 10px; text-align: center" class="text-center">( MASUK TAB. SIKEMAS )</center>
        <div class="d-flex justify-content-end mb-3">
            <div class="mx-3">
                <a href="{{route('payroll.index')}}" class="btn btn-primary btn-icon-text no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</a>
            </div>
        </div>
    </div>
    <div class="card" style="border: none">
        <div class="card-body">
            <table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
                <thead style="border:1px solid #e3e3e3 !important">
                    <tr>
                        <th rowspan="2"  style="vertical-align:middle" class="text-center">No</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle">Nama karyawan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle">Gaji</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle">No Rek</th>
                        <th colspan="6" class="text-center"  style="vertical-align:middle">Potongan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle">Total Potongan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle">Total Yang Diterima</th>
                    </tr>
                    <tr>
                        <th class="text-center">JP BPJS TK 1%</th>
                        <th class="text-center">DPP 5%</th>
                        <th class="text-center">Kredit Koperasi</th>
                        <th class="text-center">Iuaran Koperasi</th>
                        <th class="text-center">Kredit Pegawai</th>
                        <th class="text-center">Iuran IK</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $footer_total_gaji = 0;
                        $footer_bpjs_tk = 0;
                        $footer_dpp = 0;
                        $footer_kredit_koperasi = 0;
                        $footer_iuran_koperasi = 0;
                        $footer_kredit_pegawai = 0;
                        $footer_iuran_ik = 0;
                        $footer_total_potongan = 0;
                        $footer_total_diterima = 0;
                    @endphp
                    @forelse ($data as $key => $item)
                        @php
                            $norek = $item->no_rekening ? $item->no_rekening : '-';
                            $total_gaji = $item->gaji ? $item->gaji->total_gaji : 0;
                            $dpp = $item->potongan ? $item->potongan->dpp : 0;
                            $bpjs_tk = $item->bpjs_tk ? $item->bpjs_tk : 0;
                            $kredit_koperasi = $item->potonganGaji ? $item->potonganGaji->kredit_koperasi : 0;
                            $iuran_koperasi = $item->potonganGaji ? $item->gaji->iuran_koperasi : 0;
                            $kredit_pegawai = $item->potonganGaji ? $item->gaji->kredit_pegawai : 0;
                            $iuran_ik = $item->potonganGaji ? $item->gaji->iuran_ik : 0;
                            $total_potongan = $item->total_potongan ? $item->total_potongan : 0;
                            $total_diterima = $item->total_yg_diterima ? $item->total_yg_diterima : 0;

                            // count total
                            $footer_total_gaji += str_replace('.', '', $total_gaji);
                            $footer_bpjs_tk += str_replace('.', '', $bpjs_tk);
                            $footer_dpp += str_replace('.', '', $dpp);
                            $footer_kredit_koperasi += str_replace('.', '', $kredit_koperasi);
                            $footer_iuran_koperasi += str_replace('.', '', $iuran_koperasi);
                            $footer_kredit_pegawai += str_replace('.', '', $kredit_pegawai);
                            $footer_iuran_ik += str_replace('.', '', $iuran_ik);
                            $footer_total_potongan += str_replace('.', '', $total_potongan);
                            $footer_total_diterima += str_replace('.', '', $total_diterima);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td class="text-end">{{ formatRupiahExcel($total_gaji, 0, true) }}</td>
                            <td class="text-center">{{ $norek }}</td>
                            <td class="text-end">{{ formatRupiahExcel($bpjs_tk, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($dpp, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($kredit_koperasi, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($iuran_koperasi, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($kredit_pegawai, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($iuran_ik, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($total_potongan, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($total_diterima, 0, true) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="12">Data tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">Total</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_total_gaji, 0, true) }}</th>
                        <th></th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_bpjs_tk, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_dpp, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_kredit_koperasi, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_iuran_koperasi, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_kredit_pegawai, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_iuran_ik, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_total_potongan, 0, true) }}</th>
                        <th class="text-end" align="right">{{ formatRupiahExcel($footer_total_diterima, 0, true) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="px-4 container-fluid mt-4">
        <div class="d-flex justify-content-between mx-4 px-4">
            @if (!auth()->user()->hasRole('cabang'))
                <div>
                    <p class="p-0 mb-5">Mengetahui</p>
                    <p class="p-0 m-0 fw-bold text-decoration-underline">{{ $ttdKaryawan[0]->nama_karyawan }}</p>
                    <p class="p-0 m-0">{{ $ttdKaryawan[0]->jabatan->nama_jabatan.' '.$ttdKaryawan[0]->entitas_result }}</p>
                </div>
                <div>
                    <p class="p-0 m-0 mb-5">Surabaya,{{ date('d F Y', strtotime($data[0]->tanggal_input)) }}</p>
                    <p class="p-0 m-0 fw-bold text-decoration-underline">{{ $ttdKaryawan[1]->nama_karyawan }}</p>
                    <p class="p-0 m-0">{{ $ttdKaryawan[1]->jabatan->nama_jabatan.' '.$ttdKaryawan[1]->entitas_result }}</p>
                </div>
            @endif
            @if (auth()->user()->hasRole('cabang'))
                <div></div>
                <div>
                    <p class="p-0 m-0 mb-5">{{$cabang}}, {{ date('d F Y', strtotime($data[0]->tanggal_input)) }}</p>
                    <p class="p-0 m-0 fw-bold text-decoration-underline">{{ $pincab->nama_karyawan }}</p>
                    <p class="p-0 m-0">Pimpinan Cabang {{$cabang}}</p>
                </div>
            @endif
        </div>
    </div>
</body>
<script>
    print();
</script>
</html>


