<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model 
{

    protected $table = 'tickets';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at','name', 'description', 'project_id', 'status_id', 'created_by', 'updated_by');

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function ticket_status()
    {
        return $this->belongsTo('App\Models\Status');
    }

}