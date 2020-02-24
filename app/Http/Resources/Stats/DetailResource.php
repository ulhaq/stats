<?php

namespace App\Http\Resources\Stats;

use App\Http\Resources\ActionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
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
            'user' => $this->user,
            'client' => $this->client,
            'platform' => $this->platform,
            'created_at' => $this->created_at->toDateTimeString(),
            'actions' => ActionResource::collection($this->actions),
        ];
    }
}
