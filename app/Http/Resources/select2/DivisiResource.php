<?php

namespace App\Http\Resources\select2;

use Illuminate\Http\Resources\Json\JsonResource;

class DivisiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->kd_divisi,
            "text" => "$this->kd_divisi - $this->nama_divisi",
            "kode" => $this->kd_divisi,
            "nama" => $this->nama_divisi,
        ];
    }
}
