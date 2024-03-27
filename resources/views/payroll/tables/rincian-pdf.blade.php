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
<body onload="window.print()">
@php
    $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
@endphp
    <div class="container">
        <h5 class="fw-bold text-center">RINCIAN GAJI PEGAWAI</h5>
        <h5 class="fw-bold text-center">{{strtoupper($kantor)}}</h5>
        <h5 class="fw-bold text-center">BANK BPR JATIM BANK UMKM JAWA TIMUR</h5>
        <h6 class="fw-bold text-center">{{ $bulan[Session::get('month')] }} {{ Session::get('year') }}</h6>
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
                        <th class="text-center">No</th>
                        <th>Nama karyawan</th>
                        <th class="text-center">Gaji Pokok</th>
                        <th class="text-center">T. Keluarga</th>
                        <th class="text-center">T. Telepon, Listrik & Air</th>
                        <th class="text-center">T. Jabatan</th>
                        <th class="text-center">T. Khusus</th>
                        <th class="text-center">T. Perumahan</th>
                        <th class="text-center">T. Pelaksana</th>
                        <th class="text-center">T. Kemahalan</th>
                        <th class="text-center">T. Kesejahteraan</th>
                        <th class="text-center">Penyesuaian</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">PPH 21</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $footer_gj_pokok = 0;
                        $footer_tj_keluarga = 0;
                        $footer_tj_listrik = 0;
                        $footer_tj_jabatan = 0;
                        $footer_tj_khusus = 0;
                        $footer_tj_perumahan = 0;
                        $footer_tj_pelaksana = 0;
                        $footer_tj_kemahalan = 0;
                        $footer_tj_kesejahteraan = 0;
                        $footer_gj_penyesuaian = 0;
                        $footer_total_gaji = 0;
                        $footer_pph_harus_dibayar = 0;
                        $footer_pajak_insentif = 0;
                    @endphp
                    @forelse ($data as $key => $item)
                        @php
                            $gj_pokok = $item->gaji ? $item->gaji->gj_pokok : 0;
                            $tj_keluarga = $item->gaji ? $item->gaji->tj_keluarga : 0;
                            $tj_listrik = $item->gaji ? $item->gaji->tj_telepon : 0;
                            $tj_jabatan = $item->gaji ? $item->gaji->tj_jabatan : 0;
                            $tj_khusus = $item->gaji ? $item->gaji->tj_ti : 0;
                            $tj_perumahan = $item->gaji ? $item->gaji->tj_perumahan : 0;
                            $tj_pelaksana = $item->gaji ? $item->gaji->tj_pelaksana : 0;
                            $tj_kemahalan = $item->gaji ? $item->gaji->tj_kemahalan : 0;
                            $tj_kesejahteraan = $item->gaji ? $item->gaji->tj_kesejahteraan : 0;
                            $gj_penyesuaian = $item->gaji ? $item->gaji->gj_penyesuaian : 0;
                            $total_gaji = $item->gaji ? $item->gaji->total_gaji : 0;
                            $pph_harus_dibayar = $item->pph_dilunasi_bulan_ini;
                            $pajak_insentif = $item->insentif->total_pajak_insentif;

                            // count total
                            $footer_gj_pokok += $gj_pokok;
                            $footer_tj_keluarga += $tj_keluarga;
                            $footer_tj_listrik += $tj_listrik;
                            $footer_tj_jabatan += $tj_jabatan;
                            $footer_tj_khusus += $tj_khusus;
                            $footer_tj_perumahan += $tj_perumahan;
                            $footer_tj_pelaksana += $tj_pelaksana;
                            $footer_tj_kemahalan += $tj_kemahalan;
                            $footer_tj_kesejahteraan += $tj_kesejahteraan;
                            $footer_gj_penyesuaian += $gj_penyesuaian;
                            $footer_total_gaji += $total_gaji;
                            $footer_pph_harus_dibayar += $pph_harus_dibayar;
                            $footer_pajak_insentif += $pajak_insentif;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td class="text-end">{{ formatRupiahExcel($gj_pokok, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_keluarga, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_listrik, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_jabatan, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_khusus, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_perumahan, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_pelaksana, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_kemahalan, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($tj_kesejahteraan, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($gj_penyesuaian, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($total_gaji, 0, true) }}</td>
                            <td class="text-end">{{ formatRupiahExcel($pph_harus_dibayar, 0, true) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="14">Data tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">Jumlah</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_gj_pokok, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_keluarga, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_listrik, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_jabatan, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_khusus, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_perumahan, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_pelaksana, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_kemahalan, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_tj_kesejahteraan, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_gj_penyesuaian, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_total_gaji, 0, true) }}</th>
                        <th class="text-end">{{ formatRupiahExcel($footer_pph_harus_dibayar, 0, true) }}</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    <div class="p-4 my-5 container-fluid">
        <div class="d-flex justify-content-between mx-4 px-4">
            @if (auth()->user()->hasRole('kepegawaian'))
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
</html>

