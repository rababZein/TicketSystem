<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model 
{

    protected $table = 'tickets';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at', 'read', 'project_id', 'created_by', 'updated_by');

    public function project()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->hasOne('App\Models\User', 'updated_by');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

}