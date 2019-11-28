<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Ticket\TicketResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Receipt\ReceiptResource;
use App\Http\Resources\Tracking\TrackingResource;
use App\Http\Resources\Status\StatusResource;

class TaskResource extends JsonResource
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
            "count_hours" => $this->count_hours,
            "priority" => $this->priority,
            "deadline" => $this->deadline,
            "status" => new StatusResource($this->task_status),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
            "ticket" => new TicketResource($this->whenLoaded('ticket')),
            "project" => new ProjectResource($this->whenLoaded('project')),
            "responsible" => new UserResource($this->whenLoaded('responsible')),
            "receipts" => ReceiptResource::collection($this->whenLoaded('receipts')),
            "tracking_history" => TrackingResource::collection($this->whenLoaded('tracking_history'))
        ];
    }
}
