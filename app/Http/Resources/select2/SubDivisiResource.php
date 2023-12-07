<?php

namespace App\Http\Resources\select2;

use Illuminate\Http\Resources\Json\JsonResource;

class SubDivisiResource extends JsonResource
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
            "id" => $this->kd_subdiv,
            "text" => "$this->kd_subdiv - $this->nama_subdivisi",
            "kode" => $this->kd_subdiv,
            "nama" => $this->nama_subdivisi,
            "kode_divisi" => $this->kd_divisi,
        ];
    }
}
