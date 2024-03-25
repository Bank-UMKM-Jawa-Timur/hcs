<?php

namespace Database\Seeders;

use App\Models\KeluargaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEnumKeluargaTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = KeluargaModel::select('id', 'enum')
                            ->whereIn('enum', ['ANAK1', 'ANAK2'])
                            ->get();
        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                DB::table('keluarga')
                    ->where('id', $item->id)
                    ->update([
                        'enum' => 'Anak',
                        'anak_ke' => $item->enum == 'ANAK1' ? 1 : 2,
                    ]);
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
