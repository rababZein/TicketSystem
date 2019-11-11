<?php

namespace Modules\File\Entities;

use Illuminate\Database\Eloquent\Model;

class File extends Model 
{

    protected $table = 'files';
    public $timestamps = false;
    protected $fillable = array('topic_id', 'created_at', 'updated_at', 'name', 'created_by', 'description', 'updated_by');

    public function topic()
    {
        return $this->belongsTo('Modules\Topic\Entities\Topic');
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