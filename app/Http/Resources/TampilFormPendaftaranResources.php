<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TampilFormPendaftaranResources extends JsonResource
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
            "id" => $this->id,
            "status" => $this->status,
            "name_organization" => $this->organization->name_organization,
        ];
    }
}
