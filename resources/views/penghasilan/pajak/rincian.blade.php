@php
    $lima_persen = ceil(0.05 * $total_penghasilan_bruto);
    $keterangan = 500000 * $total_ket;
    $biaya_jabatan = 0;
    $no_14 = 0;
    if($lima_persen > $keterangan){
        $biaya_jabatan = $keterangan;
    } else {
        $biaya_jabatan = $lima_persen;
    }

    if ($total_ket == 0) {
        $no_14 = 0;
    } else {
        $rumus_14 = 0;
        if (0.05 * ($total_gaji + $total_tj_lainnya + $jaminan + $bonus_sum) > $keterangan) {
            $rumus_14 = ceil($keterangan);
        } else{
            $rumus_14 = ceil(0.05 * ($total_gaji + $total_tj_lainnya + $jaminan + $bonus_sum));
        }
        $no_14 = (($total_rutin + $total_tidak_rutin) - $bonus_sum - $pengurang - $biaya_jabatan) / $total_ket * 12 + $bonus_sum + ($biaya_jabatan - $rumus_14);
    }

    $no_16 = 0;
    if ($status == 'Mutasi Keluar') {
        $no_16 = floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000;
    } else {
        if (($no_14 - $ptkp?->ptkp_tahun) <= 0) {
            $no_16 = 0;
        } else {
            $no_16 = $no_14 - $ptkp?->ptkp_tahun;
        }
    }
    $persen5 = 0;
    if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
        if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
            $persen5 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
        } else {
            $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
        }
    } else {
        $persen5 = 0;
    }
    $persen15 = 0;
    if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
        if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
            $persen15 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000- 60000000) * 0.18;
        } else {
            $persen15 = 190000000 * 0.15;
        }
    } else {
        $persen15 = 0;
    }
    $persen25 = 0;
    if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
        if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
            $persen25 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
        } else {
            $persen25 = 250000000 * 0.25;
        }
    } else {
        $persen25 = 0;
    }
    $persen30 = 0;
    if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
        if (($no_14 - $ptkp?->ptkp_tahun) <= 5000000000) {
            $persen30 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
        } else {
            $persen30 = 4500000000 * 0.30;
        }
    } else {
        $persen30 = 0;
    }
    $persen35 = 0;
    if (($no_14 - $ptkp?->ptkp_tahun) > 5000000000) {
            $persen35 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.35 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.42;
    } else {
        $persen35 = 0;
    }

    $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;

    if($total_ket < 12){
        $no19 = 0;
        for ($i=0; $i < 12; $i++) { 
            if ($gj[$i] != null) {
                $penghasilanBruto = array_sum($gj[$i]) + array_sum($penghasilan[$i]) + array_sum($bonus[$i]) + $jamsostek[$i];
                $ter_kategori = \App\Helpers\HitungPPH::getTarifEfektifKategori($status);
                $lapisanPenghasilanBruto = DB::table('lapisan_penghasilan_bruto')
                    ->where('kategori', $ter_kategori)
                    ->where(function($query) use ($penghasilanBruto) {
                        $query->where(function($q2) use ($penghasilanBruto) {
                            $q2->where('nominal_start', '<=', $penghasilanBruto)
                                ->where('nominal_end', '>=', $penghasilanBruto);
                        })->orWhere(function($q2) use ($penghasilanBruto) {
                            $q2->where('nominal_start', '<=', $penghasilanBruto)
                                ->where('nominal_end', 0);
                        });
                    })
                    ->first();
                $pengali = 0;
                if ($lapisanPenghasilanBruto) {
                    $pengali = $lapisanPenghasilanBruto->pengali;
                }
                $pph = $penghasilanBruto * ($pengali / 100);
                $no19 += round($pph);
            }
        }
    } else {
        $no19 = floor(($no17 / 12) * $total_ket);
    }
@endphp
<div class="p-5 space-y-5">
    <h3 class="font-bold uppercase">B.1. RINCIAN PENGHASILAN</h3>
    <div class="flex gap-5">
        <div class="w-full space-y-5 mt-5">
            <div class="form-horizontal">
                <div class="w-full">
                    <label for="npwp">PENGHASILAN TERATUR</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_rutin) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>PENGHASILAN TIDAK TERATUR</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_tidak_rutin) }}" readonly>
                </div>
            </div>
        </div>
        <div class="w-full lg:block hidden"></div>
    </div>
    <h3 class="font-bold uppercase">PENGHASILAN BRUTO</h3>
    <div class="flex gap-5">
        <div class="w-full space-y-5 mt-5">
            <div class="form-horizontal">
                <div class="w-full">
                    <label for="npwp">1. Gaji/Pansiun atau THT/JHT</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled"  value="{{ rupiah($total_gaji) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>2. Tunjangan PPh</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_tidak_rutin) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>3. Tunjangan Lainnya, Uang Lembur dan sebagainya</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_tj_lainnya) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>4. Honorarium dan Imbalan Lainnya</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="-" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>5. Premi Asuransi yang dibayarkan Pemberi Kerja</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($jaminan) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>6. Penerimaan dalam Bentuk Natura atau Kenikmatan Lainnya yang dikenakan Pemotongan PPh Pasal 21</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="-" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>7. Tantiem, Bonus, Gratifikasi, Jaspro dan THR</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled"  value="{{ rupiah($bonus_sum) }}" readonly>
                </div>
            </div>

            <div class="form-horizontal">
                <div class="w-full">
                    <label>8. Jumlah Penghasilan Bruto (1 + 2 + 3 + 4 + 5 + 6 + 7)</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_gaji + $total_pph + $total_tj_lainnya + $total_honorium + $jaminan + $total_pph_21 + $bonus_sum) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full text-center">
                    <label class="font-semibold text-neutral-500">Total Penghasilan (Teratur + Tidak Teratur)</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($total_rutin + $total_tidak_rutin) }}" readonly>
                </div>
            </div>
            <h3 class="font-bold uppercase">PENGURANGAN PENGHASILAN</h3>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>9. Biaya Jabatan/Biaya Pensiun</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($biaya_jabatan) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>10. Iuran Pensiun atau Iuran THT/JHT</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($pengurang) }}" readonly>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="w-full">
                    <label>11. Jumlah Pengurangan (9 + 10)</label>
                </div>
                <div class="w-full">
                    <input type="text" class="form-input-disabled" value="{{ rupiah($biaya_jabatan + $pengurang) }}" readonly>
                </div>
            </div>
            <h3 class="font-bold uppercase">B.2 PENGHITUNGAN PPh PASAL 21</h3>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>12. Jumlah Penghasilan Neto (8 - 11)</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah(($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang)) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>13. Penghasilan Neto Masa sebelumnya</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="-" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full text-center">
                        <label class="font-semibold text-neutral-500">Total Penghasilan Neto</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah(($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang)) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>14. Jumlah Penghasilan Neto untuk PPh Pasal 21 (Setahun/Disetahunkan)</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah(round($no_14)) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>15. Penghasilan Tidak Kena Pajak (PTKP)</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($ptkp?->ptkp_tahun) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>16. Penghasilan Kena Pajak Setahun/Disetahunkan</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($no_16) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>17. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($no17) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>18. PPh Pasal 21 yang telah dipotong Masa Sebelumnya</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="-" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>19. PPh Pasal 21 Terutang</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($no19) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>20. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($total_pph_lunas) }}" readonly>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="w-full">
                        <label>U. PPh Pasal 21 yang masih harus dibayar</label>
                    </div>
                    <div class="w-full">
                        <input type="text" class="form-input-disabled" value="{{ rupiah($no19 - $total_pph_lunas) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full"></div>
    </div>