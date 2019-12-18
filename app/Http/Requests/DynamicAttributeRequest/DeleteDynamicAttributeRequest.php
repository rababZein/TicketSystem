<?php

namespace App\Http\Requests\DynamicAttributeRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ItemNotFoundException;

class DeleteDynamicAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can delete DynamicAttribute?

        if (auth()->user()->isAdmin() || auth()->user()->can('dynamic-attribute-delete')) {
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
            //
        ];
    }
}
