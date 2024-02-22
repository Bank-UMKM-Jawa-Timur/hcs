<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%; border:1px solid #000000 !important">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
        <tr>
            <th rowspan="2" class="text-center">No</th>
            <th rowspan="2" class="text-center">Nama karyawan</th>
            <th rowspan="2" class="text-center">Gaji</th>
            <th rowspan="2" class="text-center">No Rek</th>
            <th colspan="6" class="text-center">Potongan</th>
            <th rowspan="2" class="text-center">Total Potongan</th>
            <th rowspan="2" class="text-center">Total Yang Diterima</th>
        </tr>
        <tr>
            <th class="text-center">JP BPJS TK 1%</th>
            <th class="text-center">DPP 5%</th>
            <th class="text-center">Kredit Koperasi</th>
            <th class="text-center">Iuran Koperasi</th>
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
                $total_gaji = $item->gaji ? round($item->gaji->total_gaji) : 0;
                $dpp = $item->gaji ? round($item->gaji->dpp) : 0;
                $bpjs_tk = $item->gaji ? round($item->gaji->bpjs_tk) : 0;
                $kredit_koperasi = $item->potonganGaji ? round($item->potonganGaji->kredit_koperasi) : 0;
                $iuran_koperasi = $item->gaji ? round($item->gaji->iuran_koperasi) : 0;
                $kredit_pegawai = $item->gaji ? round($item->gaji->kredit_pegawai) : 0;
                $iuran_ik = $item->gaji ? round($item->gaji->iuran_ik) : 0;
                $total_potongan = $item->total_potongan ? round($item->total_potongan) : 0;
                $total_diterima = $item->total_yg_diterima ? round($item->total_yg_diterima) : 0;

                $footer_total_gaji += $total_gaji;
                $footer_dpp += $dpp;
                $footer_bpjs_tk += $bpjs_tk;
                $footer_kredit_koperasi += $kredit_koperasi;
                $footer_iuran_koperasi += $iuran_koperasi;
                $footer_kredit_pegawai += $kredit_pegawai;
                $footer_iuran_ik += $iuran_ik;
                $footer_total_potongan += $total_potongan;
                $footer_total_diterima += $total_diterima;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_gaji) }}</td>
                <td class="text-center">{{ $norek }}</td>
                <td class="text-right">{{ formatRupiahExcel($bpjs_tk) }}</td>
                <td class="text-right">{{ formatRupiahExcel($dpp) }}</td>
                <td class="text-right">{{ formatRupiahExcel($kredit_koperasi) }}</td>
                <td class="text-right">{{ formatRupiahExcel($iuran_koperasi) }}</td>
                <td class="text-right">{{ formatRupiahExcel($kredit_pegawai) }}</td>
                <td class="text-right">{{ formatRupiahExcel($iuran_ik) }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_potongan) }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_diterima) }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="12">Data tidak tersedia.</td>
            </tr>
        @endforelse
        <tr>
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right">{{ formatRupiahExcel($footer_total_gaji) }}</td>
            <td class="text-center"></td>
            <td class="text-right">{{ formatRupiahExcel($footer_bpjs_tk) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_dpp) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_kredit_koperasi) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_iuran_koperasi) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_kredit_pegawai) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_iuran_ik) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_total_potongan) }}</td>
            <td class="text-right">{{ formatRupiahExcel($footer_total_diterima) }}</td>
        </tr>
    </tbody>
</table>
