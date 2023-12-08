<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PotonganRequest extends FormRequest
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
            'nip' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'kredit_koperasi' => 'required',
            'iuran_koperasi' => 'required',
            'kredit_pegawai' => 'required',
            'iuran_ik' => 'required',
        ];
    }
}
