<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);
        return [
            'user' => $this->user,
            'client' => $this->client,
            'platform' => $this->platform,
            'created_at' => $this->created_at->toDateTimeString(),
            'actions' => ActionResource::collection($this->actions),
        ];
    }
}
