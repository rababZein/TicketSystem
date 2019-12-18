<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user_id =$this->route('user');
        $owner = User::find($user_id);

        if (auth()->user()->isAdmin() || auth()->user()->can('user-edit') || $owner->id == auth()->user()->id) {
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
        $id = $this->route('user');
        
        return [
            'name' => 'string|max:191',
            'email' => 'string|email|max:191|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:6',
            'type' => 'string',
            'roles' => 'array',
            'roles.*.id' => 'integer',
            'roles.*.name' => 'string',
            'dynamic_attributes' => 'array',
            'dynamic_attribute.*.id' => 'required|integer',
            'dynamic_attribute.*.value' => 'required|string',
        ];
    }
}
