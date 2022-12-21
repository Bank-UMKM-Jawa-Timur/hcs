<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nip' => 'required|alpha_num|exists:mst_karyawan,nip',
            'id_jabatan_baru' => 'required|not_in:-|exists:mst_jabatan,kd_jabatan',
            'tanggal_pengesahan' => 'required',
            'bukti_sk' => 'required',
        ];
    }
}
