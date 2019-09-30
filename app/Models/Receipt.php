<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model 
{

    protected $table = 'receipts';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at', 'created_by', 'updated_by', 'name', 'description', 'total', 'is_paid', 'task_id');

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'updated_by');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

}