<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JadwalPiketResource extends JsonResource
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
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'nama_hari' => $this->nama_hari,
            'user_piket' => UserPiketResource::collection($this->user_piket),
        ];
    }
}
