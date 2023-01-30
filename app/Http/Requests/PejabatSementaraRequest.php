<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PejabatSementaraRequest extends FormRequest
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
            'nip' => 'required|exists:mst_karyawan,nip',
            'tanggal_mulai' => 'required|date',
            'kd_jabatan' => 'required|exists:mst_jabatan,kd_jabatan',
            'no_sk' => 'required',
            'file_sk' => 'required|mimes:pdf'
        ];
    }
}
