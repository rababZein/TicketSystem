<?php

namespace Modules\TicketComment\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TicketComment extends Model 
{
    use HasRoles;

    protected $table = 'ticket_comments';
    public $timestamps = false;
    protected $fillable = array('created_at', 'created_by', 'updated_at', 'updated_by', 'comment', 'Ticket_id');

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'id', 'ticket_id');
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