<?php

namespace App\Repository;

use App\Helpers\HitungPPH;
use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Models\PtkpModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use stdClass;

class LaporanTetapRepository
{
    private $param;
    private \Illuminate\Support\Collection $cabang;
    private String $orderRaw;
    private $karyawanRepo;

    public function __construct()
    {
        $this->karyawanRepo = new KaryawanRepository;
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
        $this->param['namaTunjangan'] = [
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
            'tj_transport',
            'tj_pulsa',
            'tj_vitamin',
            'uang_makan',
        ];
    }

    function stringMonth($month){
        $result = null;
        if ($month == 1) {
            $result = '01';
        }
        else if ($month == 2) {
            $result = '02';
        }
        else if ($month == 3) {
            $result = '03';
        }
        else if ($month == 4) {
            $result = '04';
        }
        else if ($month == 5) {
            $result = '05';
        }
        else if ($month == 6) {
            $result = '06';
        }
        else if ($month == 7) {
            $result = '07';
        }
        else if ($month == 8) {
            $result = '08';
        }
        else if ($month == 9) {
            $result = '09';
        }
        else if ($month == 10) {
            $result = '10';
        }
        else if ($month == 11) {
            $result = '11';
        }
        else if ($month == 12) {
            $result = '12';
        }
        return $result;
    }

    public function get($kantor = 'keseluruhan', $kategori, $search, $limit=10, $cetak, $year, $month){
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

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
                'gaji' => function($query) use ($year, $month) {
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
                        'tj_transport',
                        'tj_pulsa',
                        'tj_vitamin',
                        'uang_makan',
                        'dpp',
                        'jp',
                        'bpjs_tk',
                        'penambah_bruto_jamsostek',
                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji")
                    )
                    ->where('tahun', $year)
                    ->where('bulan', $month);
                },
                'tunjangan' => function($query) use ($year, $month) {
                    $query->whereYear('tunjangan_karyawan.created_at', $year)
                        ->whereMonth('tunjangan_karyawan.created_at', $month);
                },
                'tunjanganTidakTetap' => function($query) use ($kategori, $kantor, $month, $year) {
                    $query->select(
                            'penghasilan_tidak_teratur.id',
                            'penghasilan_tidak_teratur.nip',
                            'penghasilan_tidak_teratur.id_tunjangan',
                            'mst_tunjangan.nama_tunjangan',
                            // DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                            'penghasilan_tidak_teratur.nominal',
                            'penghasilan_tidak_teratur.kd_entitas',
                            'penghasilan_tidak_teratur.tahun',
                            'penghasilan_tidak_teratur.bulan',
                        )
                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                        ->when($kategori, function ($query) use ($kategori, $kantor, $month, $year) {
                            if ($kategori == 'ebupot') {
                                if ($month == 1) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 1)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanJanuari = date('d', strtotime($tanggal->tanggal_input));
                                            $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                                $year . '-01-01',
                                                $year . '-01-' . $hariTerakhirBulanJanuari
                                            ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else if ($month == 12) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 12)->first();
                                    $tanggal_bulan_kemaren = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 11)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanNovember = date('d', strtotime($tanggal_bulan_kemaren->tanggal_input . ' +1 day'));
                                        $hariTerakhirBulanDesember = Carbon::parse($tanggal->tanggal_input)->lastOfMonth()->day;
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                                $year . '-11-' . $hariTerakhirBulanNovember,
                                                $year . '-12-' . $hariTerakhirBulanDesember
                                            ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', $month)->first();
                                    if ($tanggal) {
                                        $tanggal_input = $tanggal->tanggal_input;
                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal_input, $kantor);
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $start_date, $tanggal_input
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                }
                            } else {
                                    $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                    ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                            }
                        });
                        // ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                },
                'bonus' => function($query) use ($kategori, $kantor, $month, $year) {
                    $query->select(
                            'penghasilan_tidak_teratur.id',
                            'penghasilan_tidak_teratur.nip',
                            'penghasilan_tidak_teratur.id_tunjangan',
                            'mst_tunjangan.nama_tunjangan',
                            // DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                            'penghasilan_tidak_teratur.nominal',
                            'penghasilan_tidak_teratur.kd_entitas',
                            'penghasilan_tidak_teratur.tahun',
                            'penghasilan_tidak_teratur.bulan',
                        )
                        ->where('mst_tunjangan.kategori', 'bonus')
                        ->when($kategori, function ($query) use ($kategori, $kantor, $month, $year) {
                            if ($kategori == 'ebupot') {
                                if ($month == 1) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 1)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanJanuari = date('d', strtotime($tanggal->tanggal_input));
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-01-01',
                                            $year . '-01-' . $hariTerakhirBulanJanuari
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else if ($month == 12) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 12)->first();
                                    $tanggal_bulan_kemaren = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 11)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanNovember = date('d', strtotime($tanggal_bulan_kemaren->tanggal_input . ' +1 day'));
                                        $hariTerakhirBulanDesember = Carbon::parse($tanggal->tanggal_input)->lastOfMonth()->day;
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-11-' . $hariTerakhirBulanNovember,
                                            $year . '-12-' . $hariTerakhirBulanDesember
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', $month)->first();
                                    if ($tanggal) {
                                        $tanggal_input = $tanggal->tanggal_input;
                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal_input, $kantor);
                                        // return [$start_date, $tanggal_input];
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $start_date, $tanggal_input
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                                ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                }
                            } else {
                                $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                            }
                        });
                        // ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                },
                'potonganGaji' => function($query) {
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
                            'TK',
                            mst_karyawan.status
                        )
                    ) AS status
                "),
                'status_karyawan',
                'mst_karyawan.kd_entitas',
                'kd_jabatan',
                'ket_jabatan',
                'alamat_ktp',
                'jk',
                'mst_karyawan.gj_pokok',
                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor")
            )
            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
            ->whereNull('batch.deleted_at')
            ->whereYear('batch.tanggal_input', $year)
            ->orderByRaw($this->orderRaw)
            ->orderBy('status_kantor', 'asc')
            ->orderBy('kd_cabang', 'asc')
            ->orderBy('nip', 'asc')
            ->orderBy('mst_karyawan.kd_entitas')
            ->where(function($query) use ($kantor, $kode_cabang_arr, $search) {
                $query->whereNull('tanggal_penonaktifan')
                ->where(function($q) use ($kantor, $kode_cabang_arr, $search) {
                    if ($kantor != '000') {
                        $q->where('mst_karyawan.kd_entitas', $kantor);
                    } else if($kantor == '000'){
                        $q->whereRaw("(mst_karyawan.kd_entitas NOT IN(SELECT kd_cabang FROM  mst_cabang where kd_cabang != '000') OR mst_karyawan.kd_entitas IS null or mst_karyawan.kd_entitas = '')");
                    }
                    $q->where('mst_karyawan.npwp', 'like', "%$search%")
                        ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                        ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%");
                });
            });

            if ($cetak) {
                $data = $data->where('gaji_per_bulan.bulan', $month)->where('gaji_per_bulan.tahun', $year)->get();
            } else {
                $data = $data->where('gaji_per_bulan.bulan', $month)->where('gaji_per_bulan.tahun', $year)->paginate($limit);
            }

        // $this->karyawanRepo->getEntity($data);

        foreach($data as $key => $karyawan){
            $karyawan->total_insentif_kredit = DB::table('penghasilan_tidak_teratur AS pt')
                                                ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pt.nip', $karyawan->nip)
                                                ->where('pt.bulan', $month)
                                                ->where('pt.tahun', $year)
                                                ->whereIn('pt.id_tunjangan', [31, 32])
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pt.nominal');
            $karyawan->insentif_kredit = DB::table('penghasilan_tidak_teratur AS pt')
                                        ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pt.nip', $karyawan->nip)
                                        ->where('pt.bulan', $month)
                                        ->where('pt.tahun', $year)
                                        ->where('pt.id_tunjangan', 31)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pt.nominal');
            $karyawan->insentif_penagihan = DB::table('penghasilan_tidak_teratur AS pt')
                                            ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                            ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                            ->where('pt.nip', $karyawan->nip)
                                            ->where('pt.bulan', $month)
                                            ->where('pt.tahun', $year)
                                            ->where('pt.id_tunjangan', 32)
                                            ->whereNull('batch.deleted_at')
                                            ->sum('pt.nominal');

            $karyawan->insentif_kredit_pajak = DB::table('pph_yang_dilunasi AS pph')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pph.nip', $karyawan->nip)
                                                ->where('pph.bulan', $month)
                                                ->where('pph.tahun', $year)
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pph.insentif_kredit');

            $karyawan->insentif_penagihan_pajak = DB::table('pph_yang_dilunasi AS pph')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pph.nip', $karyawan->nip)
                                                ->where('pph.bulan', $month)
                                                ->where('pph.tahun', $year)
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pph.insentif_penagihan');

            $insentif_kredit_pajak = DB::table('pph_yang_dilunasi AS pph')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pph.nip', $karyawan->nip)
                                        ->where('pph.bulan', $month)
                                        ->where('pph.tahun', $year)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pph.insentif_kredit');

            $insentif_penagihan_pajak = DB::table('pph_yang_dilunasi AS pph')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pph.nip', $karyawan->nip)
                                        ->where('pph.bulan', $month)
                                        ->where('pph.tahun', $year)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pph.insentif_penagihan');
            // total pajak insentif
            $karyawan->pajak_insentif = $insentif_kredit_pajak + $insentif_penagihan_pajak;
            // end total pajak insentif

            $prefix = match ($karyawan->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            if ($karyawan->jabatan) {
                $jabatan = $karyawan->jabatan->nama_jabatan;
            } else {
                $jabatan = 'undifined';
            }

            $ket = $karyawan->ket_jabatan ? "({$karyawan->ket_jabatan})" : '';

            if (isset($karyawan->entitas->subDiv)) {
                $entitas = $karyawan->entitas->subDiv->nama_subdivisi;
            } elseif (isset($karyawan->entitas->div)) {
                $entitas = $karyawan->entitas->div->nama_divisi;
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
                $jabatan = $karyawan->jabatan ? $karyawan->jabatan->nama_jabatan : 'undifined';
            }

            $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $karyawan?->bagian?->nama_bagian . ' ' . $ket;
            $karyawan->display_jabatan = $display_jabatan;
            // End Get Jabatan

            // Get Perhitungan
            if($karyawan->status_kantor == 0){
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
                    ->where('mst_profil_kantor.kd_cabang', $karyawan->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                    ->first();
                $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                    ->where('kd_cabang', $karyawan->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                    ->first();
            }

            $karyawan->total_masa_kerja = GajiPerBulanModel::where('nip', $karyawan->nip)
                ->where('tahun', $year)
                ->where('bulan', $month)
                ->count('*');

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
            if ($karyawan->potonganGaji) {
                $total_potongan += $karyawan->potonganGaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp ?? 0;
            }

            $total_potongan += $bpjs_tk;
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
                                                'allGajiByKaryawan' => function($query) use ($karyawan,$kategori, $kantor, $year, $month) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        'gj_pokok',
                                                        'tj_keluarga',
                                                        'tj_kesejahteraan',
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller) AS total_gaji"),
                                                        DB::raw("(uang_makan + tj_vitamin + tj_pulsa + tj_transport) AS total_tunjangan_lainnya"),
                                                    )
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('tahun', $year)
                                                    ->where('bulan', $month)
                                                    ->orderBy('bulan')
                                                    ->groupBy('bulan');
                                                },
                                                'sumBonusKaryawan' => function($query) use ($karyawan, $kategori, $kantor, $year, $month) {
                                                    $query->select(
                                                        'penghasilan_tidak_teratur.nip',
                                                        'mst_tunjangan.kategori',
                                                        DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                    )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->where('mst_tunjangan.kategori', 'bonus')
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'sumTunjanganTidakTetapKaryawan' => function($query) use ($karyawan, $kategori, $kantor, $year, $month) {
                                                    $query->select(
                                                            'penghasilan_tidak_teratur.nip',
                                                            'mst_tunjangan.kategori',
                                                            DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                        )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'pphDilunasi' => function($query) use ($karyawan, $year, $month) {
                                                    $query->select(
                                                        'id',
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                                        DB::raw('CAST(total_pph AS SIGNED) AS nominal'),
                                                        DB::raw('CAST(terutang AS SIGNED) AS terutang'),
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('bulan', $month)
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

            $gaji_bruto = 0;
            $tunjangan_lainnya_bruto = 0;
            $total_gaji_bruto = 0;
            $total_jamsostek = 0;
            $total_pengurang_bruto = 0;
            if ($karyawan_bruto) {
                // Get jamsostek
                if ($karyawan_bruto->allGajiByKaryawan) {
                    $allGajiByKaryawan = $karyawan_bruto->allGajiByKaryawan;
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
                                            ->where('bulan', $month)
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

            $total_rutin = $penghasilanBruto?->penghasilan_rutin ?? 0;
            $total_tidak_rutin = $penghasilanBruto?->penghasilan_tidak_rutin ?? 0;
            $bonus_sum = $penghasilanBruto?->total_bonus ?? 0;
            $pengurang = $penguranganPenghasilan?->total_pengurangan_bruto ?? 0;
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
            $status_kawin = 'TK';
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
            if ($karyawan_bruto?->pphDilunasi) {
                $pphDilunasi = $karyawan_bruto->pphDilunasi;
                $pphDilunasiArr = $karyawan_bruto->pphDilunasi->toArray();
                foreach ($pphDilunasi as $key => $value) {
                    $terutang = 0;
                    if (array_key_exists(($key - 1), $pphDilunasiArr)) {
                        $terutang = $pphDilunasiArr[($key - 1)]['terutang'];
                    }
                    $nominal_pph = intval($value->nominal) + $terutang;
                    $value->total_pph = $nominal_pph;
                    $total_pph_dilunasi += $nominal_pph;
                }
            }
            $pphPasal21->pph_telah_dilunasi = $total_pph_dilunasi;

            // 7. PPh Pasal 21 yang masih harus dibayar
            $pph_harus_dibayar = 0;
            if($karyawan_bruto?->pphDilunasi ){
                if(count($karyawan_bruto?->pphDilunasi) == 12){
                    $pph_harus_dibayar = $pph_21_terutang - $total_pph_dilunasi;
                } else{
                    $pph_harus_dibayar = DB::table('pph_yang_dilunasi')
                        ->where('nip', $karyawan->nip)
                        ->where('tahun', $year)
                        ->where('bulan', $month)
                        ->orderBy('id', 'desc')
                        ->first()?->terutang ?? 0;
                }
            }
            $pphPasal21->pph_harus_dibayar = $pph_harus_dibayar;

            $perhitunganPph21->pph_pasal_21 = $pphPasal21;
            $karyawan->perhitungan_pph21 = $perhitunganPph21;
        }

        // dd($data);
        return $data;
    }

    public function getTotal($kantor = 'keseluruhan', $kategori, $search, $limit=10, $cetak, $year, $month){
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

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
                'gaji' => function($query) use ($year, $month) {
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
                        'tj_transport',
                        'tj_pulsa',
                        'tj_vitamin',
                        'uang_makan',
                        'dpp',
                        'jp',
                        'bpjs_tk',
                        'penambah_bruto_jamsostek',
                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_teller + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji")
                    )
                    ->where('tahun', $year)
                    ->where('bulan', $month);
                },
                'tunjangan' => function($query) use ($year, $month) {
                    $query->whereYear('tunjangan_karyawan.created_at', $year)
                        ->whereMonth('tunjangan_karyawan.created_at', $month);
                },
                'tunjanganTidakTetap' => function ($query) use ($kategori, $kantor, $month, $year) {
                    $query->select(
                        'penghasilan_tidak_teratur.id',
                        'penghasilan_tidak_teratur.nip',
                        'penghasilan_tidak_teratur.id_tunjangan',
                        'mst_tunjangan.nama_tunjangan',
                        // DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                        'penghasilan_tidak_teratur.nominal',
                        'penghasilan_tidak_teratur.kd_entitas',
                        'penghasilan_tidak_teratur.tahun',
                        'penghasilan_tidak_teratur.bulan',
                    )
                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                        ->when($kategori, function ($query) use ($kategori, $kantor, $month, $year) {
                            if ($kategori == 'ebupot') {
                                if ($month == 1) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 1)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanJanuari = date('d', strtotime($tanggal->tanggal_input));
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-01-01',
                                            $year . '-01-' . $hariTerakhirBulanJanuari
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else if ($month == 12) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 12)->first();
                                    $tanggal_bulan_kemaren = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 11)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanNovember = date('d', strtotime($tanggal_bulan_kemaren->tanggal_input . ' +1 day'));
                                        $hariTerakhirBulanDesember = Carbon::parse($tanggal->tanggal_input)->lastOfMonth()->day;
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-11-' . $hariTerakhirBulanNovember,
                                            $year . '-12-' . $hariTerakhirBulanDesember
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', $month)->first();
                                    if ($tanggal) {
                                        $tanggal_input = $tanggal->tanggal_input;
                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal_input, $kantor);
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $start_date, $tanggal_input
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                }
                            } else {
                                $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                            }
                        });
                    // ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                },
                'bonus' => function ($query) use ($kategori, $kantor, $month, $year) {
                    $query->select(
                        'penghasilan_tidak_teratur.id',
                        'penghasilan_tidak_teratur.nip',
                        'penghasilan_tidak_teratur.id_tunjangan',
                        'mst_tunjangan.nama_tunjangan',
                        // DB::raw('CAST(SUM(penghasilan_tidak_teratur.nominal) AS SIGNED) AS nominal'),
                        'penghasilan_tidak_teratur.nominal',
                        'penghasilan_tidak_teratur.kd_entitas',
                        'penghasilan_tidak_teratur.tahun',
                        'penghasilan_tidak_teratur.bulan',
                    )
                        ->where('mst_tunjangan.kategori', 'bonus')
                        ->when($kategori, function ($query) use ($kategori, $kantor, $month, $year) {
                            if ($kategori == 'ebupot') {
                                if ($month == 1) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 1)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanJanuari = date('d', strtotime($tanggal->tanggal_input));
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-01-01',
                                            $year . '-01-' . $hariTerakhirBulanJanuari
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else if ($month == 12) {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 12)->first();
                                    $tanggal_bulan_kemaren = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', 11)->first();
                                    if ($tanggal) {
                                        $hariTerakhirBulanNovember = date('d', strtotime($tanggal_bulan_kemaren->tanggal_input . ' +1 day'));
                                        $hariTerakhirBulanDesember = Carbon::parse($tanggal->tanggal_input)->lastOfMonth()->day;
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $year . '-11-' . $hariTerakhirBulanNovember,
                                            $year . '-12-' . $hariTerakhirBulanDesember
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                } else {
                                    $tanggal = DB::table('batch_gaji_per_bulan')->select('tanggal_input')->whereYear('tanggal_input', $year)->where('kd_entitas', $kantor)->whereMonth('tanggal_input', $month)->first();
                                    if ($tanggal) {
                                        $tanggal_input = $tanggal->tanggal_input;
                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal_input, $kantor);
                                        $query->whereBetween('penghasilan_tidak_teratur.created_at', [
                                            $start_date, $tanggal_input
                                        ]);
                                    } else {
                                        $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                                    }
                                }
                            } else {
                                $query->whereMonth('penghasilan_tidak_teratur.created_at', $this->stringMonth($month))
                                        ->whereYear('penghasilan_tidak_teratur.created_at', $year);
                            }
                        });
                    // ->groupBy('penghasilan_tidak_teratur.id_tunjangan');
                },
                'potonganGaji' => function($query) {
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
                            'TK',
                            mst_karyawan.status
                        )
                    ) AS status
                "),
                'status_karyawan',
                'mst_karyawan.kd_entitas',
                'kd_jabatan',
                'ket_jabatan',
                'alamat_ktp',
                'jk',
                'mst_karyawan.gj_pokok',
                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor")
            )
            ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
            ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
            ->whereNull('batch.deleted_at')
            ->orderByRaw($this->orderRaw)
            ->orderBy('status_kantor', 'asc')
            ->orderBy('kd_cabang', 'asc')
            ->orderBy('nip', 'asc')
            ->orderBy('mst_karyawan.kd_entitas')
            ->where(function($query) use ($kantor, $kode_cabang_arr, $search) {
                $query->whereNull('tanggal_penonaktifan')
                ->where(function($q) use ($kantor, $kode_cabang_arr, $search) {
                    if ($kantor != '000' && $kantor != 'keseluruhan') {
                        $q->where('mst_karyawan.kd_entitas', $kantor);
                    } else if($kantor == '000'){
                        $q->whereRaw("(mst_karyawan.kd_entitas NOT IN(SELECT kd_cabang FROM  mst_cabang where kd_cabang != '000') OR mst_karyawan.kd_entitas IS null or mst_karyawan.kd_entitas = '')");
                    }
                    $q->where('mst_karyawan.npwp', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%");
                });
            });

        $data = $data->where('gaji_per_bulan.bulan', $month)->where('gaji_per_bulan.tahun', $year)->get();

        // $this->karyawanRepo->getEntity($data);

        foreach($data as $key => $karyawan){
            // Get Jabatan
            $prefix = match ($karyawan->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            if ($karyawan->jabatan) {
                $jabatan = $karyawan->jabatan->nama_jabatan;
            } else {
                $jabatan = 'undifined';
            }

            $karyawan->total_insentif_kredit = DB::table('penghasilan_tidak_teratur AS pt')
                                                ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pt.nip', $karyawan->nip)
                                                ->where('pt.bulan', $month)
                                                ->where('pt.tahun', $year)
                                                ->whereIn('pt.id_tunjangan', [31, 32])
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pt.nominal');
            $karyawan->insentif_kredit = DB::table('penghasilan_tidak_teratur AS pt')
                                        ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pt.nip', $karyawan->nip)
                                        ->where('pt.bulan', $month)
                                        ->where('pt.tahun', $year)
                                        ->where('pt.id_tunjangan', 31)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pt.nominal');
            $karyawan->insentif_penagihan = DB::table('penghasilan_tidak_teratur AS pt')
                                            ->join('batch_penghasilan_tidak_teratur AS batch_pt', 'batch_pt.penghasilan_tidak_teratur_id', 'pt.id')
                                            ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'batch_pt.gaji_per_bulan_id')
                                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                            ->where('pt.nip', $karyawan->nip)
                                            ->where('pt.bulan', $month)
                                            ->where('pt.tahun', $year)
                                            ->where('pt.id_tunjangan', 32)
                                            ->whereNull('batch.deleted_at')
                                            ->sum('pt.nominal');

            $karyawan->insentif_kredit_pajak = DB::table('pph_yang_dilunasi AS pph')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pph.nip', $karyawan->nip)
                                                ->where('pph.bulan', $month)
                                                ->where('pph.tahun', $year)
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pph.insentif_kredit');

            $karyawan->insentif_penagihan_pajak = DB::table('pph_yang_dilunasi AS pph')
                                                ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                                ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                                ->where('pph.nip', $karyawan->nip)
                                                ->where('pph.bulan', $month)
                                                ->where('pph.tahun', $year)
                                                ->whereNull('batch.deleted_at')
                                                ->sum('pph.insentif_penagihan');

            $insentif_kredit_pajak = DB::table('pph_yang_dilunasi AS pph')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pph.nip', $karyawan->nip)
                                        ->where('pph.bulan', $month)
                                        ->where('pph.tahun', $year)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pph.insentif_kredit');

            $insentif_penagihan_pajak = DB::table('pph_yang_dilunasi AS pph')
                                        ->join('gaji_per_bulan', 'gaji_per_bulan.id', 'pph.gaji_per_bulan_id')
                                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                                        ->where('pph.nip', $karyawan->nip)
                                        ->where('pph.bulan', $month)
                                        ->where('pph.tahun', $year)
                                        ->whereNull('batch.deleted_at')
                                        ->sum('pph.insentif_penagihan');
            $karyawan->pajak_insentif = $insentif_kredit_pajak + $insentif_penagihan_pajak;
            // end total pajak insentif

            $ket = $karyawan->ket_jabatan ? "({$karyawan->ket_jabatan})" : '';

            if (isset($karyawan->entitas->subDiv)) {
                $entitas = $karyawan->entitas->subDiv->nama_subdivisi;
            } elseif (isset($karyawan->entitas->div)) {
                $entitas = $karyawan->entitas->div->nama_divisi;
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
                $jabatan = $karyawan->jabatan ? $karyawan->jabatan->nama_jabatan : 'undifined';
            }

            $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $karyawan?->bagian?->nama_bagian . ' ' . $ket;
            $karyawan->display_jabatan = $display_jabatan;
            // End Get Jabatan

            // Get Perhitungan
            if($karyawan->status_kantor == 0){
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
                    ->where('mst_profil_kantor.kd_cabang', $karyawan->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                    ->first();
                $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                    ->where('kd_cabang', $karyawan->kd_entitas)
                    ->where('active', 1)
                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                    ->first();
            }

            $karyawan->total_masa_kerja = GajiPerBulanModel::where('nip', $karyawan->nip)
                ->where('tahun', $year)
                ->where('bulan', $month)
                ->count('*');

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
            if ($karyawan->potonganGaji) {
                $total_potongan += $karyawan->potonganGaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp ?? 0;
            }

            $total_potongan += $bpjs_tk;
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
                                                'allGajiByKaryawan' => function($query) use ($karyawan, $year, $month) {
                                                    $query->select(
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        'gj_pokok',
                                                        'tj_keluarga',
                                                        'tj_kesejahteraan',
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller) AS total_gaji"),
                                                        DB::raw("(uang_makan + tj_vitamin + tj_pulsa + tj_transport) AS total_tunjangan_lainnya"),
                                                    )
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('tahun', $year)
                                                    ->where('bulan', $month)
                                                    ->orderBy('bulan')
                                                    ->groupBy('bulan');
                                                },
                                                'sumBonusKaryawan' => function($query) use ($karyawan, $year, $month) {
                                                    $query->select(
                                                        'penghasilan_tidak_teratur.nip',
                                                        'mst_tunjangan.kategori',
                                                        DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                    )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'bonus')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'sumTunjanganTidakTetapKaryawan' => function($query) use ($karyawan, $year, $month) {
                                                    $query->select(
                                                            'penghasilan_tidak_teratur.nip',
                                                            'mst_tunjangan.kategori',
                                                            DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                        )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                                        ->where('penghasilan_tidak_teratur.bulan', $month)
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'pphDilunasi' => function($query) use ($karyawan, $year, $month) {
                                                    $query->select(
                                                        'id',
                                                        'nip',
                                                        DB::raw("CAST(bulan AS SIGNED) AS bulan"),
                                                        DB::raw("CAST(tahun AS SIGNED) AS tahun"),
                                                        DB::raw('CAST(total_pph AS SIGNED) AS nominal'),
                                                        DB::raw('CAST(terutang AS SIGNED) AS terutang'),
                                                    )
                                                    ->where('tahun', $year)
                                                    ->where('bulan', $month)
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

            $gaji_bruto = 0;
            $tunjangan_lainnya_bruto = 0;
            $total_gaji_bruto = 0;
            $total_jamsostek = 0;
            $total_pengurang_bruto = 0;
            if ($karyawan_bruto) {
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
                                            ->where('bulan', $month)
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

            $total_rutin = $penghasilanBruto->penghasilan_rutin ?? 0;
            $total_tidak_rutin = $penghasilanBruto->penghasilan_tidak_rutin ?? 0;
            $bonus_sum = $penghasilanBruto->total_bonus ?? 0;
            $pengurang = $penguranganPenghasilan->total_pengurangan_bruto ?? 0;
            $total_ket = $month_on_year_paid ?? 0;

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
            $status_kawin = 'TK';
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
            if ($karyawan_bruto?->pphDilunasi) {
                $pphDilunasi = $karyawan_bruto->pphDilunasi;
                $pphDilunasiArr = $karyawan_bruto->pphDilunasi->toArray();
                foreach ($pphDilunasi as $key => $value) {
                    $terutang = 0;
                    if (array_key_exists(($key - 1), $pphDilunasiArr)) {
                        $terutang = $pphDilunasiArr[($key - 1)]['terutang'];
                    }
                    $nominal_pph = intval($value->nominal) + $terutang;
                    $value->total_pph = $nominal_pph;
                    $total_pph_dilunasi += $nominal_pph;
                }
            }
            $pphPasal21->pph_telah_dilunasi = $total_pph_dilunasi;

            // 7. PPh Pasal 21 yang masih harus dibayar
            $pph_harus_dibayar = 0;
            if($karyawan_bruto?->pphDilunasi){
                if(count($karyawan_bruto?->pphDilunasi) == 12){
                    $pph_harus_dibayar = $pph_21_terutang - $total_pph_dilunasi;
                } else{
                    $pph_harus_dibayar = DB::table('pph_yang_dilunasi')
                        ->where('nip', $karyawan->nip)
                        ->where('tahun', $year)
                        ->orderBy('id', 'desc')
                        ->first()?->terutang ?? 0;
                }

            }
            $pphPasal21->pph_harus_dibayar = $pph_harus_dibayar;

            $perhitunganPph21->pph_pasal_21 = $pphPasal21;
            $karyawan->perhitungan_pph21 = $perhitunganPph21;
        }

        $returnData = new stdClass;
        $totalGaji = 0;
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
        $totalBrutoNataru = 0;
        $totalPPHNataru = 0;
        $totalBrutoJaspro = 0;
        $totalBrutoTambahanPenghasilan = 0;
        $totalBrutoRekreasi = 0;
        $totalBrutoTHR = 0;
        $totalBrutoDanaPendidikan = 0;
        $totalBrutoPenghargaanKinerja = 0;
        $totalPPHJaspro = 0;
        $totalPPHTambahanPenghasilan = 0;
        $totalPPHRekreasi = 0;
        $totalPPH21Bentukan = 0;
        $totalPPH21 = 0;
        $totalPenambahBruto = 0;
        $totalBruto = 0;
        $total_insentif_kredit = 0;
        $total_insentif_penagihan = 0;
        $total_insentif_kredit_pajak = 0;
        $total_insentif_penagihan_pajak = 0;
        $totalPajakInsentif = 0;
        $totalInsentif = 0;
        $totalPPh = 0;

        foreach($data as $key => $item){
            $totalGaji += $item->gaji->total_gaji ?? $item->gj_pokok;
            $totalUangMakan += $item->gaji->uang_makan ?? 0;
            $totalPulsa += $item->gaji->tj_pulsa ?? 0;
            $totalVitamin += $item->gaji->tj_vitamin ?? 0;
            $totalTransport += $item->gaji->tj_transport ?? 0;
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
            $brutoNataru = 0;
            $pphNataru = 0;
            $brutoJaspro = 0;
            $pphJaspro = 0;
            $brutoTambahanPenghasilan = 0;
            $brutoRekreasi = 0;
            $brutoTHR = 0;
            $brutoDanaPendidikan = 0;
            $brutoPenghargaanKinerja = 0;
            $pphTambahanPenghasilan = 0;
            $pphRekreasi = 0;
            $pph21Bentukan = 0;
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
            }

            foreach($item?->bonus as $value){
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
                    $brutoTambahanPenghasilan += $value->nominal;
                }
                if ($value->id_tunjangan == 28) {
                    $brutoPenghargaanKinerja += $value->nominal;
                }
                if ($value->id_tunjangan == 33) {
                    $brutoRekreasi += $value->nominal;
                }
            }

            foreach ($item?->karyawan_bruto->pphDilunasi as $value) {
                if ($value->bulan > 1) {
                    $pph21Bentukan = $value->total_pph;
                    $pph21 = $value->total_pph;
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
                    $pph21Bentukan = $value->total_pph;
                    $pph21 = $value->total_pph;
                }
            }
            $pph21 -= $item->pajak_insentif;
            $penambahBruto = $item->jamsostek;

            $brutoPPH = $pphNataru + $pphJaspro + $pphTambahanPenghasilan + $pphRekreasi + $pph21Bentukan;
            $totalInsentif += $item->total_insentif_kredit ?? 0;
            $totalPajakInsentif += $item->pajak_insentif ?? 0;
            $total_insentif_kredit += $item->insentif_kredit ?? 0;
            $total_insentif_penagihan += $item->insentif_penagihan ?? 0;
            $total_insentif_kredit_pajak += $item->insentif_kredit_pajak ?? 0;
            $total_insentif_penagihan_pajak += $item->insentif_penagihan_pajak ?? 0;
            $brutoTotal = $gaji + $uangMakan + $pulsa + $vitamin + $transport + $lembur + $penggantiBiayaKesehatan + $uangDuka + $spd + $spdPendidikan + $spdPindahTugas + $brutoNataru + $brutoJaspro + $penambahBruto + $brutoTambahanPenghasilan + $brutoRekreasi + $brutoDanaPendidikan + $brutoTHR + $brutoPenghargaanKinerja + $total_insentif_kredit + $total_insentif_penagihan;

            $totalLembur += $lembur;
            $totalPenggantiKesehatan += $penggantiBiayaKesehatan;
            $totalUangDuka += $uangDuka;
            $totalSPD += $spd;
            $totalSPDPendidikan += $spdPendidikan;
            $totalSPDPindahTugas += $spdPindahTugas;
            $totalBrutoNataru += $brutoNataru;
            $totalPPHNataru += $pphNataru;
            $totalBrutoJaspro += $brutoJaspro;
            $totalBrutoTambahanPenghasilan += $brutoTambahanPenghasilan;
            $totalBrutoRekreasi += $brutoRekreasi;
            $totalBrutoTHR += $brutoTHR;
            $totalBrutoDanaPendidikan += $brutoDanaPendidikan;
            $totalBrutoPenghargaanKinerja += $brutoPenghargaanKinerja;
            $totalPPHJaspro += $pphJaspro;
            $totalPPHTambahanPenghasilan += $pphTambahanPenghasilan;
            $totalPPHRekreasi += $pphRekreasi;
            $totalPPH21Bentukan += $pph21Bentukan;
            $totalPPH21 += $pph21;
            $totalPenambahBruto += $penambahBruto;
            $totalBruto += $brutoTotal;
            $totalPPh += $brutoPPH;
        }

        $returnData->totalGaji = $totalGaji;
        $returnData->totalUangMakan = $totalUangMakan;
        $returnData->totalPulsa = $totalPulsa;
        $returnData->totalVitamin = $totalVitamin;
        $returnData->totalTransport = $totalTransport;
        $returnData->totalLembur = $totalLembur;
        $returnData->totalPenggantiKesehatan = $totalPenggantiKesehatan;
        $returnData->totalUangDuka = $totalUangDuka;
        $returnData->totalSPD = $totalSPD;
        $returnData->totalSPDPendidikan = $totalSPDPendidikan;
        $returnData->totalSPDPindahTugas = $totalSPDPindahTugas;
        $returnData->totalBrutoNataru = $totalBrutoNataru;
        $returnData->totalPPHNataru = $totalPPHNataru;
        $returnData->totalBrutoJaspro = $totalBrutoJaspro;
        $returnData->totalBrutoTambahanPenghasilan = $totalBrutoTambahanPenghasilan;
        $returnData->totalBrutoRekreasi = $totalBrutoRekreasi;
        $returnData->totalBrutoTHR = $totalBrutoTHR;
        $returnData->totalBrutoDanaPendidikan = $totalBrutoDanaPendidikan;
        $returnData->totalBrutoPenghargaanKinerja = $totalBrutoPenghargaanKinerja;
        $returnData->totalPPHJaspro = $totalPPHJaspro;
        $returnData->totalPPHTambahanPenghasilan = $totalPPHTambahanPenghasilan;
        $returnData->totalPPHRekreasi = $totalPPHRekreasi;
        $returnData->totalPPH21Bentukan = $totalPPH21Bentukan;
        $returnData->totalPPH21 = $totalPPH21;
        $returnData->totalPenambahBruto = $totalPenambahBruto;
        $returnData->totalBruto = $totalBruto;
        $returnData->totalInsentif = $totalInsentif;
        $returnData->totalPajakInsentif = $totalPajakInsentif;
        $returnData->totalPPh = $totalPPh;

        $returnData->total_insentif_kredit = $total_insentif_kredit;
        $returnData->total_insentif_penagihan = $total_insentif_penagihan;
        $returnData->total_insentif_kredit_pajak = $total_insentif_kredit_pajak;
        $returnData->total_insentif_penagihan_pajak = $total_insentif_penagihan_pajak;

        return $returnData;
    }
}
