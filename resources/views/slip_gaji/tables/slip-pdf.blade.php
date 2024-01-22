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
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-end my-3">
            <div class="mx-3">
                <a href="{{route('payroll.slip')}}" class="btn btn-primary btn-icon-text no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</a>
            </div>
        </div>
        <table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
            <thead style="border:1px solid #e3e3e3 !important">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama karyawan</th>
                    <th class="text-center">No Rek</th>
                    <th class="text-center">Gaji</th>
                    <th class="text-center">Total Potongan</th>
                    <th class="text-center">Gaji Bersih</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $footer_total_gaji = 0;
                    $footer_total_potongan = 0;
                    $footer_total_diterima = 0;
                    $footer_total_gaji_bersih = 0;
                @endphp
                @forelse ($data as $key => $item)
                    @php
                        $norek = $item->no_rekening ? $item->no_rekening : '-';
                        $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                        $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
                        $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
                        $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
                        $iuran_koperasi = $item->potonganGaji ? number_format($item->gaji->iuran_koperasi, 0, ',', '.') : 0;
                        $kredit_pegawai = $item->potonganGaji ? number_format($item->gaji->kredit_pegawai, 0, ',', '.') : 0;
                        $iuran_ik = $item->potonganGaji ? number_format($item->gaji->iuran_ik, 0, ',', '.') : 0;
                        $total_potongan = number_format($item->total_potongan, 0, ',', '.');
                        $total_diterima = $item->total_yg_diterima ? $item->total_yg_diterima : 0;

                        // count total
                        $footer_total_gaji += str_replace('.', '', $total_gaji);
                        $footer_total_potongan += str_replace('.', '', $total_potongan);
                        $footer_total_diterima += str_replace('.', '', $total_diterima);
                        $footer_total_gaji_bersih += str_replace('.', '', $total_diterima);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td class="text-center">{{ $norek }}</td>
                        <td class="text-end">{{ $total_gaji }}</td>
                        <td class="text-end">{{ $total_potongan }}</td>
                        <td class="text-end">{{ $total_diterima > 0 ? number_format($total_diterima, 0, ',', '.') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="12">Data tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-center">Jumlah</th>
                    <th class="text-end">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
                    <th class="text-end">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
                    <th class="text-end">{{ number_format($footer_total_gaji_bersih, 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
<script>
    print();
</script>
</html>
