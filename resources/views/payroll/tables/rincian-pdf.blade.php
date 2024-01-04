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
        <h5 class="fw-bold text-center">KANTOR PUSAT</h5>
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
                        <th class="text-center">T. Listrik & Air</th>
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
                    @endphp
                    @forelse ($data as $key => $item)
                        @php
                            $gj_pokok = $item->gaji ? number_format($item->gaji->gj_pokok, 0, ',', '.') : 0;
                            $tj_keluarga = $item->gaji ? number_format($item->gaji->tj_keluarga, 0, ',', '.') : 0;
                            $tj_listrik = $item->gaji ? number_format($item->gaji->tj_telepon, 0, ',', '.') : 0;
                            $tj_jabatan = $item->gaji ? number_format($item->gaji->tj_jabatan, 0, ',', '.') : 0;
                            $tj_khusus = $item->gaji ? number_format($item->gaji->tj_ti, 0, ',', '.') : 0;
                            $tj_perumahan = $item->gaji ? number_format($item->gaji->tj_perumahan, 0, ',', '.') : 0;
                            $tj_pelaksana = $item->gaji ? number_format($item->gaji->tj_pelaksana, 0, ',', '.') : 0;
                            $tj_kemahalan = $item->gaji ? number_format($item->gaji->tj_kemahalan, 0, ',', '.') : 0;
                            $tj_kesejahteraan = $item->gaji ? number_format($item->gaji->tj_kesejahteraan, 0, ',', '.') : 0;
                            $gj_penyesuaian = $item->gaji ? number_format($item->gaji->gj_penyesuaian, 0, ',', '.') : 0;
                            $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                            $pph_harus_dibayar = 0;
                            if ($item->perhitungan_pph21) {
                                if ($item->perhitungan_pph21->pph_pasal_21) {
                                    if ($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar > 0) {
                                        $pph_harus_dibayar = number_format($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar, 0, ',', '.');
                                    }
                                }
                            }

                            // count total
                            $footer_gj_pokok += intval(str_replace('.','', $gj_pokok));
                            $footer_tj_keluarga += intval(str_replace('.','', $tj_keluarga));
                            $footer_tj_listrik += intval(str_replace('.','', $tj_listrik));
                            $footer_tj_jabatan += intval(str_replace('.','', $tj_jabatan));
                            $footer_tj_khusus += intval(str_replace('.','', $tj_khusus));
                            $footer_tj_perumahan += intval(str_replace('.','', $tj_perumahan));
                            $footer_tj_pelaksana += intval(str_replace('.','', $tj_pelaksana));
                            $footer_tj_kemahalan += intval(str_replace('.','', $tj_kemahalan));
                            $footer_tj_kesejahteraan += intval(str_replace('.','', $tj_kesejahteraan));
                            $footer_gj_penyesuaian += intval(str_replace('.','', $gj_penyesuaian));
                            $footer_total_gaji += intval(str_replace('.','', $total_gaji));
                            $footer_pph_harus_dibayar += intval(str_replace('.','', $pph_harus_dibayar));
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td class="text-end">{{ $gj_pokok > 0 ? $gj_pokok : '-' }}</td>
                            <td class="text-end">{{ $tj_keluarga > 0 ? $tj_keluarga : '-' }}</td>
                            <td class="text-end">{{ $tj_listrik > 0 ? $tj_listrik : '-' }}</td>
                            <td class="text-end">{{ $tj_jabatan > 0 ? $tj_jabatan : '-' }}</td>
                            <td class="text-end">{{ $tj_khusus > 0 ? $tj_khusus : '-' }}</td>
                            <td class="text-end">{{ $tj_perumahan > 0 ? $tj_perumahan : '-' }}</td>
                            <td class="text-end">{{ $tj_pelaksana > 0 ? $tj_pelaksana : '-' }}</td>
                            <td class="text-end">{{ $tj_kemahalan > 0 ? $tj_kemahalan : '-' }}</td>
                            <td class="text-end">{{ $tj_kesejahteraan > 0 ? $tj_kesejahteraan : '-' }}</td>
                            <td class="text-end">{{ $gj_penyesuaian > 0 ? $gj_penyesuaian : '-' }}</td>
                            <td class="text-end">{{ $total_gaji > 0 ? $total_gaji : '-' }}</td>
                            <td class="text-end">{{ $pph_harus_dibayar > 0 ? "($pph_harus_dibayar)" : '-' }}</td>
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
                        <th class="text-end">{{ number_format($footer_gj_pokok, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_keluarga, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_listrik, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_jabatan, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_khusus, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_perumahan, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_pelaksana, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_kemahalan, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_tj_kesejahteraan, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_gj_penyesuaian, 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
                        <th class="text-end">({{ number_format($footer_pph_harus_dibayar, 0, ',', '.') }})</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    <div class="p-4 my-5">
        <div class="d-flex justify-content-between">
            <div>
                <p class="p-0 mb-5">Mengetahui</p>
                <p class="p-0 m-0 fw-bold text-decoration-underline">SIGIT PURWANTO</p>
                <p class="p-0 m-0">Pemimpin Divisi Umum</p>
            </div>
            <div>
                <p class="p-0 m-0 mb-5">Surabaya,{{ date('d M Y', strtotime(now())) }}</p>
                <p class="p-0 m-0 fw-bold text-decoration-underline">DEANG PARUJAR S</p>
                <p class="p-0 m-0">Pemimpin Sub Divisi SDM</p>
            </div>
        </div>
    </div>
</body>
</html>

