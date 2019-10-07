<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking_task extends Model 
{

    protected $table = 'tracking_tasks';
    public $timestamps = false;
    protected $fillable = array('comment', 'start_at', 'end_at', 'count_time');

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

}