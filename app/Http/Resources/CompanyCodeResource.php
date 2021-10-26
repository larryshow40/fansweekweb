<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

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
            'name' => $this->name,
            'code' => $this->code,
            'end_date' => Carbon::parse($this->end_date)->format('d F'),
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'user' => $this->user->first_name,
            'liked' => $this->likes->where('user_id', Sentinel::getUser()->id)->first() ? true : false,
            'created_at' => Carbon::parse($this->created_at)->format('d F'),
        ];
    }
}
