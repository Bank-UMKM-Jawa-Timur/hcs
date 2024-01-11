@php
    $namaBulan = [
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
<table>
    <thead>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b>RINCIAN PENGGANTI UANG VITAMIN PEGAWAI</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b>{{auth()->user()->hasRole('cabang') ? 'KANTOR ' . strtoupper($cabang->nama_cabang) : 'KANTOR PUSAT' }} </b></th>
        </tr>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b>BANK BPR JATIM BANK UMKM JAWA TIMUR</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b>{{ $namaBulan[$bulan] }} {{ $tahun }}</b>
            </th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th rowspan="2" align="center" style="vertical-align: center; width:50px;">NO</th>
            <th rowspan="2" align="center" style="vertical-align: center; width:250px;">NAMA</th>
            <th rowspan="2" align="center" style="vertical-align: center; width:200px;">No REK.</th>
            <th rowspan="2" align="center" style="vertical-align: center; width:200px;">VITAMIN</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @php
            $jumlah = 0
        @endphp
        @foreach ($data as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td align="center">{{ $item->no_rekening ? $item->no_rekening : '-' }}</td>
                <td align="right">{{ $item->vitamin ? number_format($item->vitamin, 0, ',', ',') : '0' }}</td>
            </tr>
        @php
            $jumlah += $item->vitamin;
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="2" style="font-weight: bold ">JUMLAH</td>
            <td></td>
            <td align="right" style="font-weight: bold;">{{ number_format($jumlah, 0, ',', ',') }}</td>
        </tr>
    </tfoot>
</table>

<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>

<table>
    <tbody>
        <tr>
            <td></td>
            @if (auth()->user()->hasRole('cabang'))
                <td>
                    <table>
                    </table>
                </td>
                <td>
                    <table>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td align="center">{{ $cabang->nama_cabang }}, {{ now()->format('d F Y') }}</td>
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
                            <td align="center" style="font-weight:bold; font-size: 12px">{{ $pincab->nama_karyawan }}</td>
                        </tr>
                        <tr>
                            <td align="center">Pimpinan Cabang {{$cabang->nama_cabang}}</td>
                        </tr>
                    </table>
                </td>
            @else
                <td>
                    <table>
                        <tr>
                            <td align="center">Mengetahui</td>
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
                            <td align="center" style="font-weight:bold; font-size: 12px">{{ $ttdKaryawan[0]->nama_karyawan }}</td>
                        </tr>
                        <tr>
                            <td align="center">{{ $ttdKaryawan[0]->jabatan->nama_jabatan.' '.$ttdKaryawan[0]->entitas_result }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table></table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td align="center">Surabaya, {{ now()->format('d F Y') }}</td>
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
                            <td align="center" style="font-weight:bold; font-size: 12px">{{ $ttdKaryawan[1]->nama_karyawan }}</td>
                        </tr>
                        <tr>
                            <td align="center">{{ $ttdKaryawan[1]->jabatan->nama_jabatan.' '.$ttdKaryawan[1]->entitas_result }}</td>
                        </tr>
                    </table>
                </td>
            @endif
        </tr>
    </tbody>
</table>
