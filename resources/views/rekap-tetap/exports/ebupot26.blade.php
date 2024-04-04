<table>
    <thead>
        <tr>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
            <th style="background-color: #00B050"></th>
        </tr>
        <tr>
            <th style="width: 50px; color: black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                No
            </th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">Tgl
                Pemotongan <br> (dd/MM/yyyy)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                TIN <br> (dengan format/tanda baca)</th>
            <th style="width: 200px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Nama Penerima <br> Penghasilan</th>
            <th style="width: 450px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Alamat Penerima <br> Penghasilan</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                No Paspor Penerima <br> Penghasilan</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">Kode
                <br> Negara
            </th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Penandatangan Menggunakan? <br> (NPWP/NIK)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                NPWP Penandatangan <br> (tanpa format/tanda baca)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                NIK Penandatangan <br> (tanpa format/tanda baca)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="right">
                Penghasilan Bruto</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Mendapatkan Fasilitas?<br> (N/SKD)</th>
            <th style="width: 220px; color:black; background-color: #4F81BD; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Nomor Tanda Terima SKDP</th>
            <th style="width: 150px; color:black; background-color: #4F81BD; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Tarif SKD</th>
        </tr>
    </thead>
    {{-- <tbody>
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
            $status = null;
        @endphp
        @foreach ($data as $item)
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
                            $pph21 += floor($terutang->terutang);
                        }
                    } else {
                        $pph21Bentukan = floor($value->total_pph);
                        $pph21 = floor($value->total_pph);
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
                $totalPPH21Bentukan += floor($pph21Bentukan);
                $totalPajakInsentifNew += $insentif_kredit_pajak;
                $totalPajakPenagihan += $insentif_penagihan_pajak;
                $totalPPH21 += floor($pph21);
                $totalPenambahBruto += $penambahBruto;
                $totalBruto += $brutoTotal;
                $totalPPh += $brutoPPH;
                $totalBrutoTHR += $brutoTHR;
                $totalBrutoDanaPendidikan += $brutoDanaPendidikan;
                $totalBrutoPenghargaanKinerja += $brutoPenghargaanKinerja;
                $total_insentif_kredit += $insentifKredit ? $insentifKredit : 0;
                $total_insentif_penagihan += $insentifPenagihan ? $insentifPenagihan : 0;
            @endphp
            <tr>
                <td align="right" style="color:black;">{{ $loop->iteration }}</td>
                <td align="left" style="color:black;">{{ date('d/m/Y', strtotime($lastdate)) }}</td>
                <td align="left" style="color:black;">IE6340278W</td>
                <td align="left" style="color:black;">{{$item->nama_karyawan }}</td>
                <td align="left" style="color:black;">{{$item->alamat_ktp }}</td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;">ARE</td>
                <td align="left" style="color:black;">NPWP</td>
                <td align="left" style="color:black;">{{ $penandatangan }}</td>
                <td align="left" style="color:black;"></td>
                <td align="right" style="color:black;"> {{ $brutoTotal }}</td>
                <td align="left" style="color:black;">N</td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;"></td>
            </tr>
        @endforeach
    </tbody> --}}
</table>
