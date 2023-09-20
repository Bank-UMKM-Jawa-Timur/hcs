<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MstProfilKantorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ProfilKantorController extends Controller
{
    public function list() {
        $status = '';
        $req_status = 0;
        $message = '';
        $total_data = 0;
        $data = null;
        try {
            $data = MstProfilKantorModel::select(
                                            'mst_profil_kantor.kd_cabang',
                                            'c.nama_cabang',
                                            'c.alamat_cabang',
                                            'mst_profil_kantor.email',
                                            'mst_profil_kantor.telp',
                                        )
                                        ->join('mst_cabang AS c', 'c.kd_cabang', 'mst_profil_kantor.kd_cabang')
                                        ->orderBy('c.kd_cabang')
                                        ->where('c.kd_cabang', '1')
                                        ->get();
            if ($data) {
                $total_data = count($data);
                $data = $total_data > 0 ? $data : null;
            }

            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Successfully retrieved data';
        } catch (\Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'total_data' => $total_data,
                'data' => $data,
            ], $req_status);
        }
    }

    public function getByKode($kode) {
        $status = '';
        $req_status = 0;
        $message = '';
        $data = null;
        try {
            $data = MstProfilKantorModel::select(
                                            'mst_profil_kantor.kd_cabang',
                                            'c.nama_cabang',
                                            'c.alamat_cabang',
                                            'mst_profil_kantor.email',
                                            'mst_profil_kantor.telp',
                                        )
                                        ->join('mst_cabang AS c', 'c.kd_cabang', 'mst_profil_kantor.kd_cabang')
                                        ->where('c.kd_cabang', $kode)
                                        ->orderBy('c.kd_cabang')
                                        ->first();

            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Successfully retrieved data';
        } catch (\Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $req_status);
        }
    }
}
