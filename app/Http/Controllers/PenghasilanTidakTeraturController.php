<?php

namespace App\Http\Controllers;

use App\Imports\PenghasilanImport;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanTidakTeraturController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $data = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();
        if($data == null){
            return null;
        }
        if($data->status_karyawan == "Tetap"){
            $jabatan = "Pegawai ".$data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        else{
            $jabatan = $data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        $dk = [
            'nip' => $data->nip,
            'nama' => $data->nama_karyawan,
            'jabatan' => $jabatan
        ];

        return response()->json($dk);
    }

    public function cariNama(Request $request)
    {
        $nama = $request->get('nama');
        $data = DB::table('mst_karyawan')
            ->where('nama_karyawan', 'like', '%'.$nama.'%')
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();
        if($data == null){
            return null;
        }
        if($data->status_karyawan == "Tetap"){
            $jabatan = "Pegawai ".$data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        else{
            $jabatan = $data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        $dk = [
            'nip' => $data->nip,
            'nama' => $data->nama_karyawan,
            'jabatan' => $jabatan
        ];

        return response()->json($dk);
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new PenghasilanImport;   
        $row = Excel::toArray($import, $file);
        // dd(count($row[0]));
        $import = $import->import($file);
        Alert::success('Berhasil', 'Berhasil mengimport '.count($row[0]).' data');
        return redirect()->route('penghasilan.index');
    }

    public function filter(Request $request) {
        $gaji = $totalGaji = $ptt = $bonus = [];

        for($month = 1; $month <= 12; $month++) {
            $pen = $bon = [];
            $data = DB::table('gaji_per_bulan')
                ->where('nip', $request->nip)
                ->where('bulan', $month)
                ->where('tahun', $request->tahun)
                ->first();

            $arr1 = [
                'gj_pokok' => $data?->gj_pokok ?? 0,
                'gj_penyesuaian' => $data?->gj_penyesuaian ?? 0,
                'tj_keluarga' => $data?->tj_keluarga ?? 0,
                'tj_telepon' => $data?->gj_pokok ?? 0,
                'tj_jabatan' => $data?->tj_jabatan ?? 0,
                'tj_teller' => $data?->tj_teller ?? 0,
                'tj_perumahan' => $data?->tj_perumahan ?? 0,
                'tj_kemahalan' => $data?->tj_kemahalan ?? 0,
                'tj_pelaksana' => $data?->tj_pelaksana ?? 0,
                'tj_kesejahteraan' => $data?->tj_kesejahteraan ?? 0,
                'tj_multilevel' => $data?->tj_multilevel ?? 0,
            ];

            $arr2 = [
                'tj_ti' => $data?->tj_ti ?? 0,
                'tj_transport' => $data?->tj_transport ?? 0,
                'tj_pulsa' => $data?->tj_pulsa ?? 0,
                'tj_vitamin' => $data?->tj_vitamin ?? 0,
                'uang_makan' => $data?->uang_makan ?? 0
            ];

            // Get penghasilan tidak teratur
            for($tunjangan = 16; $tunjangan <= 21; $tunjangan++) {
                $penghasilan = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $request->nip)
                    ->where('id_tunjangan', $tunjangan)
                    ->where('tahun', $request->tahun)
                    ->where('bulan', $month)
                    ->first();

                $pen[] = $penghasilan?->nominal ?? 0;
            }

            // Get bonus
            for($tunjangan = 21; $tunjangan <= 24; $tunjangan++) {
                $bns = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $request->nip)
                    ->where('id_tunjangan', $tunjangan)
                    ->where('tahun', $request->tahun)
                    ->where('bulan', $month)
                    ->first();

                $bon[] = $bns?->nominal ?? 0;
            }

            $ptt[] = $pen;
            $bonus[] = $bon;
            $gaji[] = array_merge($arr1, $arr2);
            $totalGaji[] = 0.03 * array_sum($arr1);
        }

        return view('penghasilan.gajipajak', [
            'gj' => $gaji,
            'jamsostek' => $totalGaji,
            'penghasilan' => $ptt,
            'bonus' => $bonus,
            'tahun' => $request->tahun,
            'request' => $request,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penghasilan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tj = DB::table('mst_tunjangan')
            ->whereIn('id', [11, 12, 13, 14])
            ->get();
        $tidak_teratur = DB::table('mst_tunjangan')
            ->whereIn('id', [16, 17, 18, 19, 20, 21])
            ->get();
        $bonus = DB::table('mst_tunjangan')
            ->whereIn('id', [22, 23, 24])
            ->get();

        return view('penghasilan.add', [
            'tj' => $tj,
            'tidak_teratur' => $tidak_teratur,
            'bonus' => $bonus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $nip = $request->nip;
        try{
            if($request->get('nominal_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_teratur')); $i++){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_teratur')[$i],
                            'nominal' => $request->get('nominal_teratur')[$i],
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_tidak_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_tidak_teratur')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_tidak_teratur')[$i],
                            'nominal' => $request->get('nominal_tidak_teratur')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_bonus')[0] != null){
                for($i = 0; $i < count($request->get('nominal_bonus')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_bonus')[$i],
                            'nominal' => $request->get('nominal_bonus')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan');
            return redirect()->route('penghasilan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
