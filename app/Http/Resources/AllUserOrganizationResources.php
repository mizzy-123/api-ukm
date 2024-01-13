<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllUserOrganizationResources extends JsonResource
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
            'current_page' => $this->current_page,
            'data' => $this->data,
            'first_page_url' => $this->first_page_url,
            'from' => $this->from,
            'last_page' => $this->last_page,
            'last_page_url' => $this->last_page_url,
            'links' => $this->links,
            'next_page_url' => $this->next_page_url,
            'path' => $this->path,
            'per_page' => $this->per_page,
            'prev_page_url' => $this->prev_page_url,
            'to' => $this->to,
            'total' => $this->total
        ];
    }
}
