<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
        <tr>
            <th class="text-center">No</th>
            <th>Nama karyawan</th>
            <th class="text-center">Gaji Pokok</th>
            <th class="text-center">T. Keluarga</th>
            <th class="text-center">T. Listrik dan Air</th>
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
        @forelse ($data as $key => $item)
            @php
                $gj_pokok = $item->gaji ? round($item->gaji->gj_pokok) : 0;
                $tj_keluarga = $item->gaji ? round($item->gaji->tj_keluarga) : 0;
                $tj_listrik = $item->gaji ? round($item->gaji->tj_telepon) : 0;
                $tj_jabatan = $item->gaji ? round($item->gaji->tj_jabatan) : 0;
                $tj_khusus = $item->gaji ? round($item->gaji->tj_ti) : 0;
                $tj_perumahan = $item->gaji ? round($item->gaji->tj_perumahan) : 0;
                $tj_pelaksana = $item->gaji ? round($item->gaji->tj_pelaksana) : 0;
                $tj_kemahalan = $item->gaji ? round($item->gaji->tj_kemahalan) : 0;
                $tj_kesejahteraan = $item->gaji ? round($item->gaji->tj_kesejahteraan) : 0;
                $gj_penyesuaian = $item->gaji ? round($item->gaji->gj_penyesuaian) : 0;
                $total_gaji = $item->gaji ? round($item->gaji->total_gaji) : 0;
                $pph_harus_dibayar = 0;
                if ($item->perhitungan_pph21) {
                    if ($item->perhitungan_pph21->pph_pasal_21) {
                        if ($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar > 0) {
                            $pph_harus_dibayar = round($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar);
                        }
                    }
                }
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ $gj_pokok > 0 ? $gj_pokok : 0 }}</td>
                <td class="text-right">{{ $tj_keluarga > 0 ? $tj_keluarga : 0 }}</td>
                <td class="text-right">{{ $tj_listrik > 0 ? $tj_listrik : 0 }}</td>
                <td class="text-right">{{ $tj_jabatan > 0 ? $tj_jabatan : 0 }}</td>
                <td class="text-right">{{ $tj_khusus > 0 ? $tj_khusus : 0 }}</td>
                <td class="text-right">{{ $tj_perumahan > 0 ? $tj_perumahan : 0 }}</td>
                <td class="text-right">{{ $tj_pelaksana > 0 ? $tj_pelaksana : 0 }}</td>
                <td class="text-right">{{ $tj_kemahalan > 0 ? $tj_kemahalan : 0 }}</td>
                <td class="text-right">{{ $tj_kesejahteraan > 0 ? $tj_kesejahteraan : 0 }}</td>
                <td class="text-right">{{ $gj_penyesuaian > 0 ? $gj_penyesuaian : 0 }}</td>
                <td class="text-right">{{ $total_gaji > 0 ? $total_gaji : 0 }}</td>
                <td class="text-right">{{ $pph_harus_dibayar > 0 ? "($pph_harus_dibayar)" : 0 }}</td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="14">Data tidak tersedia.</td>
            </tr>
        @endforelse
    </tbody>
</table>