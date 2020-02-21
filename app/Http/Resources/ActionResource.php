<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
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
            'location' => $this->location,
            'action' => $this->action,
            'target' => $this->target,
            'created_at' => $this->created_at->diffForHumans(),
            'session' => new SessionResource($this->whenLoaded('session')),
        ];
    }
}
