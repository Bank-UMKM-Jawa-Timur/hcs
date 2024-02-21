<?php

namespace App\Repository;

use App\Helpers\HitungPPH;
use App\Models\KaryawanModel;
use App\Models\PtkpModel;
use Illuminate\Support\Facades\DB;
use App\Repository\CabangRepository;

use function PHPSTORM_META\map;

class PayrollRepository
{
    private $orderRaw;
    public function __construct() {
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

    public function get($kantor, $month, $year, $search, $page=1, $limit=10, $cetak) {
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

        if($kantor == 'pusat'){
            $kd_entitas = '000';
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
            $kd_entitas = $kantor;
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

        // Get last penggajian
        $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear($kd_entitas);
        $last_month_penggajian = 0;
        if ($last_date_penggajian) {
            $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
        }

        $data = KaryawanModel::with([
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
                                        'gaji_per_bulan.id',
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
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                        DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                                        'kredit_koperasi',
                                        'iuran_koperasi',
                                        'kredit_pegawai',
                                        'iuran_ik',
                                        DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS total_potongan'),
                                    )
                                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                    ->whereNull('batch.deleted_at')
                                    ->where('bulan', $month)
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                                },
                                'bonus' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'bonus')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
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
                                'mst_karyawan.nip',
                                'mst_karyawan.kd_entitas',
                                'mst_karyawan.gj_pokok',
                                'mst_karyawan.gj_penyesuaian',
                                'nama_karyawan',
                                'npwp',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
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
                                'mst_karyawan.status_karyawan',
                                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"),
                                'batch.tanggal_input'
                            )
                            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
                            ->orderByRaw("
                                CASE WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 1
                                WHEN mst_karyawan.kd_jabatan='PSD' THEN 2
                                WHEN mst_karyawan.kd_jabatan='PC' THEN 3
                                WHEN mst_karyawan.kd_jabatan='PBP' THEN 4
                                WHEN mst_karyawan.kd_jabatan='PBO' THEN 5
                                WHEN mst_karyawan.kd_jabatan='PEN' THEN 6
                                WHEN mst_karyawan.kd_jabatan='ST' THEN 7
                                WHEN mst_karyawan.kd_jabatan='NST' THEN 8
                                WHEN mst_karyawan.kd_jabatan='IKJP' THEN 9 END ASC
                            ")
                            ->orderByRaw($this->orderRaw)
                            ->orderBy('status_kantor', 'asc')
                            ->orderBy('kd_cabang', 'asc')
                            ->orderBy('nip', 'asc')
                            ->orderBy('mst_karyawan.kd_entitas')
                            ->whereNull('batch.deleted_at')
                            ->where(function($query) use ($month, $year, $kantor, $kode_cabang_arr, $search, $last_month_penggajian) {
                                $query->whereRelation('gaji', 'bulan', $month)
                                ->whereRelation('gaji', 'tahun', $year)
                                ->whereRaw("(tanggal_penonaktifan IS NULL OR ($month = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                                ->where(function($q) use ($kantor, $kode_cabang_arr, $search) {
                                    if ($kantor == 'pusat') {
                                        $q->where(function($q2) use($kode_cabang_arr) {
                                            $q2->whereNotIn('batch.kd_entitas', $kode_cabang_arr)
                                                ->orWhereNull('batch.kd_entitas');
                                        });
                                    }
                                    else {
                                        $q->where('batch.kd_entitas', $kantor);
                                    }
                                    $q->where('mst_karyawan.nama_karyawan', 'like', "%$search%");
                                })
                                ->where('gaji_per_bulan.bulan', $month)
                                ->where('gaji_per_bulan.tahun', $year)
                                ->where('batch.status', 'final');
                            });
                            if ($cetak == 'cetak') {
                                $data = $data->get();
                            }else{
                                $data=  $data->paginate($limit);
                            }

        foreach ($data as $key => $karyawan) {
            $insentif = DB::table('pph_yang_dilunasi')
                        ->select(
                            'insentif_kredit',
                            'insentif_penagihan',
                            DB::raw('CAST((insentif_kredit + insentif_penagihan) AS SIGNED) AS total_pajak_insentif')
                        )
                        ->where('nip', $karyawan->nip)
                        ->where('bulan', (int) $month)
                        ->where('tahun', (int) $year)
                        ->first();
            $karyawan->insentif = $insentif;
            $ptkp = null;
            if ($karyawan->keluarga) {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', $karyawan->keluarga->status_kawin)
                                ->first();
            }
            else {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', 'TK/0')
                                ->first();
            }
            $karyawan->ptkp = $ptkp;

            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $potongan = new \stdClass();
            $total_gaji = 0;
            $tunjangan_rutin = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa

            if ($karyawan->gaji) {
                // Get BPJS TK * Kesehatan
                $obj_gaji = $karyawan->gaji;
                $gaji = floor($obj_gaji->gaji);
                $total_gaji = floor($obj_gaji->total_gaji);
                $tunjangan_rutin = floor($obj_gaji->tunjangan_rutin);

                $jamsostek = $obj_gaji->penambah_bruto_jamsostek;

                // Get Potongan(JP1%, DPP 5%)
                $potongan->dpp = $obj_gaji->dpp;
                $potongan->jp_1_persen = $obj_gaji->jp;

                // Get BPJS TK
                $bpjs_tk = floor($obj_gaji->bpjs_tk);

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
            $total_potongan = floor($total_potongan);
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $total_gaji - $total_potongan;
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
                                                        DB::raw('CAST(SUM(insentif_kredit) AS SIGNED) AS insentif_kredit'),
                                                        DB::raw('CAST(SUM(insentif_penagihan) AS SIGNED) AS insentif_penagihan'),
                                                        DB::raw('CAST((SUM(insentif_kredit) + SUM(insentif_penagihan)) AS SIGNED) AS total_pajak_insentif')
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('gaji_per_bulan_id', $karyawan->gaji->id)
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
                                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'mst_karyawan.kd_entitas')
                                            ->where(function($query) use ($karyawan) {
                                                $query->whereRelation('allGajiByKaryawan', 'nip', $karyawan->nip);
                                            })
                                            ->orderByRaw("
                                                CASE WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 1
                                                WHEN mst_karyawan.kd_jabatan='PSD' THEN 2
                                                WHEN mst_karyawan.kd_jabatan='PC' THEN 3
                                                WHEN mst_karyawan.kd_jabatan='PBP' THEN 4
                                                WHEN mst_karyawan.kd_jabatan='PBO' THEN 5
                                                WHEN mst_karyawan.kd_jabatan='PEN' THEN 6
                                                WHEN mst_karyawan.kd_jabatan='ST' THEN 7
                                                WHEN mst_karyawan.kd_jabatan='NST' THEN 8
                                                WHEN mst_karyawan.kd_jabatan='IKJP' THEN 9 END ASC
                                            ")
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
                                $jkk = floor(($persen_jkk / 100) * $total_gaji);
                                $jht = floor(($persen_jht / 100) * $total_gaji);
                                $jkm = floor(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = floor($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = floor($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = floor($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                                $dppBrutoExtra = floor(($persen_jp_pengurang / 100) * $total_gaji);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = floor($nominal_jp * ($persen_jp_pengurang / 100));
                                } else {
                                    $dppBrutoExtra = floor($total_gaji * ($persen_jp_pengurang / 100));
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
                // Get pph dilunasi
                if ($karyawan_bruto->pphDilunasi) {
                    if (count($karyawan_bruto->pphDilunasi) > 0) {
                        $last_index = count($karyawan_bruto->pphDilunasi) - 1;
                        $pph_dilunasi = $karyawan_bruto->pphDilunasi[$last_index]->nominal;
                        $terutang = DB::table('pph_yang_dilunasi')
                                    ->select('terutang')
                                    ->where('nip', $karyawan->nip)
                                    ->where('tahun', $karyawan_bruto->pphDilunasi[$last_index]->tahun)
                                    ->where('bulan', intval($karyawan_bruto->pphDilunasi[$last_index]->bulan - 1))
                                    ->first();
                        if ($terutang) {
                            $pph_dilunasi += $terutang->terutang;
                        }
                    }
                }
                // Get insentif
                $total_pajak_insentif = 0;
                if ($karyawan->insentif) {
                    $total_pajak_insentif = $karyawan->insentif->total_pajak_insentif;
                }
                $karyawan->pph_dilunasi_bulan_ini = (int) $pph_dilunasi - $total_pajak_insentif;
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

            // Get PPh21 PP58
            $pph21_pp58 = HitungPPH::getPPh58($month, $year, $karyawan, $ptkp, $karyawan->tanggal_input, $total_gaji, $tunjangan_rutin);
            $perhitunganPph21->pph21_pp58 = $pph21_pp58;

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
        return $data;
    }

    public function total($kantor, $month, $year, $search, $page=1, $limit=10, $cetak) {
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

        if($kantor == 'pusat'){
            $kd_entitas = '000';
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
            $kd_entitas = $kantor;
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

        // Get last penggajian
        $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear($kd_entitas);
        $last_month_penggajian = 0;
        if ($last_date_penggajian) {
            $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
        }

        $data = KaryawanModel::with([
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
                                        'gaji_per_bulan.id',
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
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                        DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                                        'kredit_koperasi',
                                        'iuran_koperasi',
                                        'kredit_pegawai',
                                        'iuran_ik',
                                        DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS total_potongan'),
                                    )
                                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                    ->whereNull('batch.deleted_at')
                                    ->where('bulan', $month)
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                                },
                                'bonus' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'bonus')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
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
                                'mst_karyawan.nip',
                                'mst_karyawan.kd_entitas',
                                'mst_karyawan.gj_pokok',
                                'mst_karyawan.gj_penyesuaian',
                                'nama_karyawan',
                                'npwp',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
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
                                'mst_karyawan.status_karyawan',
                                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"),
                                'batch.tanggal_input'
                            )
                            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
                            ->orderByRaw("
                                CASE WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 1
                                WHEN mst_karyawan.kd_jabatan='PSD' THEN 2
                                WHEN mst_karyawan.kd_jabatan='PC' THEN 3
                                WHEN mst_karyawan.kd_jabatan='PBP' THEN 4
                                WHEN mst_karyawan.kd_jabatan='PBO' THEN 5
                                WHEN mst_karyawan.kd_jabatan='PEN' THEN 6
                                WHEN mst_karyawan.kd_jabatan='ST' THEN 7
                                WHEN mst_karyawan.kd_jabatan='NST' THEN 8
                                WHEN mst_karyawan.kd_jabatan='IKJP' THEN 9 END ASC
                            ")
                            ->whereNull('batch.deleted_at')
                            ->where(function($query) use ($month, $year, $kantor, $kode_cabang_arr, $search, $last_month_penggajian) {
                                $query->whereRelation('gaji', 'bulan', $month)
                                ->whereRelation('gaji', 'tahun', $year)
                                ->whereRaw("(tanggal_penonaktifan IS NULL OR ($month = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                                ->where(function($q) use ($kantor, $kode_cabang_arr, $search) {
                                    if ($kantor == 'pusat') {
                                        $q->where(function($q2) use($kode_cabang_arr) {
                                            $q2->whereNotIn('batch.kd_entitas', $kode_cabang_arr)
                                                ->orWhereNull('batch.kd_entitas');
                                        });
                                    }
                                    else {
                                        $q->where('batch.kd_entitas', $kantor);
                                    }
                                    $q->where('mst_karyawan.nama_karyawan', 'like', "%$search%");
                                })
                                ->where('gaji_per_bulan.bulan', $month)
                                ->where('gaji_per_bulan.tahun', $year)
                                ->where('batch.status', 'final');
                            })->get();
        foreach ($data as $key => $karyawan) {
            $insentif = DB::table('pph_yang_dilunasi')
                        ->select(
                            'insentif_kredit',
                            'insentif_penagihan',
                            DB::raw('CAST((insentif_kredit + insentif_penagihan) AS SIGNED) AS total_pajak_insentif')
                        )
                        ->where('nip', $karyawan->nip)
                        ->where('bulan', (int) $month)
                        ->where('tahun', (int) $year)
                        ->first();
            $karyawan->insentif = $insentif;
            $ptkp = null;
            if ($karyawan->keluarga) {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', $karyawan->keluarga->status_kawin)
                                ->first();
            }
            else {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', 'TK/0')
                                ->first();
            }
            $karyawan->ptkp = $ptkp;

            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $potongan = new \stdClass();
            $total_gaji = 0;
            $tunjangan_rutin = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa

            if ($karyawan->gaji) {
                // Get BPJS TK * Kesehatan
                $obj_gaji = $karyawan->gaji;
                $gaji = floor($obj_gaji->gaji);
                $total_gaji = floor($obj_gaji->total_gaji);
                $tunjangan_rutin = floor($obj_gaji->tunjangan_rutin);

                $jamsostek = $obj_gaji->penambah_bruto_jamsostek;

                // Get Potongan(JP1%, DPP 5%)
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
            $total_potongan = floor($total_potongan);
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $total_gaji - $total_potongan;
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
                                                        DB::raw('CAST(SUM(insentif_kredit) AS SIGNED) AS insentif_kredit'),
                                                        DB::raw('CAST(SUM(insentif_penagihan) AS SIGNED) AS insentif_penagihan'),
                                                        DB::raw('CAST((SUM(insentif_kredit) + SUM(insentif_penagihan)) AS SIGNED) AS total_pajak_insentif')
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('gaji_per_bulan_id', $karyawan->gaji->id)
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
                                            ->orderByRaw("
                                                CASE WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 1
                                                WHEN mst_karyawan.kd_jabatan='PSD' THEN 2
                                                WHEN mst_karyawan.kd_jabatan='PC' THEN 3
                                                WHEN mst_karyawan.kd_jabatan='PBP' THEN 4
                                                WHEN mst_karyawan.kd_jabatan='PBO' THEN 5
                                                WHEN mst_karyawan.kd_jabatan='PEN' THEN 6
                                                WHEN mst_karyawan.kd_jabatan='ST' THEN 7
                                                WHEN mst_karyawan.kd_jabatan='NST' THEN 8
                                                WHEN mst_karyawan.kd_jabatan='IKJP' THEN 9 END ASC
                                            ")
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
                                $jkk = floor(($persen_jkk / 100) * $total_gaji);
                                $jht = floor(($persen_jht / 100) * $total_gaji);
                                $jkm = floor(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = floor($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = floor($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = floor($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                                $dppBrutoExtra = floor(($persen_jp_pengurang / 100) * $total_gaji);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = floor($nominal_jp * ($persen_jp_pengurang / 100));
                                } else {
                                    $dppBrutoExtra = floor($total_gaji * ($persen_jp_pengurang / 100));
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
                // Get pph dilunasi
                if ($karyawan_bruto->pphDilunasi) {
                    if (count($karyawan_bruto->pphDilunasi) > 0) {
                        $last_index = count($karyawan_bruto->pphDilunasi) - 1;
                        $pph_dilunasi = $karyawan_bruto->pphDilunasi[$last_index]->nominal;
                        $terutang = DB::table('pph_yang_dilunasi')
                                    ->select('terutang')
                                    ->where('nip', $karyawan->nip)
                                    ->where('tahun', $karyawan_bruto->pphDilunasi[$last_index]->tahun)
                                    ->where('bulan', intval($karyawan_bruto->pphDilunasi[$last_index]->bulan - 1))
                                    ->first();
                        if ($terutang) {
                            $pph_dilunasi += $terutang->terutang;
                        }
                    }
                }
                // Get insentif
                $total_pajak_insentif = 0;
                if ($karyawan->insentif) {
                    $total_pajak_insentif = $karyawan->insentif->total_pajak_insentif;
                }
                $karyawan->pph_dilunasi_bulan_ini = (int) $pph_dilunasi - $total_pajak_insentif;
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

            // Get PPh21 PP58
            $pph21_pp58 = HitungPPH::getPPh58($month, $year, $karyawan, $ptkp, $karyawan->tanggal_input, $total_gaji, $tunjangan_rutin);
            $perhitunganPph21->pph21_pp58 = $pph21_pp58;

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
        $data = $data;
        $grand_footer_total_gaji = 0;
        $grand_footer_bpjs_tk = 0;
        $grand_footer_dpp = 0;
        $grand_footer_kredit_koperasi = 0;
        $grand_footer_iuran_koperasi = 0;
        $grand_footer_kredit_pegawai = 0;
        $grand_footer_iuran_ik = 0;
        $grand_footer_total_potongan = 0;
        $grand_footer_total_diterima = 0;
        $total_gj_pokok = 0;
        $total_gj_penyesuaian = 0;
        $total_tj_keluarga = 0;
        $total_tj_telepon = 0;
        $total_tj_jabatan = 0;
        $total_tj_teller = 0;
        $total_tj_perumahan = 0;
        $total_tj_kemahalan = 0;
        $total_tj_pelaksana = 0;
        $total_tj_kesejahteraan = 0;
        $total_tj_multilevel = 0;
        $total_tj_ti = 0;
        $total_tj_fungsional = 0;
        $total_tj_khusus = 0;
        $total_tj_transport = 0;
        $total_tj_pulsa = 0;
        $total_tj_vitamin = 0;
        $total_uang_makan = 0;
        $total_dpp = 0;
        $total_total_gaji = 0;
        $total_pph_harus_dibayar = 0;
        $total_pajak_insentif = 0;

        foreach ($data as $item) {
            $insentif = DB::table('pph_yang_dilunasi')
                        ->select(
                            'insentif_kredit',
                            'insentif_penagihan',
                            DB::raw('CAST((insentif_kredit + insentif_penagihan) AS SIGNED) AS total_pajak_insentif')
                        )
                        ->where('nip', $item->nip)
                        ->where('bulan', (int) $month)
                        ->where('tahun', (int) $year)
                        ->first();
            $item->insentif = $insentif;
            if ($insentif) {
                $total_pajak_insentif += $insentif->total_pajak_insentif;
            }
            $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
            $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
            $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
            $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
            $iuran_koperasi = $item->potonganGaji ? number_format($item->gaji->iuran_koperasi, 0, ',', '.') : 0;
            $kredit_pegawai = $item->potonganGaji ? number_format($item->gaji->kredit_pegawai, 0, ',', '.') : 0;
            $iuran_ik = $item->potonganGaji ? number_format($item->gaji->iuran_ik, 0, ',', '.') : 0;
            $total_potongan = number_format($item->total_potongan, 0, ',', '.');
            $total_diterima = $item->total_yg_diterima ? number_format($item->total_yg_diterima, 0, ',', '.') : 0;
            // count total rincian
            $total_gj_pokok += $item->gaji->gj_pokok;
            $total_gj_penyesuaian += $item->gaji->gj_penyesuaian;
            $total_tj_keluarga += $item->gaji->tj_keluarga;
            $total_tj_telepon += $item->gaji->tj_telepon;
            $total_tj_jabatan += $item->gaji->tj_jabatan;
            $total_tj_teller += $item->gaji->tj_teller;
            $total_tj_perumahan += $item->gaji->tj_perumahan;
            $total_tj_kemahalan += $item->gaji->tj_kemahalan;
            $total_tj_pelaksana += $item->gaji->tj_pelaksana;
            $total_tj_kesejahteraan += $item->gaji->tj_kesejahteraan;
            $total_tj_multilevel += $item->gaji->tj_multilevel;
            $total_tj_ti += $item->gaji->tj_ti;
            $total_tj_fungsional += $item->gaji->tj_fungsional;
            $total_tj_khusus += ($item->gaji->tj_multilevel + $item->gaji->tj_ti + $item->gaji->tj_fungsional);
            $total_tj_transport += $item->gaji->tj_transport;
            $total_tj_pulsa += $item->gaji->tj_pulsa;
            $total_tj_vitamin += $item->gaji->tj_vitamin;
            $total_uang_makan += $item->gaji->uang_makan;
            $total_dpp += $item->gaji->dpp;
            $total_total_gaji += $item->gaji->total_gaji;
            // Get pph dilunasi
            $total_pph_harus_dibayar += $item->pph_dilunasi_bulan_ini;

            // count total payroll
            $grand_footer_total_gaji += str_replace('.', '', $total_gaji);
            $grand_footer_bpjs_tk += str_replace('.', '', $bpjs_tk);
            $grand_footer_dpp += str_replace('.', '', $dpp);
            $grand_footer_kredit_koperasi += str_replace('.', '', $kredit_koperasi);
            $grand_footer_iuran_koperasi += str_replace('.', '', $iuran_koperasi);
            $grand_footer_kredit_pegawai += str_replace('.', '', $kredit_pegawai);
            $grand_footer_iuran_ik += str_replace('.', '', $iuran_ik);
            $grand_footer_total_potongan += str_replace('.', '', $total_potongan);
            $grand_footer_total_diterima += str_replace('.', '', $total_diterima);
        }

        $result = [
            'total_gj_pokok' => $total_gj_pokok,
            'total_gj_penyesuaian' => $total_gj_penyesuaian,
            'total_tj_keluarga' => $total_tj_keluarga,
            'total_tj_telepon' => $total_tj_telepon,
            'total_tj_jabatan' => $total_tj_jabatan,
            'total_tj_teller' => $total_tj_teller,
            'total_tj_perumahan' => $total_tj_perumahan,
            'total_tj_kemahalan' => $total_tj_kemahalan,
            'total_tj_pelaksana' => $total_tj_pelaksana,
            'total_tj_kesejahteraan' => $total_tj_kesejahteraan,
            'total_tj_multilevel' => $total_tj_multilevel,
            'total_tj_ti' => $total_tj_ti,
            'total_tj_fungsional' => $total_tj_fungsional,
            'total_tj_khusus' => $total_tj_khusus,
            'total_tj_transport' => $total_tj_transport,
            'total_tj_pulsa' => $total_tj_pulsa,
            'total_tj_vitamin' => $total_tj_vitamin,
            'total_uang_makan' => $total_uang_makan,
            'total_dpp' => $total_dpp,
            'total_total_gaji' => $total_total_gaji,
            'total_pph_harus_dibayar' => $total_pph_harus_dibayar,
            'total_pajak_insentif' => $total_pajak_insentif,
            'grand_total_gaji' => $grand_footer_total_gaji,
            'grand_bpjs_tk' => $grand_footer_bpjs_tk,
            'grand_dpp' => $grand_footer_dpp,
            'grand_kredit_koperasi' => $grand_footer_kredit_koperasi,
            'grand_iuran_koperasi' => $grand_footer_iuran_koperasi,
            'grand_kredit_pegawai' => $grand_footer_kredit_pegawai,
            'grand_iuran_ik' => $grand_footer_iuran_ik,
            'grand_total_potongan' => $grand_footer_total_potongan,
            'grand_total_diterima' => $grand_footer_total_diterima,
        ];
        return $result;

    }


    public function getJson($kantor, $month, $year, $cetak, $batch_id) {
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

        $batch = DB::table('batch_gaji_per_bulan')->find($batch_id);
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

        $data = KaryawanModel::with([
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
                                        'gaji_per_bulan.id',
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
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                        DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                                        'kredit_koperasi',
                                        'iuran_koperasi',
                                        'kredit_pegawai',
                                        'iuran_ik',
                                        DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS total_potongan'),
                                    )
                                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                    ->whereNull('batch.deleted_at')
                                    ->where('bulan', $month)
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                                },
                                'bonus' => function($query) use ($year, $month) {
                                    $query->select(
                                            'penghasilan_tidak_teratur.id',
                                            'penghasilan_tidak_teratur.nip',
                                            'penghasilan_tidak_teratur.id_tunjangan',
                                            'mst_tunjangan.nama_tunjangan',
                                            DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                                            'penghasilan_tidak_teratur.kd_entitas',
                                            'penghasilan_tidak_teratur.tahun',
                                            'penghasilan_tidak_teratur.bulan',
                                        )
                                        ->where('mst_tunjangan.kategori', 'bonus')
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
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
                                'mst_karyawan.nip',
                                'mst_karyawan.kd_entitas',
                                'mst_karyawan.gj_pokok',
                                'mst_karyawan.gj_penyesuaian',
                                'mst_karyawan.status_ptkp',
                                'nama_karyawan',
                                'npwp',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
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
                                'mst_karyawan.status_karyawan',
                                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"),
                                'batch.tanggal_input'
                            )
                            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
                            ->where(function($query) use ($batch) {
                                $query->where('batch.id', $batch->id);
                            })
                            ->orderByRaw($this->orderRaw)
                            ->orderBy('status_kantor', 'asc')
                            ->orderBy('kd_cabang', 'asc')
                            ->orderBy('nip', 'asc')
                            ->orderBy('batch.kd_entitas')
                            ->get();
        foreach ($data as $key => $karyawan) {
            $insentif = DB::table('pph_yang_dilunasi')
                        ->select(
                            'insentif_kredit',
                            'insentif_penagihan',
                            DB::raw('CAST((insentif_kredit + insentif_penagihan) AS SIGNED) AS total_pajak_insentif')
                        )
                        ->where('nip', $karyawan->nip)
                        ->where('bulan', (int) $month)
                        ->where('tahun', (int) $year)
                        ->first();
            $karyawan->insentif = $insentif;
            $ptkp = null;
            if ($karyawan->keluarga) {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', $karyawan->keluarga->status_kawin)
                                ->first();
            }
            else {
                $ptkp = PtkpModel::select('id', 'kode', 'ptkp_bulan', 'ptkp_tahun', 'keterangan')
                                ->where('kode', 'TK/0')
                                ->first();
            }
            $karyawan->ptkp = $ptkp;

            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $potongan = new \stdClass();
            $total_gaji = 0;
            $tunjangan_rutin = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa

            if ($karyawan->gaji) {
                // Get BPJS TK * Kesehatan
                $obj_gaji = $karyawan->gaji;
                $gaji = floor($obj_gaji->gaji);
                $total_gaji = floor($obj_gaji->total_gaji);
                $tunjangan_rutin = floor($obj_gaji->tunjangan_rutin);

                $jamsostek = $obj_gaji->penambah_bruto_jamsostek;

                // Get Potongan(JP1%, DPP 5%)
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
            $total_potongan = floor($total_potongan);
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $total_gaji - $total_potongan;
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
                                                        DB::raw('CAST(SUM(insentif_kredit) AS SIGNED) AS insentif_kredit'),
                                                        DB::raw('CAST(SUM(insentif_penagihan) AS SIGNED) AS insentif_penagihan'),
                                                        DB::raw('CAST((SUM(insentif_kredit) + SUM(insentif_penagihan)) AS SIGNED) AS total_pajak_insentif')
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('gaji_per_bulan_id', $karyawan->gaji->id)
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
                                $jkk = floor(($persen_jkk / 100) * $total_gaji);
                                $jht = floor(($persen_jht / 100) * $total_gaji);
                                $jkm = floor(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = floor($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = floor($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = floor($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                                $dppBrutoExtra = floor(($persen_jp_pengurang / 100) * $total_gaji);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = floor($nominal_jp * ($persen_jp_pengurang / 100));
                                } else {
                                    $dppBrutoExtra = floor($total_gaji * ($persen_jp_pengurang / 100));
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
                // Get pph dilunasi
                if ($karyawan_bruto->pphDilunasi) {
                    if (count($karyawan_bruto->pphDilunasi) > 0) {
                        $last_index = count($karyawan_bruto->pphDilunasi) - 1;
                        $pph_dilunasi = $karyawan_bruto->pphDilunasi[$last_index]->nominal;
                        $terutang = DB::table('pph_yang_dilunasi')
                                    ->select('terutang')
                                    ->where('nip', $karyawan->nip)
                                    ->where('tahun', $karyawan_bruto->pphDilunasi[$last_index]->tahun)
                                    ->where('bulan', intval($karyawan_bruto->pphDilunasi[$last_index]->bulan - 1))
                                    ->first();
                        if ($terutang) {
                            $pph_dilunasi += $terutang->terutang;
                        }
                    }
                }
                // Get insentif
                $total_pajak_insentif = 0;
                if ($karyawan->insentif) {
                    $total_pajak_insentif = $karyawan->insentif->total_pajak_insentif;
                }
                $karyawan->pph_dilunasi_bulan_ini = (int) $pph_dilunasi - $total_pajak_insentif;
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
            // Get PPh21 PP58
            $pph21_pp58 = HitungPPH::getPPh58($month, $year, $karyawan, $ptkp, $karyawan->tanggal_input, $total_gaji, $tunjangan_rutin);
            $perhitunganPph21->pph21_pp58 = $pph21_pp58;

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

        return $data;
    }
}
