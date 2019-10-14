<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingResource extends JsonResource
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
            "comment" => $this->comment,
            "start_at" => $this->start_at,
            "end_at" => $this->end_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => UserResource::collection($this->created_by),
            "updated_by" => UserResource::collection($this->whenLoaded('updateted_by')),
            "task" => TaskResource::collection($this->task),
        ];
    }
}
