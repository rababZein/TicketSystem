<?php

namespace Modules\Activity\Entities;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model 
{

    protected $table = 'activities';
    public $timestamps = true;
    protected $fillable = array('subject', 'url', 'method', 'ip', 'agent', 'user_id', 'project_id', 'client_id', 'ticket_id', 'task_id');

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'id', 'ticket_id');
    }

    public function task()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_id');
    }

    public function receipt()
    {
        return $this->hasOne('App\Models\Receipt', 'id', 'receipt_id');
    }
}