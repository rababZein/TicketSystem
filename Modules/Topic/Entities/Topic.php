<?php

namespace Modules\Topic\Entities;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model 
{

    protected $table = 'topics';
    public $timestamps = false;
    protected $fillable = array('created_at', 'updated_at', 'created_by', 'updated_by', 'name', 'description', 'category_id');

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function category()
    {
        return $this->belongsTo('Modules\Category\Entities\Category');
    }

    public function files()
    {
        return $this->hasMany('Modules\File\Entities\File');
    }

}