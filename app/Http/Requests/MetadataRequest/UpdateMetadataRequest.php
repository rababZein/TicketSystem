<?php

namespace App\Http\Requests\MetadataRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Metadata;

class UpdateMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $metadata_id =$this->route('metadata');
        $metadata = Metadata::find($metadata_id);

        if (auth()->user()->isAdmin() || auth()->user()->can('user-edit') || $metadata->id == auth()->user()->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {      
        return [
            'user_id' => 'required|exists:users,id|unique:metadata,user_id,'.$this->metadata->id.',id,deleted_at,NULL',
            'debitor_number' => 'nullable|string',
            'customer_number' => 'nullable|string',
            'company' => 'nullable|string',
            'addition_company' => 'nullable|string',
            'address' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'gender' => 'nullable|string',
            'telephone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'fax' => 'nullable|string',
            'website' => 'nullable|string',
            'birth_date' => 'nullable|string',
            'eBay-user' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'commerical_register' => 'nullable|string',
            'street_number' => 'nullable|string',
            'additional_address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city_code' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'customer_from' => 'nullable|string',
            'customer_group' => 'nullable|string',
            'first_contact_by' => 'nullable|string',
            'customer_of_company' => 'nullable|string',
            'language' => 'nullable|string',
            'print_templates_set' => 'nullable|string',
            'payment_deadline' => 'nullable|string',
            'payment' => 'nullable|string',
            'discount' => 'nullable|string',
            "description" => 'nullable|string',
        ];
    }
}
