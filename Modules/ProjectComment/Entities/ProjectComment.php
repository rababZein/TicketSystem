<?php

namespace Modules\ProjectComment\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectComment extends Model 
{
    use HasRoles;
    use SoftDeletes;

    protected $table = 'project_comments';
    public $timestamps = false;
    protected $fillable = array('created_at', 'created_by', 'updated_at', 'updated_by', 'comment', 'Project_id');

    public function project()
    {
        return $this->hasOne('App\Models\User', 'id', 'project_id');
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