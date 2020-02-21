<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariableResource extends JsonResource
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
            'variable' => $this->variable,
            'value' => $this->value,
            'created_at' => $this->created_at->diffForHumans(),
            'action' => new ActionResource($this->whenLoaded('action')),
        ];
    }
}
