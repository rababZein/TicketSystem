<?php

namespace App\Http\Requests\DynamicAttributeRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddDynamicAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // all who has permission
        if (auth()->user()->isAdmin() || auth()->user()->can('dynamic-attribute-create')) {
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
            'name' => 'required|string|unique:dynamic_attributes',
            'hidden' => 'boolean'
        ];
    }
}
