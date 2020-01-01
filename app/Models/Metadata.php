<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Metadata extends Model 
{
    use HasRoles;
    use SoftDeletes;

    protected $guard_name = 'api';

    protected $table = 'metadata';
    public $timestamps = true;

    protected $fillable = array(
        'created_at',
        'updated_at',
        'user_id',
        'created_by',
        'updated_by',
        'debitor_number',
        'customer_number',
        'company',
        'addition_company',
        'address',
        'first_name',
        'last_name',
        'gender',
        'telephone',
        'mobile',
        'fax',
        'website',
        'birth_date',
        'eBay_user',
        'tax_number',
        'tax_id',
        'commerical_register',
        'street_number',
        'additional_address',
        'postal_code',
        'city_code',
        'country',
        'state',
        'customer_from',
        'customer_group',
        'first_contact_by',
        'customer_of_company',
        'language',
        'print_templates_set',
        'payment_deadline',
        'payment',
        'discount',
        'description'
    );

    public function user()
    {
        return $this->hasOne('App\Models\User','id', 'owner_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id',  'created_by');
    }

    public function updater()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
