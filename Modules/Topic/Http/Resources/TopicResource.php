<?php

namespace Modules\Topic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\User\UserResource;

use Modules\File\Http\Resources\FileResource;
use Modules\Category\Http\Resources\CategoryResource;


class TopicResource extends JsonResource
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
            "category" => new CategoryResource($this->whenLoaded('category')),
            "files" => FileResource::collection($this->whenLoaded('files')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
        ];
    }
}
