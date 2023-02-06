<?php

namespace App\Http\Requests\SuratPeringatan;

use Illuminate\Foundation\Http\FormRequest;

class HistoryRequest extends FormRequest
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
        $rules = [
            'tanggal' => 'array'
        ];

        if ($this->first_date || $this->end_date) {
            $rules['first_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after:first_date';
        }

        return $rules;
    }
}
