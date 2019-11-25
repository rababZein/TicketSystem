<?php

namespace Modules\TicketComment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Ticket\TicketResource;

class TicketCommentResource extends JsonResource
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
            "send_mail" => $this->send_mail,
            "ticket" => new TicketResource($this->whenLoaded('ticket')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
        ];
    }
}
