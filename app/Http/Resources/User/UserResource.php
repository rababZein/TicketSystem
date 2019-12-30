<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Metadata\MetadataResource;

class UserResource extends JsonResource
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
            "email" => $this->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "roles" => RoleResource::collection($this->whenLoaded('roles')),
            "type" => $this->type,
            "metadata" => new MetadataResource($this->whenLoaded('metadata')),
            "projects" => ProjectResource::collection($this->whenLoaded('projects')),
            // "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')
        ];
    }
}
