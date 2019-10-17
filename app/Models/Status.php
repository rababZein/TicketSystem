<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model 
{

    protected $table = 'status';
    public $timestamps = false;
    protected $fillable = array('updated_by', 'created_by', 'name', 'description', 'main');

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task');
    }

    public function tickets()
    {
        return $this->belongsToMany('App\Models\Status');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Status');
    }

}