<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama karyawan</th>
            <th class="text-center">No Rek</th>
            <th class="text-center">Gaji</th>
            <th class="text-center">Total Potongan</th>
            <th class="text-center">Gaji Bersih</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $number = $page == 1 ? 1 : $start;
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
                $iuran_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->iuran_koperasi, 0, ',', '.') : 0;
                $kredit_pegawai = $item->potonganGaji ? number_format($item->potonganGaji->kredit_pegawai, 0, ',', '.') : 0;
                $iuran_ik = $item->potonganGaji ? number_format($item->potonganGaji->iuran_ik, 0, ',', '.') : 0;
                $total_potongan = number_format($item->total_potongan, 0, ',', '.');
                $total_diterima = $item->total_yg_diterima ? number_format($item->total_yg_diterima, 0, ',', '.') : 0;

                // count total
                $footer_total_gaji += str_replace('.', '', $total_gaji);
                $footer_total_potongan += str_replace('.', '', $total_potongan);
                $footer_total_diterima += str_replace('.', '', $total_diterima);
                $footer_total_gaji_bersih += str_replace('.', '', $total_diterima);
            @endphp
            <tr>
                <td class="text-center">{{ $number++ }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td class="text-center">{{ $norek }}</td>
                <td class="text-right">{{ $total_gaji }}</td>
                <td class="text-right">{{ $total_potongan }}</td>
                <td class="text-right">{{ $total_diterima }}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-primary">Slip</button>
                </td>
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
            <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_gaji_bersih, 0, ',', '.') }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>