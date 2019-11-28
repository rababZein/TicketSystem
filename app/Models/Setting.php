<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{

    protected $table = 'Setting';
    public $timestamps = true;
    protected $fillable = array('updated_by', 'created_by', 'start_number', 'last_number', 'current', 'updated_at', 'created_at');

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id',  'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

}