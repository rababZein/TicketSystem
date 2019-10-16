<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
