<?php

namespace App\Http\Resources\Metadata;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class MetadataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'debitor_number' => $this->debitor_number,
            'customer_number' => $this->customer_number,
            'company' => $this->company,
            'addition_company' => $this->addition_company,
            'address' => $this->address,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'telephone' => $this->telephone,
            'mobile' => $this->mobile,
            'fax' => $this->fax,
            'website' => $this->website,
            'birth_date' => $this->birth_date,
            'eBay-user' => $this->eBay_user,
            'tax_number' => $this->tax_number,
            'tax_id' => $this->tax_id,
            'commerical_register' => $this->commerical_register,
            'street_number' => $this->street_number,
            'additional_address' => $this->additional_address,
            'postal_code' => $this->postal_code,
            'city_code' => $this->city_code,
            'country' => $this->country,
            'state' => $this->state,
            'customer_from' => $this->customer_from,
            'customer_group' => $this->customer_group,
            'first_contact_by' => $this->first_contact_by,
            'customer_of_company' => $this->customer_of_company,
            'language' => $this->language,
            'print_templates_set' => $this->print_templates_set,
            'payment_deadline' => $this->payment_deadline,
            'payment' => $this->payment,
            'discount' => $this->discount,
            "description" => $this->description,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => new UserResource($this->whenLoaded('creator')),
            "updated_by" => new UserResource($this->whenLoaded('updater')),
        ];
    }
}
