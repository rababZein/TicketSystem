<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model 
{

    protected $table = 'tasks';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at', 'name', 'description', 'responsible_id', 'created_by', 'updated_by', 'ticket_id', 'project_id','count_hours');

    public function responsible()
    {
        return $this->hasOne('App\Models\User', 'id', 'responsible_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'updated_by');
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

}