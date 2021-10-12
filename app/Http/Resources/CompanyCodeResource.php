<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name ' => $this->name,
            'code ' => $this->code,
            'end_date ' => $this->end_date,
            'likes_count ' => $this->likes_count,
            'dislikes_count ' => $this->dislikes_count,
            'user ' => $this->user->first_name,
        ];
    }
}
