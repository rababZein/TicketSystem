<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicAttribute extends Model 
{
    use HasRoles;
    use SoftDeletes;

    protected $guard_name = 'api';

    protected $table = 'dynamic_attributes';
    public $timestamps = true;

    protected $fillable = array('created_at', 'updated_at', 'name', 'hidden', 'created_by', 'updated_by');

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id',  'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','user_dynamic_attributes', 'dynamic_attribute_id', 'user_id');
    }
}