<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model 
{

    protected $table = 'tasks';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at', 'name', 'description', 'responsible_id', 'created_by', 'updated_by', 'ticket_id', 'project_id','count_hours', 'status_id');

    public function responsible()
    {
        return $this->hasOne('App\Models\User', 'id', 'responsible_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function receipts()
    {
        return $this->hasMany('App\Models\Receipt');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

    public function tracking_history()
    {
        return $this->hasMany('App\Models\Tracking_task');
    }

    public function task_status()
    {
        return $this->hasOne('App\Models\Status');
    }

}
