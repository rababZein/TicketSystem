<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function inProgressTracking($task_id)
    {
        return Tracking_task::whereNull('end_at')
                    ->where('task_id', $task_id)
                    ->orderBy('start_at', 'desc')
                    ->first();
    }

    public function history($task_id)
    {
        return Tracking_task::where('task_id', $task_id)
                    ->get();
    }

    public function timeReporting($fromDate, $toDate, $employeeId, $projectId)
    {
        return DB::select('
        SELECT 
        p.id project_id,
        p.name project_name,
        p.owner_id owner_id,
        u.name owner_name,
        SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tt.end_at, tt.start_at)))) time_counting,
        date(tt.start_at) the_day 
        from tracking_tasks tt,
             projects p,
             tasks t,
             users u
        where tt.task_id = t.id
        and p.id = t.project_id
        and u.id = p.owner_id
        and date(tt.start_at) between ? and ?
        and tt.created_by = ?
        and (p.id = ? or ? is null)
        
        GROUP by p.id,
                p.name,
                p.owner_id,
                u.name,
                date(tt.start_at)', 
        [$fromDate, $toDate, $employeeId, $projectId, $projectId]);
    }
}