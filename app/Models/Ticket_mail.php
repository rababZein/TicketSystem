<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket_mail extends Model 
{

    protected $table = 'ticket_mails';
    public $timestamps = true;
    protected $fillable = array('ticket_id', 'email', 'created_by', 'updated_by');

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id',  'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'id', 'ticket_id');
    }

}