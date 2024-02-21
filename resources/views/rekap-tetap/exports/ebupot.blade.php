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
                Penerima Penghasilan ? <br> (NPWP / NIK)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                NPWP <br> (Tanpa format / Tanda baca)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">NIK
                <br> (Tanpa format / Tanda baca)
            </th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Nama Penerima <br> Penghasilan Sesuai NIK</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Alamat Penerima <br> Penghasilan Sesuai NIK</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Kode Objek Pajak</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Penandatangan Menggunakan ? <br>(NPWP / NIK)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                NPWP Penandatangan <br>(Tanpa format / Tanda baca)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">NIK Penandatangan <br>(Tanpa format / Tanda baca)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Kode PTKP</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Pegawai Harian ? <br> (Ya / Tidak)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Menggunakan <br> Gross Up ? (Ya / Tidak)</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Penghasilan Bruto</th>
            <th style="width: 150px; color:black; background-color: #EEECE1; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Mendapatkan Fasilitas ? <br> (N/SKB/DTP) </th>
            <th style="width: 150px; color:black; background-color: #4F81BD; font-weight: bold; vertical-align: top; border-top: 4px solid black; border-bottom: 4px solid black;"
                align="center">
                Nomor SKB / Nomor DTP</th>
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
                // pajak insentif
                $insentif_kredit_pajak = floor($item->insentif_kredit_pajak);
                $insentif_penagihan_pajak = floor($item->insentif_penagihan_pajak);

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
                $pph21 -= floor($item->pajak_insentif);
                $penambahBruto = $item->jamsostek;

                $brutoTotal = floor($gaji + $uangMakan + $pulsa + $vitamin + $transport + $lembur + $penggantiBiayaKesehatan + $uangDuka + $spd + $spdPendidikan + $spdPindahTugas + $item->insentif_kredit + $item->insentif_penagihan + $totalBrutoTHR + $brutoDanaPendidikan + $brutoPenghargaanKinerja + $tambahanPenghasilan + $rekreasi + $brutoNataru + $brutoJaspro + $penambahBruto);
                $brutoPPH = $pphNataru + $pphJaspro + $pphTambahanPenghasilan + $pphRekreasi + $pph21Bentukan;

            @endphp
            <tr>
                <td align="right" style="color:black;">{{ $loop->iteration }}</td>
                <td align="left" style="color:black;">{{ $lastdate }}</td>
                <td align="left" style="color:black;">NPWP</td>
                <td align="left" style="color:black;">{{ "'".$item->npwp }}</td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;">21-100-01</td>
                <td align="left" style="color:black;">NPWP</td>
                <td align="left" style="color:black;">{{ "'".$penandatangan }}</td>
                <td align="left" style="color:black;"></td>
                <td align="left" style="color:black;">{{ $item->status_ptkp }}</td>
                <td align="left" style="color:black;">Tidak</td>
                <td align="left" style="color:black;">Tidak</td>
                <td align="right" style="color:black;"> {{ $brutoTotal }}</td>
                <td align="left" style="color:black;">N</td>
                <td align="left" style="color:black;"></td>
            </tr>
        @endforeach
    </tbody>
</table>
