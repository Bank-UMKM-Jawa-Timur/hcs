<?php

namespace App\Imports;

use App\Models\GajiPerBulanModel;
use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\PPHModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPPH21 implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $penghasilan_tidak_teratur = array(
            'uang_lembur', 
            'biaya_kesehatan', 
            'uang_duka', 
            'spd', 
            'spd_pendidikan', 
            'spd_pindah_tugas', 
            'thr', 
            'jasa_produksi', 
            'dana_pendidikan'
        );
        
        $gajiPerBulan = array();
        $penghasilanTidakTeratur = array();
        $pphYangDilunasi = array();

        foreach($collection as $key => $item){
            array_push($gajiPerBulan, [
                'nip' => $item['nip'],
                'bulan' => $item['bulan'],
                'tahun' => $item['tahun'],
                'gj_pokok' => $item['gj_pokok'],
                'gj_penyesuaian' => $item['gj_penyesuaian'],
                'tj_keluarga' => $item['tj_keluarga'],
                'tj_telepon' => $item['tj_telepon'],
                'tj_jabatan' => $item['tj_jabatan'],
                'tj_teller' => $item['tj_teller'],
                'tj_perumahan' => $item['tj_perumahan'],
                'tj_pelaksana' => $item['tj_pelaksana'],
                'tj_kesejahteraan' => $item['tj_kesejahteraan'],
                'tj_kemahalan' => $item['tj_kemahalan'],
                'tj_multilevel' => $item['tj_multilevel'],
                'tj_ti' => $item['tj_ti'],
                'tj_transport' => $item['tj_transport'],
                'tj_pulsa' => $item['tj_pulsa'],
                'tj_vitamin' => $item['tj_vitamin'],
                'uang_makan' => $item['uang_makan'],
                'dpp' => $item['dpp'],
            ]);

            $tanggal = $item['bulan'] == 7 ? '26-' : '25-';
            foreach($penghasilan_tidak_teratur as $ptt){
                array_push($penghasilanTidakTeratur, [
                    'nip' => $item['nip'],
                    'id_tunjangan' => $this->getIdTunjangan($ptt),
                    'nominal' => $item[$ptt],
                    'tahun' => $item['tahun'],
                    'bulan' => $item['bulan'],
                    'created_at' => date( 'Y-m-d h:i:s',strtotime($tanggal . $item['bulan'] . '-' . $item['tahun']))
                ]);
            }

            array_push($pphYangDilunasi, [
                'nip' => $item['nip'],
                'bulan' => $item['bulan'],
                'tahun' => $item['tahun'],
                'total_pph' => $item['pph_yang_dilunasi'],
                'tanggal' => date('Y-m-d', strtotime('25-' . $item['bulan'] . '-' . $item['tahun'])),
                'created_at' => date('Y-m-d h:i:s', strtotime('25-' . $item['bulan'] . '-' . $item['tahun'])),
            ]);
        }

        try{
            DB::beginTransaction();
            DB::table('gaji_per_bulan')
                ->insert($gajiPerBulan);
            DB::table('penghasilan_tidak_teratur')
                ->insert($penghasilanTidakTeratur);
            DB::table('pph_yang_dilunasi')
                ->insert($pphYangDilunasi);
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            return $e;
        } catch(QueryException $e){
            DB::rollBack();
        }
    }

    static function getIdTunjangan($name): int {
        $name = $name == 'thr' ? 'tunjangan hari raya' : $name;
        $data = DB::table('mst_tunjangan')
            ->where('nama_tunjangan', 'like', '%' . str_replace('_', ' ', $name) . '%')
            ->select('id')
            ->first();
            
        return intval($data->id);
    }
}
