<?php

namespace App\Http\Resources\select2;

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
            "id" => $this->nip,
            "text" => "{$this->nip} - {$this->nama_karyawan}",
            "nama" => $this->nama_karyawan,
            "jabatan" => $this->nama_jabatan,
        ];
    }
}
