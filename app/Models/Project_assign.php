<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project_assign extends Model 
{

    protected $table = 'project_assigns';
    public $timestamps = false;
    protected $fillable = array('assign_to', 'project_id');

}