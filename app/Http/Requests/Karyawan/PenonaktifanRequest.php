<?php

namespace App\Http\Requests\Karyawan;

use App\Enum\KategoriPenonaktifan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PenonaktifanRequest extends FormRequest
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
        if ($this->isMethod('GET')) return [];

        return [
            'nip' => 'required|exists:mst_karyawan,nip',
            'tanggal_penonaktifan' => 'required|date',
            'kategori_penonaktifan' => [new Enum(KategoriPenonaktifan::class)],
            'sk_pemberhentian' => 'required|mimes:pdf',
        ];
    }
}
