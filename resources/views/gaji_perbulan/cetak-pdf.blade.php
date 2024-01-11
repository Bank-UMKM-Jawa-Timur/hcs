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
    {{-- <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"> --}}
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
        table thead {
            font-size: 8pt;
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
                font-size: 10pt;
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
        <h5 class="fw-bold text-center">KANTOR PUSAT</h5>
        <h5 class="fw-bold text-center">BANK BPR JATIM BANK UMKM JAWA TIMUR</h5>
        <h6 class="fw-bold text-center">{{ $bulan[$month] }} {{ $year }}</h6>
        <center style="font-size: 10px; text-align: center" class="text-center">( MASUK TAB. SIKEMAS )</center>
        <div class="d-flex justify-content-end mb-1">
            <div class="mx-3">
                <a href="{{route('gaji_perbulan.index')}}" class="btn btn-primary btn-icon-text no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</a>
            </div>
        </div>
    </div>
    <div class="card" style="border: none">
        <div class="card-body">
            <table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
                <thead style="border:1px solid #e3e3e3 !important">
                    <tr>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle"  align="center">No</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle" >Nama karyawan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle" >Gaji</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle" >No Rek</th>
                        <th colspan="6" class="text-center"  style="vertical-align:middle" >Potongan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle" >Total Potongan</th>
                        <th rowspan="2" class="text-center"  style="vertical-align:middle" >Total Yang Diterima</th>
                    </tr>
                    <tr>
                        <th class="text-center">DPP 5%</th>
                        <th class="text-center">JP BPJS TK 1%</th>
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
                            $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                            $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
                            $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
                            $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
                            $iuran_koperasi = $item->potonganGaji ? number_format($item->gaji->iuran_koperasi, 0, ',', '.') : 0;
                            $kredit_pegawai = $item->potonganGaji ? number_format($item->gaji->kredit_pegawai, 0, ',', '.') : 0;
                            $iuran_ik = $item->potonganGaji ? number_format($item->gaji->iuran_ik, 0, ',', '.') : 0;
                            $total_potongan = number_format($item->total_potongan, 0, ',', '.');
                            $total_diterima = $item->total_yg_diterima ? number_format($item->total_yg_diterima, 0, ',', '.') : 0;

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
                            <td class="text-end">{{ $total_gaji }}</td>
                            <td class="text-center">{{ $norek }}</td>
                            <td class="text-end">{{ $dpp }}</td>
                            <td class="text-end">{{ $bpjs_tk }}</td>
                            <td class="text-end">{{ $kredit_koperasi }}</td>
                            <td class="text-end">{{ $iuran_koperasi }}</td>
                            <td class="text-end">{{ $kredit_pegawai }}</td>
                            <td class="text-end">{{ $iuran_ik }}</td>
                            <td class="text-end">{{ $total_potongan }}</td>
                            <td class="text-end">{{ $total_diterima }}</td>
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
                        <th class="text-end" align="right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
                        <th></th>
                        <th class="text-end" align="right">{{ number_format($footer_dpp, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_bpjs_tk, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_kredit_koperasi, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_iuran_koperasi, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_kredit_pegawai, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_iuran_ik, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
                        <th class="text-end" align="right">{{ number_format($footer_total_diterima, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="p-4">
        <div class="d-flex justify-content-between">
            @if (!auth()->user()->hasRole('cabang'))
            <div>
                <p class="p-0 mb-5">Mengetahui</p>
                <p class="p-0 m-0 fw-bold text-decoration-underline">SIGIT PURWANTO</p>
                <p class="p-0 m-0">Pemimpin Divisi Umum</p>
            </div>
            @endif
            @if (auth()->user()->hasRole('cabang'))
                @foreach ($ttdKaryawan as $item)
                    @if ($item->kd_jabatan == 'PBO' || $item->kd_jabatan == 'PC')
                        <div>
                            <p class="p-0 m-0 mb-5">{{ $cabang->nama_cabang }},{{ date('d M Y', strtotime($tanggal)) }}</p>
                            <p class="p-0 m-0 fw-bold text-decoration-underline">{{ $item->nama_karyawan }}</p>
                            <p class="p-0 m-0">{{ $item->jabatan_result }}</p>
                        </div>
                    @elseif ($item->kd_bagian == 'B-UMAK')
                        <div>
                            <p class="p-0 m-0 mb-5">{{ $cabang->nama_cabang }},{{ date('d M Y', strtotime($tanggal)) }}</p>
                            <p class="p-0 m-0 fw-bold text-decoration-underline">{{ $item->nama_karyawan }}</p>
                            <p class="p-0 m-0">{{ $item->jabatan_result.' '.$item->bagian->nama_bagian }}</p>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
<script src="{{asset('vendor/printpage/printpage.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var currentPageUrl = window.location.href;
    var url_arr = currentPageUrl.split('/')
    var id = url_arr[(url_arr.length - 1)]
    window.addEventListener('afterprint', function () {
        // After print, refresh the page
        console.log('after print')
        //window.location.href = currentPageUrl;
        updateTglCetak(id)
    });

    function updateTglCetak(id) {
        $.ajax({
            url: `{{url('update-tanggal-cetak')}}/${id}`,
            method: "GET",
            success: function(response) {
                console.log(response)
                window.close()
                $().closePrint()
            },
            error: function(e) {
                console.log(e)
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan!',
                    text: e
                });
            }
        })
    }
</script>
</html>


