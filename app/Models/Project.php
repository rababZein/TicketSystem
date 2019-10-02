<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Project extends Model 
{
    use HasRoles;

    protected $guard_name = 'api';

    protected $table = 'projects';
    public $timestamps = false;

    protected $fillable = array('created_at', 'updated_at', 'name', 'description', 'owner_id', 'task_rate', 'budget_hours', 'created_by', 'updated_by');

    public function owner()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'updated_by');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function assigns()
    {
        return $this->belongsToMany('App\Models\User','project_assigns', 'project_id', 'assign_to');
    }

    public function search($searckKey)
    {
        return Project::with('tasks', 'tickets', 'assigns')
                    ->select('projects.*')
                    ->leftJoin('tasks', 'tasks.project_id', '=', 'projects.id')
                    ->leftJoin('tickets', 'tickets.project_id', '=', 'projects.id')
                    ->where('tasks.name', 'like', '%'.$searckKey.'%')
                    ->orWhere('tickets.name', 'like', '%'.$searckKey.'%')
                    ->orWhere('projects.name', 'like', '%'.$searckKey.'%')
                    ->get();

    }

}