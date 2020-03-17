<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'visitor' => $this->visitor,
            'client' => $this->client,
            'platform' => $this->platform,
            'created_at' => $this->created_at->toDateTimeString(),
            'actions' => ActionResource::collection($this->whenLoaded('actions')),
            'variables' => VariableResource::collection($this->whenLoaded('variables')),
        ];
    }
}
