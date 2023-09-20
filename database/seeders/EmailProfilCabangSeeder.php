<?php

namespace Database\Seeders;

use App\Models\MstProfilKantorModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailProfilCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cabang = DB::table('mst_cabang')->select('kd_cabang', 'nama_cabang')->orderBy('kd_cabang')->get();

        foreach ($cabang as $value) {
            $email = 'cabang'.strtolower(str_replace(' ', '', $value->nama_cabang)).'@bankumkm.id';
            $profil_exists = MstProfilKantorModel::select('id', 'kd_cabang')
                                                ->where('kd_cabang', $value->kd_cabang)
                                                ->first();
            if ($profil_exists) {
                // update email
                MstProfilKantorModel::where('kd_cabang', $value->kd_cabang)->update([
                    'email' => $email,
                ]);
            }
            else {
                // create a new profile
                MstProfilKantorModel::create([
                    'kd_cabang' => $value->kd_cabang,
                    'email' => $email,
                ]);
            }
        }
    }
}
