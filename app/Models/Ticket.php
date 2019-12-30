<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model 
{
    use HasRoles;
    use SoftDeletes;

    protected $table = 'tickets';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at','name', 'description', 'project_id', 'status_id', 'email_id', 'created_by', 'updated_by', 'setting_id', 'number');

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
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\Ticket_file');
    }

    public function mails()
    {
        return $this->hasMany('App\Models\Ticket_mail');
    }
  
    public function setting()
    {
        return $this->hasOne('App\Models\Setting', 'id', 'setting_id');
    }

    public function ownTickets($id)
    {
        return Ticket::with('project.owner')
                    ->select('tickets.*')
                    ->join('projects', 'tickets.project_id', 'projects.id')
                    ->join('project_assigns', 'project_assigns.project_id', '=', 'projects.id')
                    ->where('project_assigns.assign_to', $id)
                    ->orWhere('tickets.created_by', $id)
                    ->paginate();

    }

}