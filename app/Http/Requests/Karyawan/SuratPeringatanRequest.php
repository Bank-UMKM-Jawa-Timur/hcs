<?php

namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;

class SuratPeringatanRequest extends FormRequest
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
            'no_sp' => 'required',
            'tanggal_sp' => 'required|date',
            'pelanggaran' => 'required',
            'sanksi' => 'required',
        ];
    }
}
