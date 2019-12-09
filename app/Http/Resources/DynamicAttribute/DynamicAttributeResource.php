<?php

namespace App\Http\Resources\DynamicAttribute;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class DynamicAttributeResource extends JsonResource
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
            "hidden" => $this->hidden,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
            "users" => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
