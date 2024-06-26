@php
    $formatrp = $is_cetak ? false : true;
    $colspanBonus = 3;
    if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0) {
        $colspanBonus += 1;
    }
    if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0) {
        $colspanBonus += 1;
    }
    if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0) {
        $colspanBonus += 1;
    }
    if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0) {
        $colspanBonus += 1;
    }
@endphp

<table class="tables table whitespace-nowrap table-bordered table-scroll" id="table" style="width: 100%;">
    <thead class="text-primary" style=" position: sticky; top: 0; background-color: white; z-index: 1;">
        <tr class="thead_pertama">
            <th rowspan="2" class="text-center">NO</th>
            <th rowspan="2" class="text-center">NIP</th>
            <th rowspan="2" class="text-center">NPWP</th>
            <th rowspan="2" class="text-center">PTKP</th>
            <th rowspan="2" class="text-center">NAMA KARYAWAN</th>
            <th rowspan="2" class="text-center">GAJI</th>
            <th class="text-center" colspan="4">TERATUR</th>
            <th class="text-center" colspan="8">TIDAK TERATUR</th>
            <th class="text-center" colspan="{{ $colspanBonus }}">BONUS</th>
            <th rowspan="2" class="text-center">PENAMBAH PENGHASILAN <br> BRUTO</th>
            <th rowspan="2" class="text-center">TOTAL BRUTO</th>
            <th rowspan="2" class="text-center">PPH21 BENTUKAN</th>
            <th colspan="3" class="text-center">PAJAK INSENTIF</th>
            <th rowspan="2" class="text-center">PPH21<br>(PPH BENTUKAN - PAJAK INSENTIF)</th>
        </tr>
        <tr>
            {{-- teratur --}}
            <th class="text-center">UANG MAKAN</th>
            <th class="text-center">PULSA</th>
            <th class="text-center">PENGGANTI VITAMIN</th>
            <th class="text-center">TRANSPORT</th>
            {{-- teratur --}}
            {{-- tidak teratur --}}
            <th class="text-center">LEMBUR</th>
            <th class="text-center">PENGGANTI UANG KESEHATAN</th>
            <th class="text-center">UANG DUKA</th>
            <th class="text-center">PERJALANAN DINAS</th>
            <th class="text-center">PENDIDIKAN</th>
            <th class="text-center">PINDAH TUGAS</th>
            <th class="text-center">INSENTIF KREDIT</th>
            <th class="text-center">INSENTIF PENAGIHAN</th>
            {{-- end tidak teratur --}}
            {{-- bonus --}}
            <th class="text-center">THR</th>
            <th class="text-center">DANA<br>PENDIDIKAN</th>
            <th class="text-center">PENGHARGAAN<br>KINERJA</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th class="text-center">NATARU</th>
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th class="text-center">JASA PRODUKSI + DANA KESEHATAN</th>
            @endif
            @if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0)
                <th class="text-center">TAMBAHAN<br>PENGHASILAN</th>
            @endif
            @if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0)
                <th class="text-center">REKREASI</th>
            @endif
            {{-- bonus --}}
            {{-- pajak insentif --}}
            <th class="text-center">KREDIT</th>
            <th class="text-center">PENAGIHAN</th>
            <th class="text-center">TOTAL</th>
            {{-- end pajak insentif --}}
            {{-- bruto --}}
            {{-- <th class="text-center">BRUTO</th> --}}
            {{-- <th class="text-center">PPH</th> --}}
            {{-- <th class="text-center">BRUTO Insentif</th>
                <th class="text-center">Pajak Insentif</th> --}}
        </tr>
    </thead>
    <tbody>
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
            $totalBrutoNataru = 0;
            $totalPPHNataru = 0;
            $totalBrutoJaspro = 0;
            $totalPPHJaspro = 0;
            $totalBrutoTambahanPenghasilan = 0;
            $totalPPHTambahanPenghasilan = 0;
            $totalBrutoRekreasi = 0;
            $totalPPHRekreasi = 0;
            $totalPPH21Bentukan = 0;
            $totalPajakInsentifNew = 0;
            $totalPajakPenagihan = 0;
            $totalPPH21 = 0;
            $totalPenambahBruto = 0;
            $totalBruto = 0;
            $totalPPh = 0;
            $totalpajakInsentif = 0;
            $totalInsentif = 0;
            $totalBrutoDanaPendidikan = 0;
            $totalColumns = 19;
            // insentif kredit
            $total_insentif_kredit = 0;
            $total_insentif_penagihan = 0;
            if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0) {
                $totalColumns += 1;
            }
            if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0) {
                $totalColumns += 1;
            }
            if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0) {
                $totalColumns += 1;
            }
            if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0) {
                $totalColumns += 1;
            }
        @endphp
        @forelse ($data as $key => $item)
            @php
                $nip = $item->nip ? $item->nip : '-';
                if (str_contains($nip, 'U')) {
                    $nip = '-';
                }
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
                $insentifPenagihan = 0;
                $insentifKredit = 0;
                $tambahanPenghasilan = 0;
                $pphTambahanPenghasilan = 0;
                $rekreasi = 0;
                $pphRekreasi = 0;
                $brutoNataru = 0;
                $pphNataru = 0;
                $brutoJaspro = 0;
                $pphJaspro = 0;
                $pph21 = 0;
                $pph21Bentukan = 0;
                $pajakInsentif = 0;
                $penambahBruto = 0;
                $brutoTotal = 0;
                $brutoPPH = 0;
                $bonus = 0;
                $totalBonus = 0;
                $brutoTHR = 0;
                $totalBrutoTHR = 0;
                $brutoDanaPendidikan = 0;
                $brutoPenghargaanKinerja = 0;
                $totalBrutoPenghargaanKinerja = 0;
                $totalpajakInsentif += $item->pajak_insentif;
                $totalInsentif += $item->total_insentif;

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
                    // insentif penagihan
                    if ($value->id_tunjangan == 32) {
                        $insentifPenagihan += $value->nominal;
                    }
                    // insentif kredit
                    if ($value->id_tunjangan == 31) {
                        $insentifKredit += $value->nominal;
                    }
                }

                foreach ($item->tunjanganTidakTetap as $value) {
                    if ($value->id_tunjangan == 23) {
                        $brutoJaspro += $value->nominal;
                    }
                }

                foreach ($item->bonus as $key => $value) {
                    if ($value->id_tunjangan == 22) {
                        $brutoTHR += $value->nominal;
                    }
                    if ($value->id_tunjangan == 23) {
                        $brutoJaspro += $value->nominal;
                    }
                    if ($value->id_tunjangan == 24) {
                        $brutoDanaPendidikan += $value->nominal;
                    }
                    if ($value->id_tunjangan == 26) {
                        $tambahanPenghasilan += $value->nominal;
                    }
                    if ($value->id_tunjangan == 28) {
                        $brutoPenghargaanKinerja += $value->nominal;
                    }
                    if ($value->id_tunjangan == 33) {
                        $rekreasi += $value->nominal;
                    }
                }

                foreach ($item?->karyawan_bruto->pphDilunasi as $value) {
                    // pajak insentif
                    $insentif_kredit_pajak = floor($value->insentif_kredit);
                    $insentif_penagihan_pajak = floor($value->insentif_penagihan);
                    $total_pajak_insentif = floor($value->insentif_kredit + $value->insentif_penagihan);
                    if ($value->bulan > 1) {
                        $pph21Bentukan = floor($value->total_pph);
                        $pph21 = floor($value->total_pph);
                        $terutang = DB::table('pph_yang_dilunasi AS pph')
                                        ->select('pph.terutang')
                                        ->join('gaji_per_bulan AS gaji', 'gaji.id', 'pph.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                                        ->where('pph.id', $value->id)
                                        ->whereNull('batch.deleted_at')
                                        ->first();
                        if ($terutang) {
                            $pph21 += $terutang->terutang;
                        }
                    }
                    else {
                        $pph21Bentukan = $value->total_pph;
                        $pph21 = $value->total_pph;
                    }
                }
                $pph21 -= floor($total_pajak_insentif);
                $penambahBruto = $item->jamsostek;

                $brutoTotal = floor($gaji + $uangMakan + $pulsa + $vitamin + $transport + $lembur + $penggantiBiayaKesehatan + $uangDuka + $spd + $spdPendidikan + $spdPindahTugas + $insentifKredit + $insentifPenagihan + $brutoTHR + $brutoDanaPendidikan + $brutoPenghargaanKinerja + $tambahanPenghasilan + $rekreasi + $brutoNataru + $brutoJaspro + $penambahBruto);
                $brutoPPH = $pphNataru + $pphJaspro + $pphTambahanPenghasilan + $pphRekreasi + $pph21Bentukan;

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
                $totalBrutoTambahanPenghasilan += $tambahanPenghasilan;
                $totalBrutoRekreasi += $rekreasi;
                $totalPPHJaspro += $pphJaspro;
                $totalPPHTambahanPenghasilan += $pphTambahanPenghasilan;
                $totalPPHRekreasi += $pphRekreasi;
                $totalPPH21Bentukan += $pph21Bentukan;
                $totalPajakInsentifNew += $insentif_kredit_pajak;
                $totalPajakPenagihan += $insentif_penagihan_pajak;
                $totalPPH21 += floor($pph21);
                $totalPenambahBruto += $penambahBruto;
                $totalBruto += $brutoTotal;
                $totalPPh += $brutoPPH;
                $totalBrutoTHR += $brutoTHR;
                $totalBrutoDanaPendidikan += $brutoDanaPendidikan;
                $totalBrutoPenghargaanKinerja += $brutoPenghargaanKinerja;
                $total_insentif_kredit += $insentifKredit ? $insentifKredit : 0 ;
                $total_insentif_penagihan += $insentifPenagihan ? $insentifPenagihan : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $nip }}</td>
                <td>{{ $item->npwp ? $item->npwp : '-' }}</td>
                <td>{{ $item->status_ptkp ? $item->status_ptkp : '-' }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                {{-- teratur --}}
                <td class="td-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->total_gaji ?? 0, 0, $formatrp) : formatRupiahExcel($item->gj_pokok ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->uang_makan ?? 0, 0, $formatrp) : formatRupiahExcel(0) }}</td>
                <td class="td-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_pulsa ?? 0, 0, $formatrp) : formatRupiahExcel(0) }}</td>
                <td class="td-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_vitamin ?? 0, 0, $formatrp) : formatRupiahExcel(0) }}</td>
                <td class="td-right">{{ $item->gaji ? formatRupiahExcel($item->gaji->tj_transport ?? 0, 0, $formatrp) : formatRupiahExcel(0) }}</td>
                {{-- end teratur --}}
                {{-- tidak teratur --}}
                <td class="td-right">{{ formatRupiahExcel($lembur, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($penggantiBiayaKesehatan, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($uangDuka, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($spd, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($spdPendidikan, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($spdPindahTugas, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($insentifKredit ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($insentifPenagihan ?? 0, 0, $formatrp) }}</td>
                {{-- end tidak teratur --}}
                {{-- bonus --}}
                <td class="td-right">{{ formatRupiahExcel($brutoTHR, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($brutoDanaPendidikan ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($brutoPenghargaanKinerja ?? 0, 0, $formatrp) }}</td>
                @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                    <td class="td-right">{{ formatRupiahExcel($brutoNataru ?? 0, 0, $formatrp) }}</td>
                    {{--  <td class="td-right">{{ formatRupiahExcel($pphNataru, 0, $formatrp) }}</td>  --}}
                @endif
                @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                    <td class="td-right">{{ formatRupiahExcel($brutoJaspro ?? 0, 0, $formatrp) }}</td>
                    {{--  <td class="td-right">{{ formatRupiahExcel($pphJaspro, 0, $formatrp) }}</td>  --}}
                @endif
                @if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0)
                    <td class="td-right">{{ formatRupiahExcel($tambahanPenghasilan ?? 0, 0, $formatrp) }}</td>
                    {{--  <td class="td-right">{{ formatRupiahExcel($pphTambahanPenghasilan, 0, $formatrp) }}</td>  --}}
                @endif
                @if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0)
                    <td class="td-right">{{ formatRupiahExcel($rekreasi ?? 0, 0, $formatrp) }}</td>
                    {{--  <td class="td-right">{{ formatRupiahExcel($pphRekreasi, 0, $formatrp) }}</td>  --}}
                @endif
                {{-- end bonus --}}
                <td class="td-right">{{ formatRupiahExcel($penambahBruto ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($brutoTotal ?? 0, 0, $formatrp) }}</td>
                {{--  pph bentukan  --}}
                <td class="td-right">{{ formatRupiahExcel($pph21Bentukan ?? 0, 0, $formatrp) }}</td>
                {{--  end pph bentukan  --}}
                {{-- pajak insentif --}}
                <td class="td-right">{{ formatRupiahExcel($insentif_kredit_pajak ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($insentif_penagihan_pajak ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($insentif_kredit_pajak + $insentif_penagihan_pajak ?? 0, 0, $formatrp) }}</td>
                {{-- end pajak insentif --}}
                {{-- pph --}}
                <td class="td-right">{{ formatRupiahExcel($pph21 ?? 0, 0, $formatrp) }}</td>
                {{-- end pph --}}
                {{-- <td class="td-right">{{ formatRupiahExcel($brutoPPH ?? 0, 0, $formatrp) }}</td> --}}
                {{-- <td class="td-right">{{ formatRupiahExcel($item->total_insentif_kredit ?? 0, 0, $formatrp) }}</td>
                <td class="td-right">{{ formatRupiahExcel($item->pajak_insentif ?? 0, 0, $formatrp) }}</td> --}}
            </tr>
        @empty
            <tr>
                <td colspan="{{$totalColumns}}" class="text-center">Maaf Data Tidak Tersedia</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        @if (!$is_cetak)
            <tr>
                <th colspan="5" class="text-center" style="position: sticky; left: 0; background-color: white; z-index: 2;">Total</th>
                {{-- teratur --}}
                <th class="text-right">{{ formatRupiahExcel($totalGaji ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalUangMakan ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPulsa ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalVitamin ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalTransport ?? 0, 0, $formatrp) }}</th>
                {{-- end teratur --}}
                {{-- tidak teratur --}}
                <th class="text-right">{{ formatRupiahExcel($totalLembur ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPenggantiKesehatan ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalUangDuka ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalSPD ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalSPDPendidikan ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalSPDPindahTugas ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($total_insentif_kredit ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($total_insentif_penagihan ?? 0, 0, $formatrp) }}</th>
                {{-- end teratur --}}
                {{-- bonus --}}
                <th class="text-right">{{ formatRupiahExcel($totalBrutoTHR ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalBrutoDanaPendidikan ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalBrutoPenghargaanKinerja ?? 0, 0, $formatrp) }}</th>
                @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                    <th class="text-right">{{ formatRupiahExcel($totalBrutoNataru ?? 0, 0, $formatrp) }}</th>
                    {{-- <th class="text-right">{{ formatRupiahExcel($totalPPHNataru ?? 0, 0, $formatrp) }}</th> --}}
                @endif
                @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                    <th class="text-right">{{ formatRupiahExcel($totalBrutoJaspro ?? 0, 0, $formatrp) }}</th>
                    {{-- <th class="text-right">{{ formatRupiahExcel($totalPPHJaspro ?? 0, 0, $formatrp) }}</th> --}}
                @endif
                @if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0)
                    <th class="text-right">{{ formatRupiahExcel($totalBrutoTambahanPenghasilan ?? 0, 0, $formatrp) }}</th>
                    {{-- <th class="text-right">{{ formatRupiahExcel($totalPPHTambahanPenghasilan ?? 0, 0, $formatrp) }}</th> --}}
                @endif
                @if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0)
                    <th class="text-right">{{ formatRupiahExcel($totalBrutoRekreasi ?? 0, 0, $formatrp) }}</th>
                    {{-- <th class="text-right">{{ formatRupiahExcel($totalPPHRekreasi ?? 0, 0, $formatrp) }}</th> --}}
                @endif
                {{-- end bonus --}}
                <th class="text-right">{{ formatRupiahExcel($totalPenambahBruto ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalBruto ?? 0, 0, $formatrp) }}</th>
                {{--  pph bentukan  --}}
                <th class="text-right">{{ formatRupiahExcel($totalPPH21Bentukan ?? 0, 0, $formatrp) }}</th>
                {{--  end pph bentukan  --}}
                {{-- pajak insentif --}}
                <th class="text-right">{{ formatRupiahExcel($totalPajakInsentifNew ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPajakPenagihan ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalPajakInsentifNew + $totalPajakPenagihan ?? 0, 0, $formatrp) }}</th>
                {{-- end pajak insentif --}}
                {{-- pph --}}
                <th class="text-right">{{ formatRupiahExcel($totalPPH21 ?? 0, 0, $formatrp) }}</th>
                {{-- end pph --}}
                {{-- <th class="text-right">{{ formatRupiahExcel($totalPPh ?? 0, 0, $formatrp) }}</th> --}}
                {{-- <th class="text-right">{{ formatRupiahExcel($totalInsentif ?? 0, 0, $formatrp) }}</th>
                <th class="text-right">{{ formatRupiahExcel($totalpajakInsentif ?? 0, 0, $formatrp) }}</th> --}}
            </tr>
        @endif
        <tr>
            <th colspan="5" class="text-center" style="position: sticky; left: 0; background-color: white; z-index: 2;">Grand Total</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalGaji ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalUangMakan ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPulsa ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalVitamin ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalTransport ?? 0, 0, $formatrp) }}</th>
            {{-- tidak teratur --}}
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalLembur ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPenggantiKesehatan ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalUangDuka ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPD ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPDPendidikan ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalSPDPindahTugas ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->total_insentif_kredit ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->total_insentif_penagihan ?? 0, 0, $formatrp) }}</th>
            {{-- end  tidak teratur --}}
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoTHR, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoDanaPendidikan ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoPenghargaanKinerja ?? 0, 0, $formatrp) }}</th>
            @if ($grandTotal->totalBrutoNataru > 0 || $grandTotal->totalPPHNataru > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoNataru ?? 0, 0, $formatrp) }}</th>
                {{--  <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHNataru ?? 0, 0, $formatrp) }}</th>  --}}
            @endif
            @if ($grandTotal->totalBrutoJaspro > 0 || $grandTotal->totalPPHJaspro > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoJaspro ?? 0, 0, $formatrp) }}</th>
                {{--  <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHJaspro ?? 0, 0, $formatrp) }}</th>  --}}
            @endif
            @if ($grandTotal->totalBrutoTambahanPenghasilan > 0 || $grandTotal->totalPPHTambahanPenghasilan > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoTambahanPenghasilan ?? 0, 0, $formatrp) }}</th>
                {{--  <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHTambahanPenghasilan ?? 0, 0, $formatrp) }}</th>  --}}
            @endif
            @if ($grandTotal->totalBrutoRekreasi > 0 || $grandTotal->totalPPHRekreasi > 0)
                <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBrutoRekreasi ?? 0, 0, $formatrp) }}</th>
                {{--  <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPHRekreasi ?? 0, 0, $formatrp) }}</th>  --}}
            @endif
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPenambahBruto ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalBruto ?? 0, 0, $formatrp) }}</th>
            {{--  pph bentukan  --}}
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPH21Bentukan ?? 0, 0, $formatrp) }}</th>
            {{--  end pph bentukan  --}}
            {{-- pajak insentif --}}
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPajakInsentif ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->total_insentif_penagihan_pajak ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPajakInsentif + $grandTotal->total_insentif_penagihan_pajak ?? 0, 0, $formatrp) }}</th>
            {{-- end pajak insentif --}}
            {{-- pph --}}
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPH21 ?? 0, 0, $formatrp) }}</th>
            {{-- end pph --}}
            {{-- <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPPh ?? 0, 0, $formatrp) }}</th> --}}
            {{-- <th class="text-right">{{ formatRupiahExcel($grandTotal->totalInsentif ?? 0, 0, $formatrp) }}</th>
            <th class="text-right">{{ formatRupiahExcel($grandTotal->totalPajakInsentif ?? 0, 0, $formatrp) }}</th> --}}
        </tr>
    </tfoot>
</table>


@push('style')
<style>
    .tables td{
        border: 1px solid #0f172a !important;
    }
    .tables th{
        border: 1px solid #0f172a !important;
    }
</style>
@endpush
