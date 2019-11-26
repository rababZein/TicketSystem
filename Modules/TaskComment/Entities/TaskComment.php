<?php

namespace Modules\TaskComment\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model 
{
    use HasRoles;
    use SoftDeletes;

    protected $table = 'task_comments';
    public $timestamps = false;
    protected $fillable = array('created_at', 'created_by', 'updated_at', 'updated_by', 'comment', 'task_id');

    public function task()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

}