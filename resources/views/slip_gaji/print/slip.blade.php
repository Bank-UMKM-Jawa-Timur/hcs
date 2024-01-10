<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji</title>
</head>

<body>
    @php
        $date = \Request::get('tahun') . '-' . \Request::get('bulan');
        $mulaKerja = Carbon::create($data->tanggal_pengangkat);
        $waktuSekarang = Carbon::now();

        $hitung = $waktuSekarang->diff($mulaKerja);
        $masaKerja = $hitung->format('%y Tahun, %m Bulan');


        $nilai = 0;

        function penyebut($nilai)
        {
            $nilai = abs($nilai);
            $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            $temp = '';
            if ($nilai < 12) {
                $temp = ' ' . $huruf[$nilai];
            } elseif ($nilai < 20) {
                $temp = penyebut($nilai - 10) . ' belas';
            } elseif ($nilai < 100) {
                $temp = penyebut($nilai / 10) . ' puluh' . penyebut($nilai % 10);
            } elseif ($nilai < 200) {
                $temp = ' seratus' . penyebut($nilai - 100);
            } elseif ($nilai < 1000) {
                $temp = penyebut($nilai / 100) . ' ratus' . penyebut($nilai % 100);
            } elseif ($nilai < 2000) {
                $temp = ' seribu' . penyebut($nilai - 1000);
            } elseif ($nilai < 1000000) {
                $temp = penyebut($nilai / 1000) . ' ribu' . penyebut($nilai % 1000);
            } elseif ($nilai < 1000000000) {
                $temp = penyebut($nilai / 1000000) . ' juta' . penyebut($nilai % 1000000);
            } elseif ($nilai < 1000000000000) {
                $temp = penyebut($nilai / 1000000000) . ' milyar' . penyebut(fmod($nilai, 1000000000));
            } elseif ($nilai < 1000000000000000) {
                $temp = penyebut($nilai / 1000000000000) . ' trilyun' . penyebut(fmod($nilai, 1000000000000));
            }
            return $temp;
        }

        function terbilang($nilai)
        {
            if ($nilai < 0) {
                $hasil = 'minus ' . trim(penyebut($nilai));
            } else {
                $hasil = trim(penyebut($nilai));
            }
            return $hasil;
        }
    @endphp
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Tinos', serif;
            font: 12pt;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        p,
        table,
        ol {
            font-size: 9pt;
        }

        .flex-container {
            display: flex;
            flex-direction: row;
        }


        .text-utama {
            color: #DA251D;
        }

        .bg-utama {
            background-color: #DA251D !important;
        }
        .text-white {
            color: #ffffff;
        }

        .table{
            border:1px solid #e3e3e3;
        }
        .table tr{
            border:1px solid #e3e3e3;
        }

        .table th {
            padding: 8px !important;
            border: 1px solid #ddd;
        }

        .table td{
            padding: 5px !important;
            border-bottom: 1px solid #ddd;
        }

        .flex {
            display: flex;
            flex-direction: column;
        }
        .flex-row {
            display: flex;
            flex-direction: row;
        }

        h6 {
            font-weight: 700;
        }
    </style>
    <div style="padding: 20px">
        <div class="flex">
            <div class="flex-row">
                <div>
                    <img src="{{ asset('style/assets/img/logo.png') }}" width="100px" class="img-fluid">
                </div>
                <div style="padding-left: 20px; padding-top: 10px;">
                    <table>
                        <tr>
                            <td style="font-weight: 800; padding-top: 5px">SLIP GAJI PEGAWAI</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800; padding-top: 5px">Periode {{ Carbon::parse($date)->translatedFormat('Y F') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800; padding-top: 5px">Bank BPR Jatim</td>
                        </tr>
                    </table>
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
                                <td>{{ $data->nip }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $data->nama_karyawan }}</td>
                            </tr>
                            <tr>
                                <td>No Rekening</td>
                                <td>:</td>
                                <td>{{ $data->no_rekening }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td>{{ $jabatan[0]->display_jabatan }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Bergabung</td>
                                <td>:</td>
                                <td>{{ Carbon::parse($data->tanggal_pengangkat)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td>Lama Kerja</td>
                                <td>:</td>
                                <td>{{ $masaKerja }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td style="vertical-align: top;">
                    <table border="1" class="table" style="width: 100%; margin-right: 1rem;">
                        <thead class="bg-utama text-white">
                            <tr>
                                <th class="text-center px-3">Pendapatan</th>
                                <th class="text-center px-3">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border:1px solid #e3e3e3">
                                <td>Gaji Pokok</td>
                                <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->gj_pokok, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if ($data->gaji->tj_keluarga > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Keluarga</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->tj_keluarga, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_jabatan > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Jabatan</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->tj_jabatan, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->gj_penyesuaian > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Gaji Penyesuaian</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->gj_penyesuaian, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_perumahan > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Perumahan</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->tj_perumahan, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_telepon > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Telepon, Listrik dan Air</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->tj_telepon, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_pelaksana > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Pelaksana</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->tj_pelaksana, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_kemahalan > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Kemahalan</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->tj_kemahalan, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->gaji->tj_kesejahteraan > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Kesejahteraan</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->tj_kesejahteraan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @php
                                $tj_khusus = 0;
                                if ($data->gaji->tj_ti > 0) {
                                    $tj_khusus += $data->gaji->tj_ti;
                                }
                                if ($data->gaji->tj_multilevel > 0) {
                                    $tj_khusus += $data->gaji->tj_multilevel;
                                }
                                if ($data->gaji->tj_fungsional > 0) {
                                    $tj_khusus += $data->gaji->tj_fungsional;
                                }
                            @endphp
                            @if ($tj_khusus > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>Tj. Khusus</td>
                                    <td style="padding-x: 3rem; text-align: right;">Rp {{ number_format($tj_khusus, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="px-3">TOTAL PENDAPATAN</th>
                                <th style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->gaji->total_gaji, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table border="1" class="table" style="width: 100%; margin-left: 1rem;">
                        <thead class="bg-utama text-white">
                            <tr>
                                <th class="text-center px-3">Potongan</th>
                                <th class="text-center px-3">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->bpjs_tk > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>JP BPJS TK 1%</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format(intval($data->bpjs_tk), 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($data->potongan)
                                @if ($data->potongan->dpp > 0)
                                    <tr style="border:1px solid #e3e3e3">
                                        <td>DPP 5%</td>
                                        <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                            Rp {{ number_format($data->potongan->dpp, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endif
                            @if ($data->gaji->kredit_koperasi > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>KREDIT KOPERASI</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->kredit_koperasi, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($data->gaji->iuran_koperasi > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>IUARAN KOPERASI </td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->iuran_koperasi, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($data->gaji->kredit_pegawai > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>KREDIT PEGAWAI </td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->kredit_pegawai, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($data->gaji->iuran_ik > 0)
                                <tr style="border:1px solid #e3e3e3">
                                    <td>IURAN IK</td>
                                    <td id="gaji_pokok" style="padding-x: 3rem; text-align: right;">
                                        Rp {{ number_format($data->gaji->iuran_ik, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="padding-x:3rem; ">TOTAL POTONGAN</th>
                                <th style="padding-x: 3rem; text-align: right;">Rp {{ number_format($data->total_potongan, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </table>

        <br><br>
        <div class="total-gaji">
            <table border="1" class="table" style="width: 100%">
                <thead class="bg-utama text-white">
                    <tr>
                        <th class="text-left px-3" colspan="2">TOTAL GAJI YANG DITERIMA <i>(TAKE HOME PAY)</i></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $takehomepay = $data->gaji->total_gaji - $data->total_potongan;
                    @endphp
                    <tr>
                        <td>
                            JUMLAH
                        </td>
                        <td style="padding-x: 3rem; text-align: right; font-weight: bold">Rp {{ number_format($takehomepay, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>
                            TERBILANG
                        </td>
                        <td style="text-align: left; font-weight: bold">
                            {{strtoupper(terbilang($takehomepay))}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>*) Dicetak dengan <b>{{env('APP_NAME')}}</b></p>

        @if (auth()->user()->hasRole('kepegawaian'))
            <div class="ttd" style="display: flex; justify-content: space-between; margin-top: 60px; margin-right: 40px;">
                <table>
                </table>
                <table>
                    <tr>
                        <td style="text-align: center; padding-bottom: 3px;" >Surabaya, {{$tanggal}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-bottom: 60px;" >{{$ttdKaryawan->display_jabatan}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: 800">({{ $ttdKaryawan->nama_karyawan }})</td>
                    </tr>
                </table>
            </div>
        @endif
        @if (auth()->user()->hasRole('cabang'))
            <div class="ttd" style="display: flex; justify-content: space-between; margin-top: 60px; margin-right: 40px;">
                <table>
                </table>
                <table>
                    <tr>
                        <td style="text-align: center; padding-bottom: 3px;" >{{$cabang->nama_cabang}}, {{$tanggal}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-bottom: 60px;" >Pimpinan Cabang</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: 800">({{ $pincab->nama_karyawan }})</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

    <script>

    </script>
</body>

<script>
    window.print();
</script>
</html>
