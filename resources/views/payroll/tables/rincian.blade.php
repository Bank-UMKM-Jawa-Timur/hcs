<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
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
                <td class="text-center">{{ $number++ }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ $gj_pokok > 0 ? $gj_pokok : '-' }}</td>
                <td class="text-right">{{ $tj_keluarga > 0 ? $tj_keluarga : '-' }}</td>
                <td class="text-right">{{ $tj_listrik > 0 ? $tj_listrik : '-' }}</td>
                <td class="text-right">{{ $tj_jabatan > 0 ? $tj_jabatan : '-' }}</td>
                <td class="text-right">{{ $tj_khusus > 0 ? $tj_khusus : '-' }}</td>
                <td class="text-right">{{ $tj_perumahan > 0 ? $tj_perumahan : '-' }}</td>
                <td class="text-right">{{ $tj_pelaksana > 0 ? $tj_pelaksana : '-' }}</td>
                <td class="text-right">{{ $tj_kemahalan > 0 ? $tj_kemahalan : '-' }}</td>
                <td class="text-right">{{ $tj_kesejahteraan > 0 ? $tj_kesejahteraan : '-' }}</td>
                <td class="text-right">{{ $gj_penyesuaian > 0 ? $gj_penyesuaian : '-' }}</td>
                <td class="text-right">{{ $total_gaji > 0 ? $total_gaji : '-' }}</td>
                <td class="text-right">{{ $pph_harus_dibayar > 0 ? $pph_harus_dibayar : '-' }}</td>
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
            <th class="text-right">{{ number_format($footer_gj_pokok, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_keluarga, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_listrik, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_jabatan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_khusus, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_perumahan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_pelaksana, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_kemahalan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_tj_kesejahteraan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_gj_penyesuaian, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_pph_harus_dibayar, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>