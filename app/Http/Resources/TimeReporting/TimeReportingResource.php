<?php

namespace App\Http\Resources\TimeReporting;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Task\TaskResource;

class TimeReportingResource extends JsonResource
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
            "project_id" => $this->project_id,
            "project_name" => $this->project_name,
            "owner_id" => $this->owner_id,
            "owner_name" => $this->owner_name,
            "time_counting" => $this->time_counting,
            "the_day" => $this->the_day,
        ];
    }
}
