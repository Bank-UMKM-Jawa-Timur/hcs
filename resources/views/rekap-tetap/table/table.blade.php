<table class="tables table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border: 1px solid #e3e3e3 !important; position: sticky; top: 0; background-color: white; z-index: 1;">
        <tr>
            <th rowspan="2" class="text-center">NO</th>
            <th rowspan="2" class="text-center" style="position: sticky; left: 0; background-color: white; z-index: 2;">NAMA KARYAWAN</th>
            <th class="text-center">GAJI</th>
            <th class="text-center">UANG MAKAN</th>
            <th class="text-center">PULSA</th>
            <th class="text-center">PENGGANTI VITAMIN</th>
            <th class="text-center">TRANSPORT</th>
            <th class="text-center">LEMBUR</th>
            <th class="text-center">PENGGANTI UANG KESEHATAN</th>
            <th class="text-center">UANG DUKA</th>
            <th class="text-center">PERJALANAN DINAS</th>
            <th class="text-center">PENDIDIKAN</th>
            <th class="text-center">PINDAH TUGAS</th>
            <th class="text-center">THR</th>
            <th class="text-center">DANA<br>PENDIDIKAN</th>
            <th class="text-center">TAMBAHAN<br>PENGHASILAN</th>
            <th class="text-center">PENGHARGAAN<br>KINERJA</th>
            <th class="text-center">REKREASI</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th colspan="2" class="text-center">NATARU</th>
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th colspan="2" class="text-center">JASA PRODUKSI + DANA KESEHATAN</th>
            @endif
            <th rowspan="2" class="text-center">PPh Ps 21</th>
            <th rowspan="2" class="text-center">Penambah Penghasilan Bruto</th>
            <th colspan="2" class="text-center">TOTAL</th>
        </tr>
        <tr>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            <th class="text-center">BRUTO</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th class="text-center">BRUTO</th>
                <th class="text-center">PPH</th>
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th class="text-center">BRUTO</th>
                <th class="text-center">PPH</th>
            @endif
            <th class="text-center">BRUTO</th>
            <th class="text-center">PPH</th>
        </tr>
    </thead>
    <tbody >
        @php
            $totalGaji = 0;
            $totalGajiOpsi = 0;
            $totalUangMakan = 0;
            $totalPulsa = 0;
            $totalVitamin = 0;
            $totalTransport = 0;
            $totalLembur = 0;
            $totalPenggantiKesehatan = 0;
            $totalUangDuka = 0;
            $totalSPD = 0;
            $totalSPDPendidikan = 0;
            $totalSPDPindahTugas = 0;
            $totalTambahanPenghasilan = 0;
            $totalRekreasi = 0;
            $totalBrutoNataru = 0;
            $totalPPHNataru = 0;
            $totalBrutoJaspro = 0;
            $totalPPHJaspro = 0;
            $totalPPH21 = 0;
            $totalPenambahBruto = 0;
            $totalBruto = 0;
            $totalPPh = 0;
            $totalColumns = 17;
            if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0) {
                $totalColumns += 2;
            }
            if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0) {
                $totalColumns += 2;
            }
        @endphp
        @forelse ($data as $key => $item)
            @php
                $gaji = $item->gaji->total_gaji ?? 0;
                $uangMakan = $item->gaji->uang_makan ?? 0;
                $pulsa = $item->gaji->tj_pulsa ?? 0;
                $vitamin = $item->gaji->tj_vitamin ?? 0;
                $transport = $item->gaji->tj_transport ?? 0;
                $lembur = 0;
                $penggantiBiayaKesehatan = 0;
                $uangDuka = 0;
                $spd = 0;
                $spdPendidikan = 0;
                $spdPindahTugas = 0;
                $tambahanPenghasilan = 0;
                $rekreasi = 0;
                $brutoNataru = 0;
                $pphNataru = 0;
                $brutoJaspro = 0;
                $pphJaspro = 0;
                $pph21 = 0;
                $penambahBruto = 0;
                $brutoTotal = 0;
                $brutoPPH = 0;

                foreach ($item->tunjanganTidakTetap as $value) {
                    if ($value->id_tunjangan == 16) {
                        $lembur += $value->nominal;
                    }
                    if ($value->id_tunjangan == 17) {
                        $penggantiBiayaKesehatan += $value->nominal;
                    }
                    if ($value->id_tunjangan == 18) {
                        $uangDuka += $value->nominal;
                    }
                    if ($value->id_tunjangan == 19) {
                        $spd += $value->nominal;
                    }
                    if ($value->id_tunjangan == 20) {
                        $spdPendidikan += $value->nominal;
                    }
                    if ($value->id_tunjangan == 21) {
                        $spdPindahTugas += $value->nominal;
                    }
                    if ($value->id_tunjangan == 33) {
                        $tambahanPenghasilan += $value->nominal;
                    }
                    if ($value->id_tunjangan == 33) {
                        $rekreasi += $value->nominal;
                    }
                }

                foreach ($item->tunjanganTidakTetap as $value) {
                    if ($value->id_tunjangan == 23) {
                        $brutoJaspro += $value->nominal;
                    }
                }

                foreach ($item?->pphDilunasi as $value) {
                    if ($value->bulan > 1) {
                        $pph21 += $value->total_pph;
                        $terutang = DB::table('pph_yang_dilunasi')
                                        ->select('terutang')
                                        ->where('nip', $value->nip)
                                        ->where('tahun', $value->tahun)
                                        ->where('bulan', ($value->bulan - 1))
                                        ->first();
                        if ($terutang) {
                            $pph21 += $terutang->terutang;
                        }
                    }
                    else {
                        $pph21 += $value->total_pph;
                    }
                }
                $penambahBruto = $item->jamsostek;

                $brutoTotal = $gaji + $uangMakan + $pulsa + $vitamin + $transport + $lembur + $penggantiBiayaKesehatan + $uangDuka + $spd + $spdPendidikan + $spdPindahTugas + $bonus + $brutoNataru + $brutoJaspro + $penambahBruto;
                $brutoPPH = $pphNataru + $pphJaspro + $pph21;

                // Hitung total per page
                $totalGaji += $item->gaji ? $item->gaji->total_gaji : $item->gj_pokok;
                $totalUangMakan += $item->gaji ? $item->gaji->uang_makan : 0;
                $totalPulsa += $item->gaji ? $item->gaji->tj_pulsa : 0;
                $totalVitamin += $item->gaji ? $item->gaji->tj_vitamin : 0;
                $totalTransport += $item->gaji ? $item->gaji->tj_transport : 0;
                $totalLembur += $lembur;
                $totalPenggantiKesehatan += $penggantiBiayaKesehatan;
                $totalUangDuka += $uangDuka;
                $totalSPD += $spd;
                $totalSPDPendidikan += $spdPendidikan;
                $totalSPDPindahTugas += $spdPindahTugas;
                $totalBonus += $bonus;
                $totalBrutoNataru += $brutoNataru;
                $totalPPHNataru += $pphNataru;
                $totalBrutoJaspro += $brutoJaspro;
                $totalPPHJaspro += $pphJaspro;
                $totalPPH21 += $pph21;
                $totalPenambahBruto += $penambahBruto;
                $totalBruto += $brutoTotal;
                $totalPPh += $brutoPPH;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td style="position: sticky; left: 0; background-color: white; z-index: 2;">{{ $item->nama_karyawan }}</td>
                <td class="text-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->total_gaji ?? 0, 0, true) : formatRupiahExcel($item->gj_pokok ?? 0, 0, true) }}</td>
                <td class="text-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->uang_makan ?? 0, 0, true) : formatRupiahExcel(0) }}</td>
                <td class="text-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_pulsa ?? 0, 0, true) : formatRupiahExcel(0) }}</td>
                <td class="text-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_vitamin ?? 0, 0, true) : formatRupiahExcel(0) }}</td>
                <td class="text-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_transport ?? 0, 0, true) : formatRupiahExcel(0) }}</td>
                <td class="text-right">{{ formatRupiahExcel($lembur, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($penggantiBiayaKesehatan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($uangDuka, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($spd, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($spdPendidikan, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($spdPindahTugas, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($bonus, 0, true) }}</td>
                @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                    <td class="text-right">{{ formatRupiahExcel($brutoNataru, 0, true) }}</td>
                    <td class="text-right">{{ formatRupiahExcel($pphNataru, 0, true) }}</td>
                @endif
                @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                    <td class="text-right">{{ formatRupiahExcel($brutoJaspro, 0, true) }}</td>
                    <td class="text-right">{{ formatRupiahExcel($pphJaspro, 0, true) }}</td>
                @endif
                <td class="text-right">{{ formatRupiahExcel($pph21, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($penambahBruto, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($brutoTotal, 0, true) }}</td>
                <td class="text-right">{{ formatRupiahExcel($brutoPPH, 0, true) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{$totalColumns}}" class="text-center">Maaf Data Tidak Tersedia</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center" style="position: sticky; left: 0; background-color: white; z-index: 2;">Total</th>
            <th class="text-right">{{ formatRupiahExcel($totalGaji ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalUangMakan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalPulsa ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalVitamin ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalTransport ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalLembur ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalPenggantiKesehatan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalUangDuka ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalSPD ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalSPDPendidikan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalSPDPindahTugas ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalBonus ?? 0, 0, true) }}</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th class="text-right">{{ formatRupiahExcel($totalBrutoNataru ?? 0, 0, true) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPPHNataru ?? 0, 0, true) }}</th>
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th class="text-right">{{ formatRupiahExcel($totalBrutoJaspro ?? 0, 0, true) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPPHJaspro ?? 0, 0, true) }}</th>
            @endif
            <th class="text-right">{{ formatRupiahExcel($totalPPH21 ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalPenambahBruto ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalBruto ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($totalPPh ?? 0, 0, true) }}</th>
        </tr>
        <tr>
            <th colspan="2" class="text-center" style="position: sticky; left: 0; background-color: white; z-index: 2;">Grand Total</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalGaji ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalUangMakan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPulsa ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalVitamin ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalTransport ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalLembur ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPenggantiKesehatan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalUangDuka ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPD ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPDPendidikan ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPDPindahTugas ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel(0 ?? 0, 0, true) }}</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoNataru ?? 0, 0, true) }}</th>
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHNataru ?? 0, 0, true) }}</th>
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoJaspro ?? 0, 0, true) }}</th>
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHJaspro ?? 0, 0, true) }}</th>
            @endif
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPH21 ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPenambahBruto ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBruto ?? 0, 0, true) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPh ?? 0, 0, true) }}</th>
        </tr>
    </tfoot>
</table>
