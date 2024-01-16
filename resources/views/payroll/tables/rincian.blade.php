<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
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
            $number = $page == 1 ? 1 : $start;
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
                $gj_pokok = $item->gaji ? $item->gaji->gj_pokok : 0;
                $tj_keluarga = $item->gaji ? $item->gaji->tj_keluarga : 0;
                $tj_listrik = $item->gaji ? $item->gaji->tj_telepon : 0;
                $tj_jabatan = $item->gaji ? $item->gaji->tj_jabatan : 0;
                $tj_khusus = 0;
                if ($item->gaji->tj_multilevel) {
                    $tj_khusus += $item->gaji->tj_multilevel;
                }
                if ($item->gaji->tj_ti) {
                    $tj_khusus += $item->gaji->tj_ti;
                }
                if ($item->gaji->tj_fungsional) {
                    $tj_khusus += $item->gaji->tj_fungsional;
                }
                $tj_khusus = $item->gaji ? $tj_khusus : 0;
                $tj_perumahan = $item->gaji ? $item->gaji->tj_perumahan : 0;
                $tj_pelaksana = $item->gaji ? $item->gaji->tj_pelaksana : 0;
                $tj_kemahalan = $item->gaji ? $item->gaji->tj_kemahalan : 0;
                $tj_kesejahteraan = $item->gaji ? $item->gaji->tj_kesejahteraan : 0;
                $gj_penyesuaian = $item->gaji ? $item->gaji->gj_penyesuaian : 0;
                $total_gaji = $item->gaji ? $item->gaji->total_gaji : 0;
                $pph_harus_dibayar = 0;
                if ($item->perhitungan_pph21) {
                    if ($item->perhitungan_pph21->pph21_pp58) {
                        $pph_harus_dibayar = $item->perhitungan_pph21->pph21_pp58;
                    }
                }

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
            @endphp
            <tr>
                <td class="text-center">{{ $number++ }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ formatRupiahExcel($gj_pokok, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_keluarga, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_listrik, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_jabatan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_khusus, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_perumahan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_pelaksana, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_kemahalan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($tj_kesejahteraan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($gj_penyesuaian, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($total_gaji, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($pph_harus_dibayar, 0, true) }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="14">Data tidak tersedia.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Total</th>
            <th class="text-right">{{ formatRupiahExcel($footer_gj_pokok, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_keluarga, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_listrik, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_jabatan, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_khusus, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_perumahan, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_pelaksana, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_kemahalan, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_tj_kesejahteraan, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_gj_penyesuaian, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_total_gaji, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($footer_pph_harus_dibayar, 0, true) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-center">Grand Total</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_gj_pokok'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_keluarga'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_telepon'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_jabatan'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_khusus'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_perumahan'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_pelaksana'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_kemahalan'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_tj_kesejahteraan'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_gj_penyesuaian'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_total_gaji'], 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($total['total_pph_harus_dibayar'], 0, true) }}</th>
        </tr>
    </tfoot>
</table>