<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
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
            <th class="text-center">Iuaran Koperasi</th>
            <th class="text-center">Kredit Pegawai</th>
            <th class="text-center">Iuran IK</th>
        </tr>
    </thead>
    <tbody>
        @php
            $number = $page == 1 ? 1 : $start;
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
                $footer_total_gaji += $total_gaji;
                $footer_bpjs_tk += $bpjs_tk;
                $footer_dpp += $dpp;
                $footer_kredit_koperasi += $kredit_koperasi;
                $footer_iuran_koperasi += $iuran_koperasi;
                $footer_kredit_pegawai += $kredit_pegawai;
                $footer_iuran_ik += $iuran_ik;
                $footer_total_potongan += $total_potongan;
                $footer_total_diterima += $total_diterima;
            @endphp
            <tr>
                <td>{{ $number++ }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_gaji, 0, true) }}</td>
                <td class="text-center">{{ $norek }}</td>
                <td class="text-right">{{ formatRupiahExcel($bpjs_tk, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($dpp, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($kredit_koperasi, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($iuran_koperasi, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($kredit_pegawai, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($iuran_ik, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_potongan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_diterima, 0, true) }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="12">Data tidak tersedia.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Grand Total</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_total_gaji'], 0, true) }}</th>
            <th></th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_bpjs_tk'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_dpp'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_kredit_koperasi'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_iuran_koperasi'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_kredit_pegawai'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_iuran_ik'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_total_potongan'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['grand_total_diterima'], 0, true) }}</th>
        </tr>
    </tfoot>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Total</th>
            <th class="text-right">{{ formatRupiahExcel($footer_total_gaji, 0, true) }}</th>
            <th></th>
            <th class="text-right">{{ formatRupiahExcel($footer_bpjs_tk, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_dpp, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_kredit_koperasi, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_iuran_koperasi, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_kredit_pegawai, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_iuran_ik, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_total_potongan, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_total_diterima, 0, true) }}</th>
        </tr>
    </tfoot>

</table>

