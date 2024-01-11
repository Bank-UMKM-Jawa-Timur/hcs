<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
        <tr>
            <th rowspan="2" class="text-center">NO</th>
            <th rowspan="2" class="text-center">NAMA KARYAWAN</th>
            <th rowspan="2" class="text-center">ALAMAT</th>
            <th rowspan="2" class="text-center">L/P</th>
            <th rowspan="2" class="text-center">JABATAN</th>
            <th rowspan="2" class="text-center">K/TK</th>
            <th rowspan="2" class="text-center">MASA KERJA</th>
            <th colspan="3" class="text-center">PENGHASILAN BRUTO</th>
            <th colspan="2" class="text-center">BIAYA JABATAN</th>
            <th rowspan="2" class="text-center">PENGHASILAN NETO</th>
            <th rowspan="2" class="text-center">PENGHASILAN NETO SETAHUN/DISETAHUNKAN</th>
            <th rowspan="2" class="text-center">PTKP</th>
            <th rowspan="2" class="text-center">PKP</th>
            <th rowspan="2" class="text-center">PPH 21 TERUTANG</th>
            <th rowspan="2" class="text-center">PPH YG SDH DIBAYAR</th>
            <th rowspan="2" class="text-center">KEKURANGAN PPH 21</th>
        </tr>
        <tr>
            <th class="text-center">TERATUR</th>
            <th class="text-center">TIDAK TERATUR</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">GAJI</th>
            <th class="text-center">IURAN PENSIUN</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalTeratur = 0;
            $totalTidakTeratur = 0;
            $totalPenghasilanBruto = 0;
            $totalBiayaJabatan = 0;
            $totalIuranPensiun = 0;
            $totalPenghasilanNeto = 0;
            $totalPenghasilanNetoSetahun = 0;
            $totalPtkp = 0;
            $totalPkp = 0;
            $totalPph21Terutang = 0;
            $totalPph21Dibayar = 0;
            $totalKekuranganPph21 = 0;
        @endphp
        @forelse ($data as $key => $item)
            @php
                $totalTeratur += $item->penghasilan_bruto->penghasilan_rutin ?? 0;
                $totalTidakTeratur += $item->penghasilan_tidak_rutin ?? 0;
                $totalPenghasilanBruto += $item->penghasilan_bruto->total_penghasilan ?? 0;
                $totalBiayaJabatan += $item->pengurangan_penghasilan->biaya_jabatan ?? 0;
                $totalIuranPensiun += $item->pengurangan_penghasilan->total_pengurangan_bruto ?? 0;
                $totalPenghasilanNeto += $item->perhitungan_pph21->total_penghasilan_neto ?? 0;
                $totalPenghasilanNetoSetahun += $item->perhitungan_pph21->jumlah_penghasilan_neto_pph21 ?? 0;
                $totalPtkp += $item->ptkp->ptkp_tahun ?? 0;
                $totalPkp += $item->perhitungan_pph21->pph_pasal_21->penghasilan_kena_pajak_setahun ?? 0;
                $totalPph21Terutang += $item->perhitungan_pph21->pph_pasal_21->pph_21_terutang ?? 0;
                $totalPph21Dibayar += $item->perhitungan_pph21->pph_pasal_21->pph_telah_dilunasi ?? 0;
                $totalKekuranganPph21 += $item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar ?? 0;

                $kurungDepan = '';
                $kurungBelakang = '';
                if ($item->perhitungan_pph21) {
                    if ($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar > 0) {
                        $kurungDepan = '(';
                        $kurungBelakang = ')';
                    }
                }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td>{{ $item->alamat_ktp }}</td>
                <td>{{ $item->jk == 'Laki-laki' ? 'L' : 'P' }}</td>
                <td>{{ $item->display_jabatan }}</td>
                <td>{{ $item->keluarga->status_kawin ?? '-' }}</td>
                <td>{{ $item->total_masa_kerja ? $item->total_masa_kerja : 0 }}</td>
                <td class="text-right">{{ number_format($item->penghasilan_bruto->penghasilan_rutin, 0, '.', '.') }}</td>
                <td class="text-right">{{ number_format($item->penghasilan_bruto->penghasilan_tidak_rutin, 0, '.', '.') }}</td>
                <td class="text-right">{{ number_format($item->penghasilan_bruto->total_penghasilan, 0, '.', '.') }}</td>
                <td class="text-right">{{ $item->pengurangan_penghasilan ? number_format($item->pengurangan_penghasilan->biaya_jabatan, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->pengurangan_penghasilan ? number_format($item->pengurangan_penghasilan->total_pengurangan_bruto, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? number_format($item->perhitungan_pph21->total_penghasilan_neto, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? number_format($item->perhitungan_pph21->jumlah_penghasilan_neto_pph21, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->ptkp ? number_format($item->ptkp->ptkp_tahun, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? number_format($item->perhitungan_pph21->pph_pasal_21->penghasilan_kena_pajak_setahun, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? number_format($item->perhitungan_pph21->pph_pasal_21->pph_21_terutang, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? number_format($item->perhitungan_pph21->pph_pasal_21->pph_telah_dilunasi, 0, '.', '.') : 0 }}</td>
                <td class="text-right">{{ $item->perhitungan_pph21 ? $kurungDepan . number_format($item->perhitungan_pph21->pph_pasal_21->pph_harus_dibayar, 0, '.', '.') . $kurungBelakang : 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="18" class="text-center">Maaf Data Tidak Tersedia</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-center">Total</th>
            <th class="text-right">{{ number_format($totalTeratur, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalTidakTeratur, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPenghasilanBruto, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalBiayaJabatan, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalIuranPensiun, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPenghasilanNeto, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPenghasilanNetoSetahun, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPtkp, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPkp, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPph21Terutang, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalPph21Dibayar, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($totalKekuranganPph21, 0, '.', '.') }}</th>
        </tr>
        <tr>
            <th colspan="7" class="text-center">Grand Total</th>
            <th class="text-right">{{ number_format($grandTotal->totalTeratur, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalTidakTeratur, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPenghasilanBruto, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalBiayaJabatan, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalIuranPensiun, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPenghasilanNeto, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPenghasilanNetoSetahun, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPtkp, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPkp, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPph21Terutang, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalPph21Dibayar, 0, '.', '.') }}</th>
            <th class="text-right">{{ number_format($grandTotal->totalKekuranganPph21, 0, '.', '.') }}</th>
        </tr>
    </tfoot>
</table>