<?php

namespace Modules\ClientComment\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ClientComment extends Model 
{
    use HasRoles;

    protected $table = 'client_comments';
    public $timestamps = false;
    protected $fillable = array('created_at', 'created_by', 'updated_at', 'updated_by', 'comment', 'client_id');

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
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