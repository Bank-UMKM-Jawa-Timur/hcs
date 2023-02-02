<?php

namespace App\Http\Resources\Api;

use App\Service\EntityService;
use Illuminate\Http\Resources\Json\JsonResource;

class KaryawanResource extends JsonResource
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
            'nip' => $this->nip,
            'nama' => $this->nama_karyawan,
            'jabatan' => $this->jabatan,
            'entitas' => EntityService::getEntity($this->kd_entitas),
            'bagian' => $this->bagian,
        ];
    }
}
