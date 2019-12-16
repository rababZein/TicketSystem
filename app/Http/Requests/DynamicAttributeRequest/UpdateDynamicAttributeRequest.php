<?php

namespace App\Http\Requests\DynamicAttributeRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ItemNotFoundException;

class UpdateDynamicAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can update ??

        if (auth()->user()->isAdmin() || auth()->user()->can('dynamic-attribute-update')) {
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
            'name' => 'required|string|unique:dynamic_attributes,name,'.$this->dynamicAttribute->id,
            'hidden' => 'boolean'
        ];
    }
}
