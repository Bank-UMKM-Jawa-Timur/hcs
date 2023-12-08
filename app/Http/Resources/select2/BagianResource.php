<?php

namespace App\Http\Resources\select2;

use Illuminate\Http\Resources\Json\JsonResource;

class BagianResource extends JsonResource
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
            "id" => $this->kd_bagian,
            "text" => "$this->kd_bagian - $this->nama_bagian",
            "kode" => $this->kd_bagian,
            "nama" => $this->nama_bagian,
        ];
    }
}
