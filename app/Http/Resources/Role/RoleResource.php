<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Permission\PermissionResource;

class RoleResource extends JsonResource
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
            //"guard_name" => $this->guard_name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "permissions" => PermissionResource::collection($this->permissions),
        ];
    }
}
