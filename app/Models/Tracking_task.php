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

    public function tarking($task_id)
    {
        Tracking_task::with('task')
                    ->select('count(tracking_tasks.end_at - tracking_task.start_at)')
                    ->where('tracking_tasks.task_id', $task_id)
                    ->get();
    }

}