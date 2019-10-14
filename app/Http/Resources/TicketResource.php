<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "read" => $this->read,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->creator),
            "updated_by" => new UserResource($this->updater),
            "project" => new ProjectResource($this->project),
        ];
    }
}
