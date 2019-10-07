<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking_task extends Model 
{

    protected $table = 'tracking_tasks';
    public $timestamps = false;
    protected $fillable = array('start_date', 'end_date', 'count_time');

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

}