<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking_task extends Model 
{

    protected $table = 'tracking_tasks';
    public $timestamps = false;
    protected $fillable = array('comment', 'start_at', 'end_at', 'count_time','task_id', 'created_at', 'updated_at', 'created_by', 'updated_by');

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    public function tarking($task_id)
    {
        return Tracking_task::where('tracking_tasks.task_id', $task_id)
                    ->sum("tracking_tasks.count_time");
    }

}