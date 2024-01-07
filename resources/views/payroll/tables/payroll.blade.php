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
                $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
                $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
                $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
                $iuran_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->iuran_koperasi, 0, ',', '.') : 0;
                $kredit_pegawai = $item->potonganGaji ? number_format($item->potonganGaji->kredit_pegawai, 0, ',', '.') : 0;
                $iuran_ik = $item->potonganGaji ? number_format($item->potonganGaji->iuran_ik, 0, ',', '.') : 0;
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
                <td>{{ $number++ }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ $total_gaji }}</td>
                <td class="text-center">{{ $norek }}</td>
                <td class="text-right">{{ $bpjs_tk }}</td>
                <td class="text-right">{{ $dpp }}</td>
                <td class="text-right">{{ $kredit_koperasi }}</td>
                <td class="text-right">{{ $iuran_koperasi }}</td>
                <td class="text-right">{{ $kredit_pegawai }}</td>
                <td class="text-right">{{ $iuran_ik }}</td>
                <td class="text-right">{{ $total_potongan }}</td>
                <td class="text-right">{{ $total_diterima }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="12">Data tidak tersedia.</td>
            </tr>
        @endforelse
        <!-- Grand Total Calculation -->
        @php
            $grand_footer_total_gaji = 0;
            $grand_footer_bpjs_tk = 0;
            $grand_footer_dpp = 0;
            $grand_footer_kredit_koperasi = 0;
            $grand_footer_iuran_koperasi = 0;
            $grand_footer_kredit_pegawai = 0;
            $grand_footer_iuran_ik = 0;
            $grand_footer_total_potongan = 0;
            $grand_footer_total_diterima = 0;
        @endphp
        @foreach ($total as $item)
            @php
                $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
                $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
                $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
                $iuran_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->iuran_koperasi, 0, ',', '.') : 0;
                $kredit_pegawai = $item->potonganGaji ? number_format($item->potonganGaji->kredit_pegawai, 0, ',', '.') : 0;
                $iuran_ik = $item->potonganGaji ? number_format($item->potonganGaji->iuran_ik, 0, ',', '.') : 0;
                $total_potongan = number_format($item->total_potongan, 0, ',', '.');
                $total_diterima = $item->total_yg_diterima ? number_format($item->total_yg_diterima, 0, ',', '.') : 0;

                // count total
                $grand_footer_total_gaji += str_replace('.', '', $total_gaji);
                $grand_footer_bpjs_tk += str_replace('.', '', $bpjs_tk);
                $grand_footer_dpp += str_replace('.', '', $dpp);
                $grand_footer_kredit_koperasi += str_replace('.', '', $kredit_koperasi);
                $grand_footer_iuran_koperasi += str_replace('.', '', $iuran_koperasi);
                $grand_footer_kredit_pegawai += str_replace('.', '', $kredit_pegawai);
                $grand_footer_iuran_ik += str_replace('.', '', $iuran_ik);
                $grand_footer_total_potongan += str_replace('.', '', $total_potongan);
                $grand_footer_total_diterima += str_replace('.', '', $total_diterima);
            @endphp
        @endforeach
        @php
        $grand_total_gaji = number_format($grand_footer_total_gaji, 0, ',', '.');
        $grand_bpjs_tk = number_format($grand_footer_bpjs_tk, 0, ',', '.');
        $grand_dpp = number_format($grand_footer_dpp, 0, ',', '.');
        $grand_kredit_koperasi = number_format($grand_footer_kredit_koperasi, 0, ',', '.');
        $grand_iuran_koperasi = number_format($grand_footer_iuran_koperasi, 0, ',', '.');
        $grand_kredit_pegawai = number_format($grand_footer_kredit_pegawai, 0, ',', '.');
        $grand_iuran_ik = number_format($grand_footer_iuran_ik, 0, ',', '.');
        $grand_total_potongan = number_format($grand_footer_total_potongan, 0, ',', '.');
        $grand_total_diterima = number_format($grand_footer_total_diterima, 0, ',', '.');
        @endphp
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Grand Total</th>
            <th class="text-right">{{ $grand_total_gaji }}</th>
            <th></th>
            <th class="text-right">{{ $grand_bpjs_tk }}</th>
            <th class="text-right">{{ $grand_dpp }}</th>
            <th class="text-right">{{ $grand_kredit_koperasi }}</th>
            <th class="text-right">{{ $grand_iuran_koperasi }}</th>
            <th class="text-right">{{ $grand_kredit_pegawai }}</th>
            <th class="text-right">{{ $grand_iuran_ik }}</th>
            <th class="text-right">{{ $grand_total_potongan }}</th>
            <th class="text-right">{{ $grand_total_diterima }}</th>
        </tr>
    </tfoot>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Total</th>
            <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
            <th></th>
            <th class="text-right">{{ number_format($footer_bpjs_tk, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_dpp, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_kredit_koperasi, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_iuran_koperasi, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_kredit_pegawai, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_iuran_ik, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_diterima, 0, ',', '.') }}</th>
        </tr>
    </tfoot>

</table>

