<?php

namespace App\Repository;

use App\Models\CabangModel;
use App\Models\KaryawanModel;
use App\Models\PtkpModel;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class SlipGajiRepository
{
    private \Illuminate\Support\Collection $cabang;
    private String $orderRaw;

    public function __construct()
    {
        $this->cabang = CabangModel::pluck('kd_cabang');
        $this->orderRaw = "
            CASE
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='STAD' THEN 8
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 9
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 10
            WHEN mst_karyawan.kd_jabatan='PC' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 12
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 13
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 14
            WHEN mst_karyawan.kd_jabatan='ST' THEN 15
            WHEN mst_karyawan.kd_jabatan='NST' THEN 16
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 17 END ASC
        ";
    }

    public function getjabatan($nip){
        $karyawan = KaryawanModel::select(
            'mst_karyawan.nip',
            'mst_karyawan.nik',
            'mst_karyawan.nama_karyawan',
            'mst_karyawan.kd_bagian',
            'mst_karyawan.kd_jabatan',
            'mst_karyawan.kd_entitas',
            'mst_karyawan.tanggal_penonaktifan',
            'mst_karyawan.status_jabatan',
            'mst_karyawan.ket_jabatan',
            DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor")
        )
        ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
        ->with('jabatan')
        ->with('bagian')
        ->where('nip',$nip)
        ->orderByRaw($this->orderRaw)
        ->orderBy('mst_karyawan.kd_entitas')
        ->get();

        foreach ($karyawan as $key => $value) {
            $prefix = match ($value->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            if ($value->jabatan) {
                $jabatan = $value->jabatan->nama_jabatan;
            } else {
                $jabatan = 'undifined';
            }

            $ket = $value->ket_jabatan ? "({$value->ket_jabatan})" : '';

            if (isset($value->entitas->subDiv)) {
                $entitas = $value->entitas->subDiv->nama_subdivisi;
            } elseif (isset($value->entitas->div)) {
                $entitas = $value->entitas->div->nama_divisi;
            } else {
                $entitas = '';
            }

            if ($jabatan == 'Pemimpin Sub Divisi') {
                $jabatan = 'PSD';
            } elseif ($jabatan == 'Pemimpin Bidang Operasional') {
                $jabatan = 'PBO';
            } elseif ($jabatan == 'Pemimpin Bidang Pemasaran') {
                $jabatan = 'PBP';
            } else {
                $jabatan = $value->jabatan ? $value->jabatan->nama_jabatan : 'undifined';
            }

            $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $value?->bagian?->nama_bagian . ' ' . $ket;
            $value->display_jabatan = $display_jabatan;
        }

        return $karyawan;
    }
    public function getSlip($nip, $year) {
        /**
         * PPH 21
         * Gaji - done
         * Tunjangan Tetap - done
         * Tunjangan Tidak Tetap - done
         * BPJS TK - done
         * BPJS Kesehatan - done
         * Tambahan Penghasilan(bonus) - done
         * Potongan (JP1%, DPP 5%, Kredit Koperasi, Iuran Koperasi, Kredit Pegawai, Iuran IK) - done
         */

        /**
          * Filter
          * Kantor(Pusat/Cabang)
          * Bulan
          * Tahun
          */

        /**
         * Table
         * gaji = gaji_perbulan
         * tunjangan tetap = tunjangan_karyawan
         * tunjangan tidak tetap = penghasilan_tidak_teratur
         * bpjs tk = kpj (jamsostek)
         * bpjs kesehatan = jkn
         */

        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        $kode_cabang_str = json_encode($kode_cabang_arr);
        $kode_cabang_str = str_replace('[', '(', $kode_cabang_str);
        $kode_cabang_str = str_replace(']', ')', $kode_cabang_str);

        $isCabang = KaryawanModel::select('nip', 'kd_entitas')
                                ->where('nip', $nip)
                                ->join('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                                ->first();

        $kantor = $isCabang ? 'cabang': 'pusat';

        if($kantor == 'pusat'){
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        }
        else {
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('mst_profil_kantor.kd_cabang', $kantor)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', $kantor)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        }

        if (!$hitungan_penambah && !$hitungan_pengurang) {
            $persen_jkk = 0;
            $persen_jht = 0;
            $persen_jkm = 0;
            $persen_kesehatan = 0;
            $persen_jp_penambah = 0;
            $persen_dpp = 0;
            $persen_jp_pengurang = 0;
            $batas_atas = 0;
            $batas_bawah = 0;
            $jp_jan_feb = 0;
            $jp_mar_des = 0;
        }else{
            $persen_jkk = $hitungan_penambah->jkk;
            $persen_jht = $hitungan_penambah->jht;
            $persen_jkm = $hitungan_penambah->jkm;
            $persen_kesehatan = $hitungan_penambah->kesehatan;
            $persen_jp_penambah = $hitungan_penambah->jp;
            $persen_dpp = $hitungan_pengurang->dpp;
            $persen_jp_pengurang = $hitungan_pengurang->jp;
            $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
            $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
            $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
            $jp_mar_des = $hitungan_pengurang->jp_mar_des;
        }

        $karyawan = KaryawanModel::with([
                                'allGajiByKaryawan' => function($query) use ($nip, $year) {
                                    $query->select(
                                        'batch.id AS batch_id',
                                        'batch.status',
                                        'nip',
                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                        'gj_pokok',
                                        'tj_keluarga',
                                        'tj_kesejahteraan',
                                        'gj_penyesuaian',
                                        'tj_telepon',
                                        'tj_jabatan',
                                        'tj_teller',
                                        'tj_perumahan',
                                        'tj_kemahalan',
                                        'tj_pelaksana',
                                        'tj_multilevel',
                                        'tj_ti',
                                        'tj_transport',
                                        'tj_pulsa',
                                        'tj_vitamin',
                                        'uang_makan',
                                        'dpp',
                                        'bpjs_tk',
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_ti + tj_multilevel + tj_fungsional) AS total_gaji"),
                                        DB::raw("(uang_makan + tj_vitamin + tj_pulsa + tj_transport) AS total_tunjangan_lainnya"),
                                        'kredit_koperasi',
                                        'iuran_koperasi',
                                        'kredit_pegawai',
                                        'iuran_ik',
                                    )
                                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                    ->where('gaji_per_bulan.nip', $nip)
                                    ->where('gaji_per_bulan.tahun', $year)
                                    ->where('batch.status', 'final')
                                    ->whereNull('batch.deleted_at')
                                    ->orderBy('bulan')
                                    ->groupBy('gaji_per_bulan.bulan');
                                },
                                'keluarga' => function($query) {
                                    $query->select(
                                        'nip',
                                        'enum',
                                        'nama AS nama_keluarga',
                                        'jml_anak',
                                        DB::raw("
                                            IF(jml_anak > 3,
                                                'K/3',
                                                IF(jml_anak IS NOT NULL, CONCAT('K/', jml_anak), 'K/0')) AS status_kawin
                                        ")
                                    )
                                    ->whereIn('enum', ['Suami', 'Istri']);
                                },
                                'gaji' => function($query) use ($year) {
                                    $query->select(
                                        'nip',
                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                        'gj_pokok',
                                        'gj_penyesuaian',
                                        'tj_keluarga',
                                        'tj_telepon',
                                        'tj_jabatan',
                                        'tj_teller',
                                        'tj_perumahan',
                                        'tj_kemahalan',
                                        'tj_pelaksana',
                                        'tj_kesejahteraan',
                                        'tj_multilevel',
                                        'tj_ti',
                                        'tj_transport',
                                        'tj_pulsa',
                                        'tj_vitamin',
                                        'uang_makan',
                                        'dpp',
                                        'jp',
                                        'bpjs_tk',
                                        'penambah_bruto_jamsostek',
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji")
                                    )
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($year) {
                                    $query->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($year) {
                                    $query->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'tidak teratur');
                                },
                                'bonus' => function($query) use ($year) {
                                    $query->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'bonus');
                                },
                                'potonganGaji' => function($query) use ($year) {
                                        $query->select(
                                            'potongan_gaji.nip',
                                            DB::raw('SUM(potongan_gaji.kredit_koperasi) AS kredit_koperasi'),
                                            DB::raw('SUM(potongan_gaji.iuran_koperasi) AS iuran_koperasi'),
                                            DB::raw('SUM(potongan_gaji.kredit_pegawai) AS kredit_pegawai'),
                                            DB::raw('SUM(potongan_gaji.iuran_ik) AS iuran_ik'),
                                            DB::raw('(SUM(potongan_gaji.kredit_koperasi) + SUM(potongan_gaji.iuran_koperasi) + SUM(potongan_gaji.kredit_pegawai) + SUM(potongan_gaji.iuran_ik)) AS total_potongan'),
                                        )
                                        ->sum('potongan_gaji.kredit_koperasi');
                                }
                            ])
                            ->select(
                                'mst_karyawan.nip',
                                'nama_karyawan',
                                'npwp',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
                                'status_jabatan',
                                'tanggal_pengangkat',
                                DB::raw("
                                    IF(
                                        mst_karyawan.status = 'Kawin',
                                        'K',
                                        IF(
                                            mst_karyawan.status = 'Belum Kawin',
                                            'TK/0',
                                            mst_karyawan.status
                                        )
                                    ) AS status
                                "),
                                'status_karyawan',
                            )
                            ->join('gaji_per_bulan AS gaji', 'gaji.nip', 'mst_karyawan.nip')
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
                            ->whereNull('batch.deleted_at')
                            ->where(function($query) use ($year, $nip) {
                                $query->whereRelation('gaji', 'tahun', $year)
                                    ->whereNull('tanggal_penonaktifan')
                                    ->where('mst_karyawan.nip', $nip);
                            })
                            ->where('mst_karyawan.nip', $nip)
                            ->first();

        if ($karyawan) {
            $ptkp = null;
            if ($karyawan->keluarga) {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', $karyawan->keluarga->status_kawin)
                                ->first();
            }
            $karyawan->ptkp = $ptkp;

            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $total_gaji = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa
            $obj_gaji = null;
            $gaji = 0;
            $total_gaji = 0;

            if ($karyawan->allGajiByKaryawan) {
                foreach ($karyawan->allGajiByKaryawan as $key => $value) {
                    $batch = DB::table('batch_gaji_per_bulan')->find($value->batch_id);
                    if ($batch) {
                        $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                            ->where('mst_profil_kantor.kd_cabang', $batch->kd_entitas)
                            ->where('active', 1)
                            ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                            ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                            ->first();
                        $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                            ->where('kd_cabang', $batch->kd_entitas)
                            ->where('active', 1)
                            ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                            ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                            ->first();
                    }
                    if (!$hitungan_penambah && !$hitungan_pengurang) {
                        $persen_jkk = 0;
                        $persen_jht = 0;
                        $persen_jkm = 0;
                        $persen_kesehatan = 0;
                        $persen_jp_penambah = 0;
                        $persen_dpp = 0;
                        $persen_jp_pengurang = 0;
                        $batas_atas = 0;
                        $batas_bawah = 0;
                        $jp_jan_feb = 0;
                        $jp_mar_des = 0;
                    }else{
                        $persen_jkk = $hitungan_penambah->jkk;
                        $persen_jht = $hitungan_penambah->jht;
                        $persen_jkm = $hitungan_penambah->jkm;
                        $persen_kesehatan = $hitungan_penambah->kesehatan;
                        $persen_jp_penambah = $hitungan_penambah->jp;
                        $persen_dpp = $hitungan_pengurang->dpp;
                        $persen_jp_pengurang = $hitungan_pengurang->jp;
                        $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
                        $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
                        $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
                        $jp_mar_des = $hitungan_pengurang->jp_mar_des;
                    }

                    $potongan = new \stdClass();
                    // Get BPJS TK * Kesehatan
                    $obj_gaji = $value;
                    $gaji = $obj_gaji->gaji;
                    $total_gaji = $obj_gaji->total_gaji;

                    $jamsostek = $obj_gaji->penambah_bruto_jamsostek;

                    // Get Potongan(JP1%, DPP 5%)
                    $nominal_jp = ($obj_gaji->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                    $gj_pokok = $obj_gaji->gj_pokok;
                    $tj_keluarga = $obj_gaji->tj_keluarga;
                    $tj_kesejahteraan = $obj_gaji->tj_kesejahteraan;

                    $potongan->dpp = $obj_gaji->dpp;
                    $potongan->jp_1_persen = $obj_gaji->jp;

                    // Get BPJS TK
                    $bpjs_tk = $obj_gaji->bpjs_tk;

                    // Penghasilan rutin
                    $penghasilan_rutin = $gaji + $jamsostek;
                    $value->jamsostek = $jamsostek;
                    $value->bpjs_tk = $bpjs_tk;
                    $value->bpjs_kesehatan = $bpjs_kesehatan;
                    $value->potongan = $potongan;
                }
            }

            // Get total penghasilan tidak teratur
            if ($karyawan->tunjanganTidakTetap) {
                $tunjangan_tidak_tetap = $karyawan->tunjanganTidakTetap;
                foreach ($tunjangan_tidak_tetap as $key => $value) {
                    $penghasilan_tidak_teratur += $value->pivot->nominal;
                }
            }

            // Get total bonus
            if ($karyawan->bonus) {
                $bonus_item = $karyawan->bonus;
                foreach ($bonus_item as $key => $value) {
                    $bonus += $value->pivot->nominal;
                }
            }

            // Penghasilan tidak rutin
            $penghasilan_tidak_rutin = $penghasilan_tidak_teratur + $bonus;

            // Get total potongan
            if ($karyawan->potonganGaji) {
                $total_potongan += $karyawan->potonganGaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp;
            }

            $total_potongan += $bpjs_tk;
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $gaji - $total_potongan;
            $karyawan->total_yg_diterima = $total_yg_diterima;

            $karyawan->penghasilan_rutin = $penghasilan_rutin;
            $karyawan->penghasilan_tidak_rutin = $penghasilan_tidak_rutin;

            // Get Penghasilan bruto
            $month_on_year = 12;
            $month_paided_arr = [];
            $month_on_year_paid = 0;
            $penghasilanBruto = new \stdClass();
            $penguranganPenghasilan = new \stdClass();
            $pph_dilunasi = 0;

            $karyawan_bruto = KaryawanModel::with([
                                                'allGajiByKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        'gj_pokok',
                                                        'tj_keluarga',
                                                        'tj_kesejahteraan',
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                                        DB::raw("(uang_makan + tj_vitamin + tj_pulsa + tj_transport) AS total_tunjangan_lainnya"),
                                                    )
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('tahun', $year)
                                                    ->orderBy('bulan')
                                                    ->groupBy('bulan');
                                                },
                                                'sumBonusKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'penghasilan_tidak_teratur.nip',
                                                        'mst_tunjangan.kategori',
                                                        DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                    )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'bonus')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'sumTunjanganTidakTetapKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                            'penghasilan_tidak_teratur.nip',
                                                            'mst_tunjangan.kategori',
                                                            DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                        )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'pphDilunasi' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                                        DB::raw('SUM(total_pph) AS nominal'),
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('nip', $karyawan->nip)
                                                    ->groupBy('bulan');
                                                }
                                            ])
                                            ->select(
                                                'nip',
                                                'nama_karyawan',
                                                'no_rekening',
                                                'tanggal_penonaktifan',
                                                'kpj',
                                                'jkn',
                                                'status_karyawan',
                                            )
                                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                                            ->where(function($query) use ($karyawan) {
                                                $query->whereRelation('allGajiByKaryawan', 'nip', $karyawan->nip);
                                            })
                                            ->first();

            if ($karyawan_bruto) {
                $gaji_bruto = 0;
                $tunjangan_lainnya_bruto = 0;
                // Get jamsostek
                if ($karyawan_bruto->allGajiByKaryawan) {
                    $allGajiByKaryawan = $karyawan_bruto->allGajiByKaryawan;
                    $total_gaji_bruto = 0;
                    $total_jamsostek = 0;
                    $total_pengurang_bruto = 0;
                    foreach ($allGajiByKaryawan as $key => $value) {
                        array_push($month_paided_arr, intval($value->bulan));
                        $gaji_bruto += $value->total_gaji ? intval($value->total_gaji) : 0;
                        $tunjangan_lainnya_bruto += $value->total_tunjangan_lainnya ? intval($value->total_tunjangan_lainnya) : 0;
                        $total_gaji = $value->total_gaji ? intval($value->total_gaji) : 0;
                        $total_gaji_bruto += $total_gaji;
                        $pengurang_bruto = 0;

                        // Get jamsostek
                        if($total_gaji > 0){
                            $jkk = 0;
                            $jht = 0;
                            $jkm = 0;
                            $jp_penambah = 0;
                            $bpjs_kesehatan = 0;
                            if(!$karyawan_bruto->tanggal_penonaktifan && $karyawan_bruto->kpj){
                                $jkk = round(($persen_jkk / 100) * $total_gaji);
                                $jht = round(($persen_jht / 100) * $total_gaji);
                                $jkm = round(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = round(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = round($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                                $dppBrutoExtra = round(($persen_jp_pengurang / 100) * $total_gaji, 2);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = round($nominal_jp * ($persen_jp_pengurang / 100), 2);
                                } else {
                                    $dppBrutoExtra = round($total_gaji * ($persen_jp_pengurang / 100), 2);
                                }
                            }

                            $pengurang_bruto = intval($dppBruto + $dppBrutoExtra);
                            $total_pengurang_bruto += $pengurang_bruto;
                        }
                        $value->pengurangan_bruto = $pengurang_bruto;
                    }
                    $penguranganPenghasilan->total_pengurangan_bruto = $total_pengurang_bruto;
                    $penghasilanBruto->gaji_pensiun = intval($total_gaji_bruto);
                    $penghasilanBruto->total_jamsostek = intval($total_jamsostek);
                }

                // Get keterangan tunjangan tidak tetap & bonus
                $ketTunjanganTidakTetap = DB::table('penghasilan_tidak_teratur')
                                            ->select('bulan', 'tahun')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', $year)
                                            ->groupBy(['bulan', 'tahun'])
                                            ->pluck('bulan');
                for ($i=0; $i < count($ketTunjanganTidakTetap); $i++) {
                    $i_bulan = $ketTunjanganTidakTetap[$i];
                    if (!in_array($i_bulan, $month_paided_arr)) {
                        array_push($month_paided_arr, intval($i_bulan));
                    }
                }
                $month_on_year_paid = count($month_paided_arr);

                // Get penghasilan tidak teratur
                $total_penghasilan_tidak_teratur_bruto = 0;
                if ($karyawan_bruto->sumTunjanganTidakTetapKaryawan) {
                    $sumTunjanganTidakTetapKaryawan = $karyawan_bruto->sumTunjanganTidakTetapKaryawan;
                    $total_penghasilan_tidak_teratur_bruto = isset($sumTunjanganTidakTetapKaryawan[0]) ? intval($sumTunjanganTidakTetapKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_penghasilan_tidak_teratur = $total_penghasilan_tidak_teratur_bruto;
                }

                // Get total bonus
                $total_bonus_bruto = 0;
                if ($karyawan_bruto->sumBonusKaryawan) {
                    $sumBonusKaryawan = $karyawan_bruto->sumBonusKaryawan;
                    $total_bonus_bruto = isset($sumBonusKaryawan[0]) ? intval($sumBonusKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_bonus = $total_bonus_bruto;
                }

                $penghasilan_rutin_bruto = 0;
                $penghasilan_tidak_rutin_bruto = 0;
                $total_penghasilan = 0; // ( Teratur + Tidak Teratur )

                $penghasilan_rutin_bruto = $gaji_bruto + $tunjangan_lainnya_bruto + $penghasilanBruto->total_jamsostek;
                $penghasilan_tidak_rutin_bruto = $total_penghasilan_tidak_teratur_bruto + $total_bonus_bruto;
                $total_penghasilan = $penghasilan_rutin_bruto + $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->penghasilan_rutin = $penghasilan_rutin_bruto;
                $penghasilanBruto->penghasilan_tidak_rutin = $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->total_penghasilan = $total_penghasilan;
            }

            // Get Tunjangan lainnya (T.Makan, T.Pulsa, T.Transport, T.Vitamin, T.Tidak teratur, Bonus)
            if ($karyawan->tunjangan) {
                $tunjangan = $karyawan->tunjangan;
                foreach ($tunjangan as $key => $value) {
                    $tunjangan_teratur_import += $value->pivot->nominal;
                }
            }
            // $tunjangan_lainnya = $tunjangan_teratur_import + $penghasilan_tidak_rutin;
            $tunjangan_lainnya = $tunjangan_lainnya_bruto;
            $penghasilanBruto->tunjangan_lainnya = $tunjangan_lainnya;

            // Get total penghasilan bruto
            $total_penghasilan_bruto = 0;
            if (property_exists($penghasilanBruto, 'gaji_pensiun')) {
                $total_penghasilan_bruto += $penghasilanBruto->gaji_pensiun;
            }
            if (property_exists($penghasilanBruto, 'total_jamsostek')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_jamsostek;
            }
            if (property_exists($penghasilanBruto, 'total_bonus')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_bonus;
            }
            if (property_exists($penghasilanBruto, 'tunjangan_lainnya')) {
                $total_penghasilan_bruto += $penghasilanBruto->tunjangan_lainnya;
            }
            $penghasilanBruto->total_keseluruhan = $total_penghasilan_bruto;

            /**
             * Pengurang Penghasilan
             * IF(5%*K46>500000*SUM(L18:L29);500000*SUM(L18:L29);5%*K46)
             * K46 = total penghasilan
             * SUM(L18:19) = jumlah bulan telah digaji dalam setahun ($month_on_year_paid)
             */

            $biaya_jabatan = 0;
            $pembanding = 0;
            $biaya_jabatan = 0;
            if (property_exists($penghasilanBruto, 'total_penghasilan')) {
                $pembanding = 500000 * $month_on_year_paid;
                $biaya_jabatan = (0.05 * $penghasilanBruto->total_penghasilan) > $pembanding ? $pembanding : (0.05 * $penghasilanBruto->total_penghasilan);
            }
            $penguranganPenghasilan->biaya_jabatan = $biaya_jabatan;

            $jumlah_pengurangan = $total_pengurang_bruto + $biaya_jabatan;
            $penguranganPenghasilan->jumlah_pengurangan = $jumlah_pengurangan;

            $karyawan->karyawan_bruto = $karyawan_bruto;
            $karyawan->penghasilan_bruto = $penghasilanBruto;
            $karyawan->pengurangan_penghasilan = $penguranganPenghasilan;

            // Perhitungan Pph 21
            $perhitunganPph21 = new \stdClass();

            $total_rutin = $penghasilanBruto->penghasilan_rutin;
            $total_tidak_rutin = $penghasilanBruto->penghasilan_tidak_rutin;
            $bonus_sum = $penghasilanBruto->total_bonus;
            $pengurang = $penguranganPenghasilan->total_pengurangan_bruto;
            $total_ket = $month_on_year_paid;

            // Get jumlah penghasilan neto
            $jumlah_penghasilan = property_exists($penghasilanBruto, 'total_keseluruhan') ? $penghasilanBruto->total_keseluruhan : 0;
            $jumlah_pengurang = property_exists($penguranganPenghasilan, 'jumlah_pengurangan') ? $penguranganPenghasilan->jumlah_pengurangan : 0;
            // $jumlah_penghasilan_neto = $jumlah_penghasilan - $jumlah_pengurang;
            $jumlah_penghasilan_neto = ($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang);
            $perhitunganPph21->jumlah_penghasilan_neto = $jumlah_penghasilan_neto;

            // Get jumlah penghasilan neto masa sebelumnya
            $jumlah_penghasilan_neto_sebelumnya = 0;
            $perhitunganPph21->jumlah_penghasilan_neto_sebelumnya = $jumlah_penghasilan_neto_sebelumnya;

            // Get total penghasilan neto
            $total_penghasilan_neto = ($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang);
            $perhitunganPph21->total_penghasilan_neto = $total_penghasilan_neto;

            // Get jumlah penghasilan neto untuk Pph 21 (Setahun/Disetaunkan)
            if ($month_on_year_paid == 0) {
                $jumlah_penghasilan_neto_pph21 = 0;
            } else {
                $rumus_14 = 0;
                if (0.05 * $penghasilanBruto->total_penghasilan > $pembanding) {
                    $rumus_14 = ceil($pembanding);
                } else{
                    $rumus_14 = ceil(0.05 * $penghasilanBruto->total_penghasilan);
                }

                $jumlah_penghasilan_neto_pph21 = (($total_rutin + $total_tidak_rutin) - $bonus_sum - $pengurang - $biaya_jabatan) / $total_ket * 12 + $bonus_sum + ($biaya_jabatan - $rumus_14);

                $perhitunganPph21->jumlah_penghasilan_neto_pph21 = $jumlah_penghasilan_neto_pph21;
            }

            // Get PTKP
            $nominal_ptkp = 0;
            if ($ptkp) {
                $nominal_ptkp = $ptkp->ptkp_tahun;
            }
            $perhitunganPph21->ptkp = $nominal_ptkp;

            // Get Penghasilan Kena Pajak Setahun/Disetahunkan
            $keluarga = $karyawan->keluarga;
            $status_kawin = 'TK/0';
            if ($keluarga) {
                $status_kawin = $keluarga->status_kawin;
            }
            else {
                $status_kawin = $karyawan->status;
            }

            $penghasilan_kena_pajak_setahun = 0;
            if ($status_kawin == 'Mutasi Keluar') {
                $penghasilan_kena_pajak_setahun = floor(($jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun) / 1000) * 1000;
            } else {
                if (($jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun) <= 0) {
                    $penghasilan_kena_pajak_setahun = 0;
                } else {
                    $penghasilan_kena_pajak_setahun = $jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun;
                }
            }
            $perhitunganPph21->penghasilan_kena_pajak_setahun = $penghasilan_kena_pajak_setahun;

            /**
             * Get PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
             * 1. Create std class object pphPasal21
             * 2. Get persentase perhitungan pph21
             * 3. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
             * 4. PPh Pasal 21 yang telah dipotong Masa Sebelumnya (default 0)
             * 5. PPh Pasal 21 Terutang
             * 6. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi
             * 7. PPh Pasal 21 yang masih harus dibayar
             */

            // 1. Create std class object pphPasal21
            $pphPasal21 = new \stdClass;

            // 2. Get persentase perhitungan pph21
            $persen5 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 0) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 60000000) {
                    $persen5 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000) * 0.05 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000) * 0.06;
                } else {
                    $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
                }
            } else {
                $persen5 = 0;
            }
            $persen15 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 60000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 250000000) {
                    $persen15 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000- 60000000) * 0.18;
                } else {
                    $persen15 = 190000000 * 0.15;
                }
            } else {
                $persen15 = 0;
            }
            $persen25 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 250000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 500000000) {
                    $persen25 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 250000000) * 0.3;
                } else {
                    $persen25 = 250000000 * 0.25;
                }
            } else {
                $persen25 = 0;
            }
            $persen30 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 500000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 5000000000) {
                    $persen30 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 500000000) * 0.36;
                } else {
                    $persen30 = 4500000000 * 0.30;
                }
            } else {
                $persen30 = 0;
            }
            $persen35 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 5000000000) {
                    $persen35 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 5000000000) * 0.35 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 5000000000) * 0.42;
            } else {
                $persen35 = 0;
            }
            $pphPasal21->persen5 = $persen5;
            $pphPasal21->persen15 = $persen15;
            $pphPasal21->persen25 = $persen25;
            $pphPasal21->persen30 = $persen30;
            $pphPasal21->persen35 = $persen35;

            // 3. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
            $penghasilan_kena_pajak_setahun = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;
            $pphPasal21->penghasilan_kena_pajak_setahun = $penghasilan_kena_pajak_setahun;

            // 4. PPh Pasal 21 yang telah dipotong Masa Sebelumnya
            $pph_21_dipotong_masa_sebelumnya = 0;
            $pphPasal21->pph_21_dipotong_masa_sebelumnya = $pph_21_dipotong_masa_sebelumnya;

            // 5. PPh Pasal 21 Terutang
            $pph_21_terutang = floor(($penghasilan_kena_pajak_setahun / 12) * $total_ket);
            $pphPasal21->pph_21_terutang = $pph_21_terutang;

            // 6. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi
            $total_pph_dilunasi = 0;
            if ($karyawan_bruto->pphDilunasi) {
                $pphDilunasi = $karyawan_bruto->pphDilunasi;
                foreach ($pphDilunasi as $key => $value) {
                    $total_pph_dilunasi += intval($value->nominal);
                }
            }
            $pphPasal21->pph_telah_dilunasi = $total_pph_dilunasi;

            // 7. PPh Pasal 21 yang masih harus dibayar
            $pph_harus_dibayar = $pph_21_terutang - $total_pph_dilunasi;
            $pphPasal21->pph_harus_dibayar = $pph_harus_dibayar;

            $perhitunganPph21->pph_pasal_21 = $pphPasal21;
            $karyawan->perhitungan_pph21 = $perhitunganPph21;
        }

        // dd($karyawan);
        return $karyawan;
    }

    public function getSlipCetak($nip, $month, $year) {
        /**
         * PPH 21
         * Gaji - done
         * Tunjangan Tetap - done
         * Tunjangan Tidak Tetap - done
         * BPJS TK - done
         * BPJS Kesehatan - done
         * Tambahan Penghasilan(bonus) - done
         * Potongan (JP1%, DPP 5%, Kredit Koperasi, Iuran Koperasi, Kredit Pegawai, Iuran IK) - done
         */ /**
          * Filter
          * Kantor(Pusat/Cabang)
          * Bulan
          * Tahun
          */

        /**
         * Table
         * gaji = gaji_perbulan
         * tunjangan tetap = tunjangan_karyawan
         * tunjangan tidak tetap = penghasilan_tidak_teratur
         * bpjs tk = kpj (jamsostek)
         * bpjs kesehatan = jkn
         */

        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        $kode_cabang_str = json_encode($kode_cabang_arr);
        $kode_cabang_str = str_replace('[', '(', $kode_cabang_str);
        $kode_cabang_str = str_replace(']', ')', $kode_cabang_str);

        $karyawan = KaryawanModel::with([
                                'keluarga' => function($query) {
                                    $query->select(
                                        'nip',
                                        'enum',
                                        'nama AS nama_keluarga',
                                        'jml_anak',
                                        DB::raw("
                                            IF(jml_anak > 3,
                                                'K/3',
                                                IF(jml_anak IS NOT NULL, CONCAT('K/', jml_anak), 'K/0')) AS status_kawin
                                        ")
                                    )
                                    ->whereIn('enum', ['Suami', 'Istri']);
                                },
                                'gaji' => function($query) use ($month, $year) {
                                    $query->select(
                                        'nip',
                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                        'gj_pokok',
                                        'gj_penyesuaian',
                                        'tj_keluarga',
                                        'tj_telepon',
                                        'tj_jabatan',
                                        'tj_teller',
                                        'tj_perumahan',
                                        'tj_kemahalan',
                                        'tj_pelaksana',
                                        'tj_kesejahteraan',
                                        'tj_multilevel',
                                        'tj_ti',
                                        'tj_fungsional',
                                        DB::raw('(tj_ti + tj_multilevel + tj_fungsional) AS tj_khusus'),
                                        'tj_transport',
                                        'tj_pulsa',
                                        'tj_vitamin',
                                        'uang_makan',
                                        'dpp',
                                        'jp',
                                        'bpjs_tk',
                                        'penambah_bruto_jamsostek',
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_ti + tj_multilevel + tj_fungsional) AS total_gaji"),
                                        'kredit_koperasi',
                                        'iuran_koperasi',
                                        'kredit_pegawai',
                                        'iuran_ik',
                                        DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS total_potongan')
                                    )
                                    ->where('bulan', $month)
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'tidak teratur');
                                },
                                'bonus' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'bonus');
                                },
                                'potonganGaji' => function($query) use ($month, $year) {
                                    $query->select(
                                        'potongan_gaji.nip',
                                        DB::raw('potongan_gaji.kredit_koperasi AS kredit_koperasi'),
                                        DB::raw('potongan_gaji.iuran_koperasi AS iuran_koperasi'),
                                        DB::raw('potongan_gaji.kredit_pegawai AS kredit_pegawai'),
                                        DB::raw('potongan_gaji.iuran_ik AS iuran_ik'),
                                        DB::raw('(potongan_gaji.kredit_koperasi + potongan_gaji.iuran_koperasi + potongan_gaji.kredit_pegawai + potongan_gaji.iuran_ik) AS total_potongan'),
                                    );
                                }
                            ])
                            ->select(
                                'batch.id AS batch_id',
                                'batch.status AS batch_status',
                                'mst_karyawan.nip',
                                'nama_karyawan',
                                'npwp',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
                                'status_jabatan',
                                'tanggal_pengangkat',
                                DB::raw("
                                    IF(
                                        mst_karyawan.status = 'Kawin',
                                        'K',
                                        IF(
                                            mst_karyawan.status = 'Belum Kawin',
                                            'TK/0',
                                            mst_karyawan.status
                                        )
                                    ) AS status
                                "),
                                'status_karyawan',
                            )
                            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'mst_karyawan.kd_entitas')
                            ->where(function($query) use ($month, $year, $nip) {
                                $query->whereRelation('gaji', 'bulan', $month)
                                ->whereRelation('gaji', 'tahun', $year)
                                ->whereNull('tanggal_penonaktifan')
                                ->where('mst_karyawan.nip', $nip);
                            })
                            ->first();

        if ($karyawan) {
            $batch = DB::table('batch_gaji_per_bulan')->find($karyawan->batch_id);
            if ($batch) {
                $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                    ->where('mst_profil_kantor.kd_cabang', $batch->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                    ->first();
                $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                    ->where('kd_cabang', $batch->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                    ->first();
            }

            if (!$hitungan_penambah && !$hitungan_pengurang) {
                $persen_jkk = 0;
                $persen_jht = 0;
                $persen_jkm = 0;
                $persen_kesehatan = 0;
                $persen_jp_penambah = 0;
                $persen_dpp = 0;
                $persen_jp_pengurang = 0;
                $batas_atas = 0;
                $batas_bawah = 0;
                $jp_jan_feb = 0;
                $jp_mar_des = 0;
            }else{
                $persen_jkk = $hitungan_penambah->jkk;
                $persen_jht = $hitungan_penambah->jht;
                $persen_jkm = $hitungan_penambah->jkm;
                $persen_kesehatan = $hitungan_penambah->kesehatan;
                $persen_jp_penambah = $hitungan_penambah->jp;
                $persen_dpp = $hitungan_pengurang->dpp;
                $persen_jp_pengurang = $hitungan_pengurang->jp;
                $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
                $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
                $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
                $jp_mar_des = $hitungan_pengurang->jp_mar_des;
            }
            $ptkp = null;
            if ($karyawan->keluarga) {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', $karyawan->keluarga->status_kawin)
                                ->first();
            }
            $karyawan->ptkp = $ptkp;

            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $potongan = new \stdClass();
            $total_gaji = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa

            if ($karyawan->gaji) {
                // Get BPJS TK * Kesehatan
                $obj_gaji = $karyawan->gaji;
                $gaji = $obj_gaji->gaji;
                $total_gaji = $obj_gaji->total_gaji;

                $jamsostek = $obj_gaji->penambah_bruto_jamsostek;

                // Get Potongan(JP1%, DPP 5%)
                $nominal_jp = ($obj_gaji->bulan > 2) ? $jp_mar_des : $jp_jan_feb;

                $gj_pokok = $obj_gaji->gj_pokok;
                $tj_keluarga = $obj_gaji->tj_keluarga;
                $tj_kesejahteraan = $obj_gaji->tj_kesejahteraan;

                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                $potongan->dpp = $obj_gaji->dpp;
                $potongan->jp_1_persen = $obj_gaji->jp;

                // Get BPJS TK
                $bpjs_tk = $obj_gaji->bpjs_tk;

                // Penghasilan rutin
                $penghasilan_rutin = $gaji + $jamsostek;
            }

            $karyawan->jamsostek = $jamsostek;
            $karyawan->bpjs_tk = $bpjs_tk;
            $karyawan->bpjs_kesehatan = $bpjs_kesehatan;
            $karyawan->potongan = $potongan;

            // Get total penghasilan tidak teratur
            if ($karyawan->tunjanganTidakTetap) {
                $tunjangan_tidak_tetap = $karyawan->tunjanganTidakTetap;
                foreach ($tunjangan_tidak_tetap as $key => $value) {
                    $penghasilan_tidak_teratur += $value->pivot->nominal;
                }
            }

            // Get total bonus
            if ($karyawan->bonus) {
                $bonus_item = $karyawan->bonus;
                foreach ($bonus_item as $key => $value) {
                    $bonus += $value->pivot->nominal;
                }
            }

            // Penghasilan tidak rutin
            $penghasilan_tidak_rutin = $penghasilan_tidak_teratur + $bonus;

            // Get total potongan
            if ($karyawan->gaji) {
                $total_potongan += $karyawan->gaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp;
            }

            $total_potongan += $bpjs_tk;
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $gaji - $total_potongan;
            $karyawan->total_yg_diterima = $total_yg_diterima;

            $karyawan->penghasilan_rutin = $penghasilan_rutin;
            $karyawan->penghasilan_tidak_rutin = $penghasilan_tidak_rutin;

            // Get Penghasilan bruto
            $month_on_year = 12;
            $month_paided_arr = [];
            $month_on_year_paid = 0;
            $penghasilanBruto = new \stdClass();
            $penguranganPenghasilan = new \stdClass();
            $pph_dilunasi = 0;

            $karyawan_bruto = KaryawanModel::with([
                                                'allGajiByKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        'gj_pokok',
                                                        'tj_keluarga',
                                                        'tj_kesejahteraan',
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                                        DB::raw("(uang_makan + tj_vitamin + tj_pulsa + tj_transport) AS total_tunjangan_lainnya"),
                                                    )
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('tahun', $year)
                                                    ->orderBy('bulan')
                                                    ->groupBy('bulan');
                                                },
                                                'sumBonusKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'penghasilan_tidak_teratur.nip',
                                                        'mst_tunjangan.kategori',
                                                        DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                    )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'bonus')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'sumTunjanganTidakTetapKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                            'penghasilan_tidak_teratur.nip',
                                                            'mst_tunjangan.kategori',
                                                            DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                        )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'pphDilunasi' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                                        DB::raw('SUM(total_pph) AS nominal'),
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('nip', $karyawan->nip)
                                                    ->groupBy('bulan');
                                                }
                                            ])
                                            ->select(
                                                'nip',
                                                'nama_karyawan',
                                                'no_rekening',
                                                'tanggal_penonaktifan',
                                                'kpj',
                                                'jkn',
                                                'status_karyawan',
                                            )
                                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                                            ->where(function($query) use ($karyawan) {
                                                $query->whereRelation('allGajiByKaryawan', 'nip', $karyawan->nip);
                                            })
                                            ->first();

            if ($karyawan_bruto) {
                $gaji_bruto = 0;
                $tunjangan_lainnya_bruto = 0;
                // Get jamsostek
                if ($karyawan_bruto->allGajiByKaryawan) {
                    $allGajiByKaryawan = $karyawan_bruto->allGajiByKaryawan;
                    $total_gaji_bruto = 0;
                    $total_jamsostek = 0;
                    $total_pengurang_bruto = 0;
                    foreach ($allGajiByKaryawan as $key => $value) {
                        array_push($month_paided_arr, intval($value->bulan));
                        $gaji_bruto += $value->total_gaji ? intval($value->total_gaji) : 0;
                        $tunjangan_lainnya_bruto += $value->total_tunjangan_lainnya ? intval($value->total_tunjangan_lainnya) : 0;
                        $total_gaji = $value->total_gaji ? intval($value->total_gaji) : 0;
                        $total_gaji_bruto += $total_gaji;
                        $pengurang_bruto = 0;

                        // Get jamsostek
                        if($total_gaji > 0){
                            $jkk = 0;
                            $jht = 0;
                            $jkm = 0;
                            $jp_penambah = 0;
                            $bpjs_kesehatan = 0;
                            if(!$karyawan_bruto->tanggal_penonaktifan && $karyawan_bruto->kpj){
                                $jkk = round(($persen_jkk / 100) * $total_gaji);
                                $jht = round(($persen_jht / 100) * $total_gaji);
                                $jkm = round(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = round(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = round($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                                $dppBrutoExtra = round(($persen_jp_pengurang / 100) * $total_gaji, 2);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = round($nominal_jp * ($persen_jp_pengurang / 100), 2);
                                } else {
                                    $dppBrutoExtra = round($total_gaji * ($persen_jp_pengurang / 100), 2);
                                }
                            }

                            $pengurang_bruto = intval($dppBruto + $dppBrutoExtra);
                            $total_pengurang_bruto += $pengurang_bruto;
                        }
                        $value->pengurangan_bruto = $pengurang_bruto;
                    }
                    $penguranganPenghasilan->total_pengurangan_bruto = $total_pengurang_bruto;
                    $penghasilanBruto->gaji_pensiun = intval($total_gaji_bruto);
                    $penghasilanBruto->total_jamsostek = intval($total_jamsostek);
                }

                // Get keterangan tunjangan tidak tetap & bonus
                $ketTunjanganTidakTetap = DB::table('penghasilan_tidak_teratur')
                                            ->select('bulan', 'tahun')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', $year)
                                            ->groupBy(['bulan', 'tahun'])
                                            ->pluck('bulan');
                for ($i=0; $i < count($ketTunjanganTidakTetap); $i++) {
                    $i_bulan = $ketTunjanganTidakTetap[$i];
                    if (!in_array($i_bulan, $month_paided_arr)) {
                        array_push($month_paided_arr, intval($i_bulan));
                    }
                }
                $month_on_year_paid = count($month_paided_arr);

                // Get penghasilan tidak teratur
                $total_penghasilan_tidak_teratur_bruto = 0;
                if ($karyawan_bruto->sumTunjanganTidakTetapKaryawan) {
                    $sumTunjanganTidakTetapKaryawan = $karyawan_bruto->sumTunjanganTidakTetapKaryawan;
                    $total_penghasilan_tidak_teratur_bruto = isset($sumTunjanganTidakTetapKaryawan[0]) ? intval($sumTunjanganTidakTetapKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_penghasilan_tidak_teratur = $total_penghasilan_tidak_teratur_bruto;
                }

                // Get total bonus
                $total_bonus_bruto = 0;
                if ($karyawan_bruto->sumBonusKaryawan) {
                    $sumBonusKaryawan = $karyawan_bruto->sumBonusKaryawan;
                    $total_bonus_bruto = isset($sumBonusKaryawan[0]) ? intval($sumBonusKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_bonus = $total_bonus_bruto;
                }

                $penghasilan_rutin_bruto = 0;
                $penghasilan_tidak_rutin_bruto = 0;
                $total_penghasilan = 0; // ( Teratur + Tidak Teratur )

                $penghasilan_rutin_bruto = $gaji_bruto + $tunjangan_lainnya_bruto + $penghasilanBruto->total_jamsostek;
                $penghasilan_tidak_rutin_bruto = $total_penghasilan_tidak_teratur_bruto + $total_bonus_bruto;
                $total_penghasilan = $penghasilan_rutin_bruto + $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->penghasilan_rutin = $penghasilan_rutin_bruto;
                $penghasilanBruto->penghasilan_tidak_rutin = $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->total_penghasilan = $total_penghasilan;
            }

            // Get Tunjangan lainnya (T.Makan, T.Pulsa, T.Transport, T.Vitamin, T.Tidak teratur, Bonus)
            if ($karyawan->tunjangan) {
                $tunjangan = $karyawan->tunjangan;
                foreach ($tunjangan as $key => $value) {
                    $tunjangan_teratur_import += $value->pivot->nominal;
                }
            }
            // $tunjangan_lainnya = $tunjangan_teratur_import + $penghasilan_tidak_rutin;
            $tunjangan_lainnya = $tunjangan_lainnya_bruto;
            $penghasilanBruto->tunjangan_lainnya = $tunjangan_lainnya;

            // Get total penghasilan bruto
            $total_penghasilan_bruto = 0;
            if (property_exists($penghasilanBruto, 'gaji_pensiun')) {
                $total_penghasilan_bruto += $penghasilanBruto->gaji_pensiun;
            }
            if (property_exists($penghasilanBruto, 'total_jamsostek')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_jamsostek;
            }
            if (property_exists($penghasilanBruto, 'total_bonus')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_bonus;
            }
            if (property_exists($penghasilanBruto, 'tunjangan_lainnya')) {
                $total_penghasilan_bruto += $penghasilanBruto->tunjangan_lainnya;
            }
            $penghasilanBruto->total_keseluruhan = $total_penghasilan_bruto;

            /**
             * Pengurang Penghasilan
             * IF(5%*K46>500000*SUM(L18:L29);500000*SUM(L18:L29);5%*K46)
             * K46 = total penghasilan
             * SUM(L18:19) = jumlah bulan telah digaji dalam setahun ($month_on_year_paid)
             */

            $biaya_jabatan = 0;
            $pembanding = 0;
            $biaya_jabatan = 0;
            if (property_exists($penghasilanBruto, 'total_penghasilan')) {
                $pembanding = 500000 * $month_on_year_paid;
                $biaya_jabatan = (0.05 * $penghasilanBruto->total_penghasilan) > $pembanding ? $pembanding : (0.05 * $penghasilanBruto->total_penghasilan);
            }
            $penguranganPenghasilan->biaya_jabatan = $biaya_jabatan;

            $jumlah_pengurangan = $total_pengurang_bruto + $biaya_jabatan;
            $penguranganPenghasilan->jumlah_pengurangan = $jumlah_pengurangan;

            $karyawan->karyawan_bruto = $karyawan_bruto;
            $karyawan->penghasilan_bruto = $penghasilanBruto;
            $karyawan->pengurangan_penghasilan = $penguranganPenghasilan;

            // Perhitungan Pph 21
            $perhitunganPph21 = new \stdClass();

            $total_rutin = $penghasilanBruto->penghasilan_rutin;
            $total_tidak_rutin = $penghasilanBruto->penghasilan_tidak_rutin;
            $bonus_sum = $penghasilanBruto->total_bonus;
            $pengurang = $penguranganPenghasilan->total_pengurangan_bruto;
            $total_ket = $month_on_year_paid;

            // Get jumlah penghasilan neto
            $jumlah_penghasilan = property_exists($penghasilanBruto, 'total_keseluruhan') ? $penghasilanBruto->total_keseluruhan : 0;
            $jumlah_pengurang = property_exists($penguranganPenghasilan, 'jumlah_pengurangan') ? $penguranganPenghasilan->jumlah_pengurangan : 0;
            // $jumlah_penghasilan_neto = $jumlah_penghasilan - $jumlah_pengurang;
            $jumlah_penghasilan_neto = ($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang);
            $perhitunganPph21->jumlah_penghasilan_neto = $jumlah_penghasilan_neto;

            // Get jumlah penghasilan neto masa sebelumnya
            $jumlah_penghasilan_neto_sebelumnya = 0;
            $perhitunganPph21->jumlah_penghasilan_neto_sebelumnya = $jumlah_penghasilan_neto_sebelumnya;

            // Get total penghasilan neto
            $total_penghasilan_neto = ($total_rutin + $total_tidak_rutin) - ($biaya_jabatan + $pengurang);
            $perhitunganPph21->total_penghasilan_neto = $total_penghasilan_neto;

            // Get jumlah penghasilan neto untuk Pph 21 (Setahun/Disetaunkan)
            if ($month_on_year_paid == 0) {
                $jumlah_penghasilan_neto_pph21 = 0;
            } else {
                $rumus_14 = 0;
                if (0.05 * $penghasilanBruto->total_penghasilan > $pembanding) {
                    $rumus_14 = ceil($pembanding);
                } else{
                    $rumus_14 = ceil(0.05 * $penghasilanBruto->total_penghasilan);
                }

                $jumlah_penghasilan_neto_pph21 = (($total_rutin + $total_tidak_rutin) - $bonus_sum - $pengurang - $biaya_jabatan) / $total_ket * 12 + $bonus_sum + ($biaya_jabatan - $rumus_14);

                $perhitunganPph21->jumlah_penghasilan_neto_pph21 = $jumlah_penghasilan_neto_pph21;
            }

            // Get PTKP
            $nominal_ptkp = 0;
            if ($ptkp) {
                $nominal_ptkp = $ptkp->ptkp_tahun;
            }
            $perhitunganPph21->ptkp = $nominal_ptkp;

            // Get Penghasilan Kena Pajak Setahun/Disetahunkan
            $keluarga = $karyawan->keluarga;
            $status_kawin = 'TK/0';
            if ($keluarga) {
                $status_kawin = $keluarga->status_kawin;
            }
            else {
                $status_kawin = $karyawan->status;
            }

            $penghasilan_kena_pajak_setahun = 0;
            if ($status_kawin == 'Mutasi Keluar') {
                $penghasilan_kena_pajak_setahun = floor(($jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun) / 1000) * 1000;
            } else {
                if (($jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun) <= 0) {
                    $penghasilan_kena_pajak_setahun = 0;
                } else {
                    $penghasilan_kena_pajak_setahun = $jumlah_penghasilan_neto_pph21 - $ptkp?->ptkp_tahun;
                }
            }
            $perhitunganPph21->penghasilan_kena_pajak_setahun = $penghasilan_kena_pajak_setahun;

            /**
             * Get PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
             * 1. Create std class object pphPasal21
             * 2. Get persentase perhitungan pph21
             * 3. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
             * 4. PPh Pasal 21 yang telah dipotong Masa Sebelumnya (default 0)
             * 5. PPh Pasal 21 Terutang
             * 6. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi
             * 7. PPh Pasal 21 yang masih harus dibayar
             */

            // 1. Create std class object pphPasal21
            $pphPasal21 = new \stdClass;

            // 2. Get persentase perhitungan pph21
            $persen5 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 0) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 60000000) {
                    $persen5 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000) * 0.05 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000) * 0.06;
                } else {
                    $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
                }
            } else {
                $persen5 = 0;
            }
            $persen15 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 60000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 250000000) {
                    $persen15 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000- 60000000) * 0.18;
                } else {
                    $persen15 = 190000000 * 0.15;
                }
            } else {
                $persen15 = 0;
            }
            $persen25 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 250000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 500000000) {
                    $persen25 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 250000000) * 0.3;
                } else {
                    $persen25 = 250000000 * 0.25;
                }
            } else {
                $persen25 = 0;
            }
            $persen30 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 500000000) {
                if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) <= 5000000000) {
                    $persen30 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 500000000) * 0.36;
                } else {
                    $persen30 = 4500000000 * 0.30;
                }
            } else {
                $persen30 = 0;
            }
            $persen35 = 0;
            if (($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) > 5000000000) {
                    $persen35 = ($karyawan->npwp != null) ? (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 5000000000) * 0.35 :  (floor(($jumlah_penghasilan_neto_pph21 - $nominal_ptkp) / 1000) * 1000 - 5000000000) * 0.42;
            } else {
                $persen35 = 0;
            }
            $pphPasal21->persen5 = $persen5;
            $pphPasal21->persen15 = $persen15;
            $pphPasal21->persen25 = $persen25;
            $pphPasal21->persen30 = $persen30;
            $pphPasal21->persen35 = $persen35;

            // 3. PPh Pasal 21 atas Penghasilan Kena Pajak Setahun/Disetahunkan
            $penghasilan_kena_pajak_setahun = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;
            $pphPasal21->penghasilan_kena_pajak_setahun = $penghasilan_kena_pajak_setahun;

            // 4. PPh Pasal 21 yang telah dipotong Masa Sebelumnya
            $pph_21_dipotong_masa_sebelumnya = 0;
            $pphPasal21->pph_21_dipotong_masa_sebelumnya = $pph_21_dipotong_masa_sebelumnya;

            // 5. PPh Pasal 21 Terutang
            $pph_21_terutang = floor(($penghasilan_kena_pajak_setahun / 12) * $total_ket);
            $pphPasal21->pph_21_terutang = $pph_21_terutang;

            // 6. PPh Pasal 21 dan PPh Pasal 26 yang telah dipotong/dilunasi
            $total_pph_dilunasi = 0;
            if ($karyawan_bruto->pphDilunasi) {
                $pphDilunasi = $karyawan_bruto->pphDilunasi;
                foreach ($pphDilunasi as $key => $value) {
                    $total_pph_dilunasi += intval($value->nominal);
                }
            }
            $pphPasal21->pph_telah_dilunasi = $total_pph_dilunasi;

            // 7. PPh Pasal 21 yang masih harus dibayar
            $pph_harus_dibayar = $pph_21_terutang - $total_pph_dilunasi;
            $pphPasal21->pph_harus_dibayar = $pph_harus_dibayar;

            $perhitunganPph21->pph_pasal_21 = $pphPasal21;
            $karyawan->perhitungan_pph21 = $perhitunganPph21;
        }
        return $karyawan;
    }
}
