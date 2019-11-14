<?php

namespace Modules\ProjectComment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Project\ProjectResource;

class ProjectCommentResource extends JsonResource
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
            "comment" => $this->comment,
            "project" => new ProjectResource($this->whenLoaded('project')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource('creator'),
            "updated_by" => new UserResource('updater'),
        ];
    }
}
