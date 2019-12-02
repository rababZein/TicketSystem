<?php

namespace App\Http\Resources\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\Setting\SettingResource;

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
            "number" => $this->number,
            "description" => $this->description,
            "read" => $this->read,
            "status" => new StatusResource($this->ticket_status),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
            "project" => new ProjectResource($this->whenLoaded('project')),
            "setting" => new SettingResource($this->whenLoaded('setting')),
            "tasks" => TaskResource::collection($this->whenLoaded('tasks'))
        ];
    }
}
