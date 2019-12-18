<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Ticket\TicketResource;

class ProjectResource extends JsonResource
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
            "task_rate" => $this->task_rate,
            "budget_hours" => $this->budget_hours,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
            "owner" => UserResource::make($this->whenLoaded('owner')),
            "project_assign" => UserResource::collection($this->assigns),
            "tasks" => TaskResource::collection($this->whenLoaded('tasks')),
            "tickets" => TicketResource::collection($this->whenLoaded('tickets')),
        ];
    }
}
